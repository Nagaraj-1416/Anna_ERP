<script>
    app.controller('DamagedStockController', ['$scope', '$http', function ($scope, $http) {
        var url = '{{ route('stock.damaged.index') }}';
        $scope.main = [];
        $scope.company = null;
        $scope.param = {
            companyId: ''
        };

        $scope.el = {
            loader: $('.cus-create-preloader')
        };

        $('.company-drop-down').dropdown({
            forceSelection: false,
            saveRemoteData: false,
            onChange: function (val) {
                $scope.param.companyId = val;
                $scope.generate();
            }
        });

        $scope.generate = function () {
            $scope.loading = true;
            $http.get(url + '?' + $.param($scope.param)).then(function (response) {
                $scope.main = response.data;
                $scope.items = $scope.main.items;
                $scope.company = $scope.main.company;
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
            scope.searchingObject[scope.comany.id] = false;
            $('.cardScroll').slimScroll({
                height: '500px'
            });
        };
    });
</script>