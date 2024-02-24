<script>
    app.controller('ShopStockController', ['$scope', '$http', function ($scope, $http) {
        var url = '{{ route('stock.shop.index') }}';
        $scope.main = [];
        $scope.allocationProducts = [];
        $scope.dailySales = [];
        $scope.salesLocations = [];
        $scope.shops = [];
        $scope.products = [];
        $scope.loading = false;
        $scope.searchProducts = '';
        $scope.shop = null;
        $scope.param = {
            shopId: '',
            query: ''
        };
        $scope.searching = false;
        $scope.el = {
            loader: $('.cus-create-preloader')
        };


        $('.shop-drop-down').dropdown({
            forceSelection: false,
            saveRemoteData: false,
            onChange: function (val) {
                $scope.param.shopId = val;
                $scope.generate();
            }
        });

        $scope.searchingObject = [];
        $scope.edit = false;
        $scope.generate = function (vehicleId) {
            $scope.loading = true;
            $scope.edit = true;
            $http.get(url + '?' + $.param($scope.param)).then(function (response) {
                $scope.main = response.data;
                $scope.shop = response.data.shop;
                $scope.allocationProducts = $scope.main.allocationProducts;
                $scope.dailySales = $scope.main.dailySales;
                $scope.products = $scope.main.products;
                $scope.loading = false;
                $scope.edit = false;
                $scope.searching = false;
                if (vehicleId) {
                    $scope.searchingObject[vehicleId] = false;
                }
                $scope.initScroll();
            });
        };
        
        $scope.getLength = function (Object) {
            return _.toArray(Object).length;
        };

        $scope.getProductData = function (vehicle) {
            if (!vehicle) return {};
            var salesLocations = $scope.getLocation(vehicle.id);
            return $scope.getProducts(salesLocations.id);
        };

        $scope.getLocation = function (vehicleId) {
            return _.find($scope.salesLocations, function (value, key) {
                if (value.vehicle_id === parseInt(vehicleId)) return value;
            });
        };

        $scope.getProducts = function (locationId) {
            return _.find($scope.allocationProducts, function (values, key) {
                if (parseInt(key) === parseInt(locationId)) return values;
            });
        };

        $scope.getProduct = function (id) {
            // return _.find($scope.products, function (value, key) {
            //     console.log(key);
            //     if (value.id === parseInt(id)) return value;
            // });
        };

        $scope.getProductStats = function (products, field) {
            return sum(_.pluck(products, field));
        };

        $scope.getAvailableQty = function (products) {
            var quantity = $scope.getProductStats(products, 'quantity');
            var sold_qty = $scope.getProductStats(products, 'sold_qty');
            var restored_qty = $scope.getProductStats(products, 'restored_qty');
            var replaced_qty = $scope.getProductStats(products, 'replaced_qty');
            return (quantity - (sold_qty + restored_qty + replaced_qty));
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

        $scope.searchable = [];
        $scope.vehiclesSearch = [];

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
            scope.searchingObject[scope.vehicle.id] = false;
            $('.cardScroll').slimScroll({
                height: '500px'
            });
        };
    });
</script>