@if(isset($id))
    {!! form()->model($customer, ['url' => route('sales.customer.add.location', [$customer]), 'method' => 'POST']) !!}
    @if(!$customer->route)
        <div class="row">
            <div class="col-md-2">
                <div class="ui fluid normal search selection dropdown route-drop-down">
                    @if(isset($customer))
                        <input name="route_id" type="hidden"
                               value="{{ old('_token') ? old('route_id'): $customer->route_id }}">
                    @else
                        <input name="route_id" type="hidden" value="{{ old('_token') ? old('route_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose route</div>
                    <div class="menu">
                        @foreach(routeDropDown() as $key => $route)
                            <div class="item" data-value="{{ $key }}">{{ $route }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div id="map1"
                 style="height:355px;position:relative; margin-bottom: 10px;margin-top: 10px"></div>
            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">
            <div class="pull-right" style="margin-bottom: 10px">
                <button type="Submit" class="btn btn-success"><i class="fa fa-check"></i> Submit
                </button>
                <button type="Button" class="btn btn-inverse" onclick="addHidden()" id="cancel-btn"><i
                            class="fa fa-remove"></i> Cancel
                </button>
            </div>
        </div>
    </div>
    {{ form()->close() }}
@else
    <div id="map2"
         style="height:355px;position:relative; margin-bottom: 10px"></div>
    <div class="pull-right">
        <button class="btn btn-sm btn-primary" onclick="removeHidden()">Edit Location</button>
        <button class="btn btn-sm btn-danger" onclick="removeLocation()">Remove Location</button>
    </div>
@endif
@section('script')
    @parent
    <script>
        @if(!isset($id))
        $(document).ready(function () {
            initialize('map2', false);

        });
                @endif

        var addedMarker;
        var map;
        var directionsService;
        var directionsDisplay;
        var route;
        var polylineOptions = {
            strokeColor: '#0806ff',
            strokeOpacity: 0.5,
            strokeWeight: 8
        };
        var polylines = [];
        var startLocation;
        var stepPolyline;
        var lat;
        var lng;

        function removeHidden() {
            $('html, body').animate({
                scrollTop: $("#map1").offset().top
            }, 1000);
            addedMarker = null;
            $('#map_form').removeClass('hidden');
            initialize('map1', true);
        }

        function addHidden() {
            addedMarker = null;
            $('#map_form').addClass('hidden');
        }


        // initial function calling after google map api responded
        function initialize(mapId, dragable) {
            startLocation = new google.maps.LatLng(9.7107126, 80.010035);
            directionsService = new google.maps.DirectionsService();
            directionsDisplay = new google.maps.DirectionsRenderer({
                suppressPolylines: true,
                map: map
            });
            var myOptions = {
                zoom: 12.83,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                draggableCursor: "pointer",
                center: new google.maps.LatLng(9.7107126, 80.010035),
            };
            map = new google.maps.Map(document.getElementById(mapId), myOptions);
            directionsDisplay.setMap(map);
            setOldData(dragable);
        }

        function setOldData(dragable) {
            @if(isset($model) && $model->route)
                route = @json($model->route);
            @endif;
            @if(isset($model) && $model->gps_lat && $model->gps_long)
                lat = '{{ $model->gps_lat }}';
            lng = '{{ $model->gps_long }}';
            addToInput();
            var location = getLngLat(lat, lng);
            addMarker(null, location, dragable);
            @endif
            if (route) {
                setRouteInMap(route)
            }
        }

        // Set Route Map
        function setRouteInMap(route, clear) {
            var startPoint = JSON.parse(route.start_point);
            var endPoint = JSON.parse(route.end_point);
            var wayPointsForEdit = route.way_points;
            if (startPoint && endPoint) {
                $('#startPoint').val(startPoint);
                $('#endPoint').val(endPoint);
                origin = getLngLat(startPoint.lat, startPoint.lng);
                destination = getLngLat(endPoint.lat, endPoint.lng);
                wayPoints = [];
                if (wayPointsForEdit) {
                    $.each(JSON.parse(wayPointsForEdit), function (key, data) {
                        wayPointsForSave.push(data);
                        var formated = {
                            location: getLngLat(data.lat, data.lng)
                        };
                        wayPoints.push(formated)
                    })
                }
                calcRoute(clear);
            } else {
                new google.maps.event.addListener(map, 'click', function (evt) {
                    addMarker(evt, evt.latLng, true)
                })
            }
        }

        /**
         *Draw Route
         */
        function calcRoute(clear) {
            // Request object for google
            var request = {
                origin: origin,
                destination: destination,
                waypoints: wayPoints,
                travelMode: google.maps.DirectionsTravelMode.DRIVING,
                optimizeWaypoints: true,
                avoidHighways: false,
            };

            // draw route
            directionsService.route(request, function (response, status) {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsDisplay.setDirections(response);
                    directionsDisplay.setMap(map);
                    renderDirectionsPolyLines(response, clear);
                    directionsDisplay.setOptions({suppressMarkers: false});
                }
            });
        }

        /**
         *
         * @param lat
         * @param lng
         * @returns
         */
        function getLngLat(lat, lng) {
            return new google.maps.LatLng(lat, lng);
        }

        /**
         *
         * @param response
         */
        function renderDirectionsPolyLines(response, clear) {
            if (clear) {
                for (var i = 0; i < polylines.length; i++) {
                    polylines[i].setMap(null);
                }
                if (addedMarker) {
                    addedMarker.setMap(null);
                    addedMarker = null;
                }
            }
            var legs = response.routes[0].legs;
            for (i = 0; i < legs.length; i++) {
                var steps = legs[i].steps;
                for (j = 0; j < steps.length; j++) {
                    var nextSegment = steps[j].path;
                    stepPolyline = new google.maps.Polyline(polylineOptions);
                    for (k = 0; k < nextSegment.length; k++) {
                        stepPolyline.getPath().push(nextSegment[k]);
                    }
                    stepPolyline.setMap(map);
                    polylines.push(stepPolyline);
                    new google.maps.event.addListener(stepPolyline, 'click', function (evt) {
                        addMarker(evt, evt.latLng)
                    })
                }
            }
        }

        // Add marker Function
        function addMarker(evt, latLng, dragable) {
            if (!addedMarker) {
                addedMarker = new google.maps.Marker({
                    position: latLng,
                    map: map,
                    animation: google.maps.Animation.DROP,
                    draggable: dragable,
                });
                if (evt) {
                    handleEvent(evt);
                }
                addedMarker.addListener('drag', handleEvent);
                addedMarker.addListener('dragend', handleEvent);
            }
        }

        function handleEvent(event) {
            var latLng = serializeLatLng(event.latLng);
            lat = latLng.lat;
            lng = latLng.lng;
            addToInput();
        }

        function addToInput() {
            $('#lat').val(lat);
            $('#lng').val(lng);
        }

        function serializeLatLng(ll) {
            if (ll) {
                return {
                    lat: ll.lat(),
                    lng: ll.lng()
                }
            }
        }

        function removeLocation() {
            var removeUrl = '{{ route('sales.customer.remove.location', [$model]) }}';
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DB2828',
                confirmButtonText: 'Yes, Remove!'
            }).then(function (isConfirm) {
                if (isConfirm.value) {
                    $.ajax({
                        url: removeUrl,
                        type: 'POST',
                        data: {'_token': '{{ csrf_token() }}'},
                        success: function (result) {
                            swal(
                                'Removed!',
                                'Customer Location removed successfully!',
                                'success'
                            ).then(function () {
                                location.reload();
                            });
                        }
                    });
                }
            });
        }

        var routeDD = $('.route-drop-down');
        var routeLocation = $('.location-drop-down');
        var getRoute = '{{ route('setting.route.get', ['ROUTE']) }}';
        routeDD.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            onChange: function (value) {
                $.get(getRoute.replace('ROUTE', value), function (response) {
                    route = response[0];
                    setRouteInMap(route, true);
                })
            }
        });
    </script>
@endsection