<script>
    app.controller('StockSearchController', ['$scope', '$http', function ($scope, $http) {
        var url = '{{ route('stock.search') }}';
        $scope.stokes = [];
        $scope.productChooesed = false;
        $scope.param = {
            productId: '',
            ajax: true
        };
        $scope.loading = false;
        $scope.el = {
            loader: $('.cus-create-preloader')
        };

        $('.product-drop-down').dropdown({
            forceSelection: false,
            saveRemoteData: false,
            onChange: function (val) {
                $scope.param.productId = val;
                $scope.productChooesed = true;
                $scope.generate();
                console.log(123);
            }
        });

        $scope.generate = function () {
            $scope.loading = true;
            $scope.edit = true;
            $http.get(url + '?' + $.param($scope.param)).then(function (response) {
                $scope.stokes = response.data;
                $scope.loading = false;
            });
        };
    }
    ]);
</script>
