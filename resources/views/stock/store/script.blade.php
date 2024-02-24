<script>
    app.controller('StoreStockController', ['$scope', '$http', function ($scope, $http) {
        var url = '{{ route('stock.store.index') }}';
        $scope.main = [];
        $scope.stocks = [];
        $scope.loading = false;
        $scope.searchProducts = '';
        $scope.store = null;

        $scope.param = {
            storeId: '',
            query: ''
        };
        $scope.searching = false;
        $scope.el = {
            loader: $('.cus-create-preloader')
        };

        $('.store-drop-down').dropdown({
            forceSelection: false,
            saveRemoteData: false,
            onChange: function (val) {
                $scope.param.storeId = val;
                $scope.generate();
            }
        });

        $scope.searchingObject = [];
        $scope.edit = false;
        $scope.generate = function (storeId) {
            $scope.loading = true;
            $scope.edit = true;
            $http.get(url + '?' + $.param($scope.param)).then(function (response) {
                $scope.main = response.data;
                $scope.store = response.data.store;
                $scope.stocks = $scope.main.stocks;
                $scope.noStocks = $scope.main.noStocks;
                $scope.loading = false;
                $scope.edit = false;
                $scope.searching = false;
                if (storeId) {
                    $scope.searchingObject[storeId] = false;
                }
                $scope.initScroll();
            });
        };

        // $scope.el.loader.addClass('hidden');
        $scope.showLoader = function () {
            $scope.el.loader.addClass('loading');
            $scope.el.loader.removeClass('hidden');
        };

        $scope.hideLoader = function () {
            $scope.el.loader.removeClass('loading');
            $scope.el.loader.addClass('hidden');
        };

        $scope.searchProduct = _.debounce(function () {
            $scope.generate();
        }, 500);

        $scope.initScroll = function () {
            $('.cardScroll').slimScroll({
                height: '500px'
            });
        }
    }
    ]).directive('cardDirective', function () {
        return function (scope, element, attrs) {
            scope.searchingObject[scope.store.id] = false;
            $('.cardScroll').slimScroll({
                height: '500px'
            });
        };
    });
</script>