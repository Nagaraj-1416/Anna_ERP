<script>
    app.filter('getClassName', function() {
        return function(stock) {
            if (stock.min_level !== '-' && parseInt(stock.min_level) > parseInt(stock.qty)) {
                return 'table-danger';
            }
            return stock.class;
        };
    });
    app.controller('StockHistoryController', ['$scope', '$http', function ($scope, $http) {
        var url = '{{ route('stock.history.data') }}';
        $scope.stokes = [];
        $scope.loading = false;

        $scope.loadData = function () {
            $scope.loading = true;
            $scope.edit = true;
            $http.get(url + '?ajax=true').then(function (response) {
                $scope.stokes = response.data;
                $scope.loading = true;
            });
        };
        $scope.loadData();
    }
    ]).directive('stockLoop', function () {
        return function (scope, element, attrs) {
            if (scope.$last) {
                $('.stock-preloader').addClass('hidden');
            }
        }
    });
</script>
