<script src="{{ asset('js/vendor/slidereveal.js') }}"></script>
@include('general.helpers')
@include('general.distance-calculator.index')
<script>
    app.controller('StatsController', function ($scope, $http) {
        $scope.el = {
            btn: $('.sidebar-btn'),
            sidebar: $('#stats-sidebar'),
            loader: $('.stats-preloader'),
        };
        $scope.mainHeader = 'Stats';
        $scope.subHeader = 'Stats';
        $scope.myCurrentPage = 1;
        $scope.width = '1200px';
        $scope.route = null;

        $scope.init = function () {
            $scope.cusSlider = $scope.el.sidebar.slideReveal({
                // trigger: $scope.el.btn,
                position: "right",
                width: $scope.width,
                push: false,
                overlay: true,
                shown: function (slider, trigger) {
                    // init scroll for side bar body
                    $('#list-stats-body').slimScroll({
                        color: 'gray',
                        height: '100%',
                        railVisible: true,
                        alwaysVisible: false
                    });
                },
                show: function (slider, trigger) {
                    // $scope.hideLoader();
                    $scope.edit = false;
                    if (!$scope.$$phase) $scope.$apply()
                }
            });
        };

        $scope.init();
        $scope.el.btn.click(function () {
            $scope.route = $(this).data('route');
            $scope.showLoader();
            var width = $(this).data('width');
            if (width) {
                $scope.width = width;
            } else {
                $scope.width = '1200px';
            }
            $scope.init();
            $scope.toggleSideBar();
            $scope.getData();
        });
        // close side bar
        $scope.toggleSideBar = function () {
            $scope.cusSlider.slideReveal("toggle");
        };

        // show loader
        $scope.el.loader.addClass('hidden');
        $scope.showLoader = function () {
            $scope.el.loader.addClass('loading');
            $scope.el.loader.removeClass('hidden');
            $('#list-stats-body').addClass('hidden');
        };
        // hide loading
        $scope.hideLoader = function () {
            $scope.el.loader.removeClass('loading');
            $scope.el.loader.addClass('hidden');
            $('#list-stats-body').removeClass('hidden');
        };

        $scope.columns = [];
        $scope.data = [];
        $scope.total = '';
        $scope.total_columns = [];
        $scope.getData = function () {
            if ($scope.route) {
                $http.get($scope.route).then(function (response) {
                    $scope.columns = response.data.columns;
                    $scope.data = response.data.data;
                    $scope.mainHeader = response.data.mainHeader;
                    $scope.subHeader = response.data.subHeader;
                    $scope.total = response.data.total;
                    $scope.total_columns = response.data.total_columns;
                    $scope.allocation = response.data.allocation;
                    $scope.header_section = response.data.header_section;
                    $scope.width = '1500px';
                    $scope.hideLoader();
                    $scope.myCurrentPage = 1;
                })
            }
        };
        $scope.getLength = function (object, minus) {
            if (minus) {
                return (_.toArray(object).length - parseInt(minus));
            }
            return _.toArray(object).length;
        };

        $scope.statusLabelColor = function ($status) {
            return statusLabelColor($status);
        };
        $scope.distanceRoute = '{{ route('sales.distance.order.update', ['ID']) }}'
    }).directive('statsDirective', function () {
        return function (scope, element, attrs) {
            if (scope.value.hasOwnProperty('distance') && !scope.value.distance) {
                if (scope.value.gps_lat && scope.value.gps_long && scope.value.customer.gps_lat && scope.value.customer.gps_long) {
                    scope.value.distance = getDistance(scope.value.gps_lat, scope.value.gps_long, scope.value.customer.gps_lat, scope.value.customer.gps_long, scope.distanceRoute.replace('ID', scope.value.id)).toFixed(2) + 'KM';
                }
            }
        };
    });
</script>