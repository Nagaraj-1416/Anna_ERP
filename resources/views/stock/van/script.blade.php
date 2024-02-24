<script>
    app.controller('VanStockController', ['$scope', '$http', function ($scope, $http) {
        var url = '{{ route('stock.van.index') }}';
        $scope.main = [];
        $scope.location = null;
        $scope.param = {
            vanId: ''
        };

        $scope.el = {
            loader: $('.cus-create-preloader')
        };

        $('.vehicle-drop-down').dropdown({
            forceSelection: false,
            saveRemoteData: false,
            onChange: function (val) {
                $scope.param.vanId = val;
                $scope.generate();
            }
        });

        $scope.generate = function (vehicleId) {
            $scope.loading = true;
            $http.get(url + '?' + $.param($scope.param)).then(function (response) {
                $scope.main = response.data;
                $scope.items = $scope.main.items;
                $scope.location = $scope.main.location;
                $scope.loading = false;
                $scope.initScroll();
            });
        };

        $scope.initScroll = function () {
            $('.cardScroll').slimScroll({
                height: '500px'
            });
        }
    }
    ]).directive('cardDirective', function () {
        return function (scope, element, attrs) {
            scope.searchingObject[scope.vehicle.id] = false;
            $('.cardScroll').slimScroll({
                height: '500px'
            });
        };
    });
</script>