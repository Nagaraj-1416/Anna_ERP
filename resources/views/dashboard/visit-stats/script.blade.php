<script src="{{ asset('js/vendor/object-helper.js') }}"></script>
<script>
    app.controller('VisitStatsController', ['$scope', '$http', function ($scope, $http) {
        $scope.query = {
            fromDate: '',
            toDate: '',
            company: null,
            route: null,
            reason: null,
        };
        $scope.icons = {
            green: '{{ asset('images/icon/customer_green.png') }}',
            red: '{{ asset('images/icon/customer_red.png') }}',
            orange: '{{ asset('images/icon/customer_orange.png') }}',
        };
        $scope.companyDD = $('.company-drop-down');
        $scope.routeDD = $('.route-drop-down');
        $scope.reasonDD = $('.reason-drop-down');
        $scope.loading = false;
        //Initiate Date Range Drop down
        dateRangeDropDown($scope);
        $scope.companyDD.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            onChange: function (val) {
                $scope.query.company = val;
                $scope.routeDD.dropdown('clear');
                routeDropDown(val);
            }
        });

        function routeDropDown(company) {
            var url = '{{ route('setting.route.by.company.search', ['companyId']) }}';
            url = url.replace('companyId', company);
            $scope.routeDD.dropdown('setting', {
                apiSettings: {
                    url: url + '/{query}',
                    cache:false,
                },
                saveRemoteData:false,
                onChange: function(val, name){
                    $scope.query.route = val;
                }
            });
        }

        $scope.reasonDD.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            allowAdditions: true,
            onChange: function (val) {
                $scope.query.reason = val;
            }
        }).on('keyup', function () {
            var text = $(this).find('input[class="search"]').val();
            $scope.reasonDD.dropdown('set value', text);
        });

        $scope.errors = [];
        $scope.filterd = true;
        $scope.generate = function () {
            $scope.loading = true;
            $scope.filterd = true;
            $scope.fromDataForDisplay = $scope.query.fromDate;
            $scope.toDateForDisplay = $scope.query.toDate;
            var url = '{{ route('visit.stats') }}';
            $http.get(url + '?' + $.param($scope.query)).then(function (response) {
                $scope.loading = false;
                $scope.filterd = false;
                $scope.customers = response.data.customers;
                $scope.totalData = response.data.total;
                $scope.init();
                $scope.errors = [];
                $scope.setScroll();
            }).catch(function (error) {
                $scope.loading = false;
                $scope.errors = error.data.errors;
            })
        };

        $scope.hasError = function (name) {
            if ($scope.errors.hasOwnProperty(name)) {
                return $scope.errors[name][0];
            }
        };

        $scope.resetFilters = function () {
            $scope.query = {
                fromDate: '',
                toDate: '',
                company: null
            };
            dateRangeDropDown($scope);
            $scope.filterd = true;
            $scope.companyDD.dropdown('clear');
            $scope.routeDD.dropdown('clear');
            $scope.generate();
        };
        var markers = [];
        var map;
        $(document).ready(function () {
            initMap()
        });
        $scope.points = {};
        $scope.init = function () {
            $scope.coords = [];
            if ($scope.customers) {
                clearMarkers();
                $.each($scope.customers, function (key, value) {
                    var icon = $scope.icons.green;
                    if (value.customer.orders.length) {
                        var orders = value.customer.orders;
                        var closed = _.where(orders, {status: "Closed"}).length;
                        if (value.customer.orders.length !== closed) {
                            icon = $scope.icons.orange;
                        }
                    }
                    if (!value.customer.orders.length) {
                        icon = $scope.icons.red;
                    }
                    if (!$scope.points.hasOwnProperty(value.id) && value.customer.gps_lat && value.customer.gps_long) {
                        addMarkerWithTimeout(getLngLat(value.customer.gps_lat, value.customer.gps_long), key * 200, icon, value);
                    }
                    $scope.points[value.id] = getLngLat(value.customer.gps_lat, value.customer.gps_long);
                });
            }
        };

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 11,
                center: {lat: 9.720663, lng: 80.148921}
            });
        }

        function getLngLat(lat, lng) {
            return new google.maps.LatLng(lat, lng);
        }

        var infowindows = [];

        function addMarkerWithTimeout(position, timeout, icon, customer) {
            var marker = new google.maps.Marker({
                position: position,
                map: map,
                animation: google.maps.Animation.DROP,
                icon: icon,
            });

            markers.push(marker);
            var contentString = '';
            contentString = getContent(contentString, customer);
            addInfo(marker, contentString);
            marker.addListener('click', function () {
                infowindows[markers.indexOf(marker)].open(map, marker);
            });
        }

        function addInfo(marker, contentString) {
            if (contentString) {
                infowindows[markers.indexOf(marker)] = new google.maps.InfoWindow({
                    content: contentString
                });
            }
        }

        function getContent(contentString, customer) {
            contentString = '<div style="width: 350px;" id="content">' +
                '<h1 id="firstHeading" style="font-size: 16px !important;font-weight: 600;line-height: 25px;" class="firstHeading"> ' + customer.customer.display_name + '</h1>' +
                '<div id="bodyContent">' +
                '<span> <b> Total sales: </b>' + $scope.getOrderTotal(customer.customer).toFixed(2) + ' </span> <br />' +
                '<span> <b> Total outstanding: </b>' + $scope.getBalanced(customer.customer).toFixed(2) + ' </span> <br />' +
                '</div>' +
                ' </div>';
            return contentString;
        }

        function clearMarkers() {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
            }
            markers = [];
        }

        $scope.sum = function (array) {
            return _.reduce(array, function (memo, num) {
                return memo + num;
            }, 0);
        };

        $scope.getOrderTotal = function (customer) {
            var orders = customer.orders;
            return $scope.sum(_.pluck(orders, 'total'))
        };

        $scope.getInvoiced = function (customer) {
            var invoices = customer.invoices;
            return $scope.sum(_.pluck(invoices, 'amount'))
        };

        $scope.getPaid = function (customer) {
            var payments = customer.payments;
            return $scope.sum(_.pluck(payments, 'payment'))
        };

        $scope.getBalanced = function (customer) {
            return $scope.getOrderTotal(customer) - $scope.getPaid(customer);
        };

        $scope.customerRoute = '{{ route('sales.distance.customer.update', ['ID']) }}';
        $scope.route = '{{ route('map.index') }}';
        $scope.setScroll = function () {
            $('.scroll').slimScroll({
                height: '600px'
            });
        };
    }]).directive('customerDirective', function () {
        return function (scope, element, attrs) {
            if (!scope.customer.distance && scope.customer.gps_lat && scope.customer.gps_long && scope.customer.customer.gps_lat && scope.customer.customer.gps_long) {
                scope.customer.distance = getDistance(scope.customer.gps_lat, scope.customer.gps_long, scope.customer.customer.gps_lat,
                    scope.customer.customer.gps_long, scope.customerRoute.replace('ID', scope.customer.id));
            }
            if (scope.customer.distance) {
                var info = {
                    heading: scope.customer.customer.display_name,
                    code: scope.customer.customer.tamil_name,
                };
                var endInfo = {
                    heading: scope.customer.customer.display_name,
                    code: scope.customer.customer.tamil_name,
                    rep: scope.customer.daily_sale.rep.name
                };
                var routeParam = {
                    startLat: scope.customer.customer.gps_lat,
                    startLng: scope.customer.customer.gps_long,
                    startInfo: JSON.stringify(info),
                    endLat: scope.customer.gps_lat,
                    endLng: scope.customer.gps_long,
                    endInfo: JSON.stringify(endInfo),
                };

                scope.customer.route = scope.route + '?' + $.param(routeParam);
            }
        };
    });
</script>