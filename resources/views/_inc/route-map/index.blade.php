@include('layouts._inc.map-script')
<script>
    var markers = [];
    var map;
    var origin = null;
    var destination = null;
    var waypoints = [];
    var waypointsForSave = [];
    var directionsService;
    var directionsDisplay;
    var labelIndex = 0;
    var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    var showBlade = false;
    var reset = true;
    @if(isset($showBlade))
        showBlade = true;

    @endif
    function checkOld() {
                @if(old('_token'))
                @php
                    $formNames = ['start_point', 'end_point', 'way_points'];
                    $oldValues = [];
                        foreach ($formNames as $formName){
                            if(old($formName)){
                                $oldValues[$formName] = old($formName);
                            }
                        }
                @endphp
        var oldData = @json($oldValues);
        if (reset) {
            setRouteInMap(oldData);
        }
        @endif
    }

    // initial function calling after google map api responded
    function initialize() {
        directionsService = new google.maps.DirectionsService();
        directionsDisplay = new google.maps.DirectionsRenderer({
            draggable: !showBlade,
            map: map
        });
        var startlocation = new google.maps.LatLng(9.7107126, 80.010035);
        var myOptions = {
            zoom: 14.83,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            draggableCursor: "pointer",
            center: startlocation,
        };
        // map
        map = new google.maps.Map(document.getElementById("map"), myOptions);
        directionsDisplay.setMap(map);
        // event for path markers change
        directionsDisplay.addListener('directions_changed', function () {
            getDirection(directionsDisplay.getDirections());
        });
        addClickEvent(map);
        checkOld();
                @if(isset($route))
        var route = [{!! json_encode($route)  !!}][0];
        if (route) {
            setRouteInMap(route);
        }
        @endif
    }

    // Set paths in map
    function setRouteInMap(route) {
        var startpoint = JSON.parse(route.start_point);
        var endPoint = JSON.parse(route.end_point);
        var wayPointsForEdit = route.way_points;
        if (startpoint && endPoint) {
            $('#startPoint').val(startpoint);
            $('#endPoint').val(endPoint);
            origin = getLngLat(startpoint.lat, startpoint.lng);
            destination = getLngLat(endPoint.lat, endPoint.lng);
            waypoints = [];
            if (wayPointsForEdit) {
                $.each(JSON.parse(wayPointsForEdit), function (key, data) {
                    waypointsForSave.push(data);
                    var formated = {
                        location: getLngLat(data.lat, data.lng)
                    };
                    waypoints.push(formated)
                })
            }
            calcRoute();
        }
    }

    function getLngLat(lat, lng) {
        return new google.maps.LatLng(lat, lng);
    }

    function serializeLatLng(ll) {
        if (ll) {
            return {
                lat: ll.lat(),
                lng: ll.lng()
            }
        }
    }

    // get changed way points direction
    function getDirection(data) {
        var legs = data.routes[0].legs;
        var waypointsData = [];
        if (legs) {
            var legeslength = legs.length;
            $.each(legs, function (key, data) {
                waypointsData = data.via_waypoints;
                if (waypointsData.length) {
                    var object = [];
                    $.each(waypointsData, function (key, data) {
                        waypointsForSave.push(serializeLatLng(data));
                    });
                    if (object) {
                        $('#wayPoints').val(JSON.stringify(waypointsForSave));
                    }
                } else {
                    if (key === 0) {
                        $('#startPoint').val(JSON.stringify(serializeLatLng(data.start_location)));
                    }
                    if (legeslength === 1) {
                        $('#endPoint').val(JSON.stringify(serializeLatLng(data.end_location)));
                    }
                }
            });
        }
    }

    // click event for Map (add markers)
    function addClickEvent(map) {
        google.maps.event.addListener(map, 'click', function (event) {
            // start point
            if (!origin) {
                origin = event.latLng;
                addMarker(origin);
                $('#startPoint').val(JSON.stringify(serializeLatLng(origin)));
            } else if (!destination) {
                // end point
                destination = event.latLng;
                addMarker(destination);
                $('#endPoint').val(JSON.stringify(serializeLatLng(destination)));
                // draw map
                calcRoute()
            }
        });
    }

    // add marker when click event triggered
    function addMarker(latLng) {
        var marker = new google.maps.Marker({
            position: latLng,
            map: map,
            label: labels[labelIndex++ % labels.length],
            animation: google.maps.Animation.DROP,
            draggable: !showBlade
        });
        markers.push(marker);
    }

    /**
     * Draw Route in map
     */
    function calcRoute() {
        if (origin == null) {
            alert("Click on the map to add a start point");
            return;
        }
        if (destination == null) {
            alert("Click on the map to add an end point");
            return;
        }
        // Request object for google
        var request = {
            origin: origin,
            destination: destination,
            waypoints: waypoints,
            travelMode: google.maps.DirectionsTravelMode.DRIVING,
            optimizeWaypoints: true,
            avoidHighways: false,
        };
        // draw route
        directionsService.route(request, function (response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                clearMarkers();
                directionsDisplay.setDirections(response);
                directionsDisplay.setOptions({suppressMarkers: showBlade});
            }
        });

    }

    function clearMap() {
        destination = null;
        origin = null;
        labelIndex = 0;
        $('#endPoint').val('');
        $('#startPoint').val('');
        $('#wayPoints').val('');
        waypoints = [];
        waypointsForSave = [];
        reset = false;
        clearMarkers();
        initialize();
    }

    // clear markers function
    function clearMarkers() {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
    }
</script>