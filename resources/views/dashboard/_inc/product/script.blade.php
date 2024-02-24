<script src="{{ asset('js/vendor/slidereveal.js') }}"></script>
<script src="{{ asset('js/vendor/object-helper.js') }}"></script>
<script>
    app.controller('AddProductController', function ($scope, $http) {
        $scope.addProduct = {
            product: null
        };
        $scope.products = [];
        $scope.productIds = [];
        $scope.errors = [];

        $scope.el = {
            btn: $('.products-sidebar-btn'),
            sidebar: $('#products-sidebar'),
            loader: $('.cus-create-preloader'),
            productDD: $('.all-products-dropdown'),
            storeDD: $('.store-drop-down')
        };

        var allocation = $scope.el.btn.data('value');

        if (allocation){

            /** set URLs */
            var allocationProductsUrl = '{{ route('sales.allocation.get.products', 'ALLOCATION') }}';
            allocationProductsUrl = allocationProductsUrl.replace('ALLOCATION', allocation.id);

            var addProductsUrl = '{{ route('sales.allocation.add.product', 'ALLOCATION') }}';
            addProductsUrl = addProductsUrl.replace('ALLOCATION', allocation.id);

            $scope.handleProductDDChange = function (val) {
                var productRoute = '{{ route('sales.allocation.product.get', ['ALLOCATION', 'ID']) }}';

                productRoute = productRoute.replace('ALLOCATION', allocation.id);

                if (!val) return;
                $http.get(productRoute.replace('ID', val)).then(function (response) {
                    var product = response.data;
                    if (_.toArray(product).length) {
                        var sampleProduct = {
                            default_qty: null,
                            name: null,
                            store_id: null,
                            product_id: null
                        };
                        var old = _.find($scope.products, function (k, v) {
                            return k.product_id === product.id
                        });

                        if (!old) {
                            sampleProduct.name = product.name;
                            sampleProduct.product_id = product.id;
                            sampleProduct.default_qty = product.pivot ? product.pivot.default_qty : 0;
                            $scope.products.push(sampleProduct);
                        }
                    }
                    $scope.el.productDD.dropdown('clear');
                    if (!$scope.$$phase) $scope.$apply()
                });
            };
            $scope.el.productDD.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: allocationProductsUrl + '/{query}',
                    cache: false
                },
                onChange: $scope.handleProductDDChange
            });

            $scope.cusSlider = $scope.el.sidebar.slideReveal({
                trigger: $scope.el.btn,
                position: "right",
                width: '800px',
                push: false,
                overlay: true,
                shown: function (slider, trigger) {
                    // init scroll for side bar body
                    $('#add-cus-body').slimScroll({
                        color: 'gray',
                        height: '100%',
                        railVisible: true,
                        alwaysVisible: false
                    });
                },
                show: function (slider, trigger) {
                    $scope.hideLoader();
                    $scope.resetForm();
                    if (!$scope.$$phase) $scope.$apply()
                }
            });

            $scope.closeSideBar = function () {
                $scope.cusSlider.slideReveal("toggle");
            };

            $scope.resetForm = function () {
                $scope.addProduct = {
                    product: '',
                };
                $scope.el.productDD.dropdown('clear');
                $scope.errors = [];
                if (!$scope.$$phase) $scope.$apply();
            };

            $scope.hideLoader = function () {
                $scope.el.loader.removeClass('loading');
                $scope.el.loader.addClass('hidden');
            };
            $scope.addProductRoute = addProductsUrl;
            $scope.submitForm = function () {
                $scope.showLoader();
                $http.post($scope.addProductRoute, {'products': $scope.products}).then(function (response) {
                    if (response.data) {
                        swal(
                            'Allocation',
                            'Products allocted successfully!',
                            'success'
                        ).then(function (confirm) {
                            if (confirm) {
                                window.location.reload()
                            }
                        });
                    }
                    $scope.hideLoader();
                    $scope.closeSideBar();
                }).catch(function (error) {
                    if (error.hasOwnProperty('data') && error.data.hasOwnProperty('message') && error.data.message === 'This action is unauthorized.') {
                        $scope.errors = [];
                        $scope.errors['unauthorized'] = true;
                    }
                    if (error.hasOwnProperty('data') && error.data.hasOwnProperty('errors')) {
                        $scope.errors = [];
                        $scope.mapErrors(error.data.errors);
                    }
                    $scope.hideLoader();
                });
            };

            // show loader
            $scope.el.loader.addClass('hidden');
            $scope.showLoader = function () {
                $scope.el.loader.addClass('loading');
                $scope.el.loader.removeClass('hidden');
            };

            $scope.mapErrors = function (errors) {
                $.map(errors, function (values, field) {
                    if (values.hasOwnProperty('0')) {
                        $scope.errors[field] = values[0];
                    }
                });
            };

            // check has error
            $scope.hasError = function (name) {
                if ($scope.errors.hasOwnProperty(name)) {
                    if ($scope.errors[name]) {
                        return true;
                    }
                }
                return false;
            };

            // check has error
            $scope.getErrorMsg = function (name) {
                if ($scope.errors.hasOwnProperty(name)) {
                    return $scope.errors[name];
                }
                return '';
            };

            $scope.removeProduct = function (index) {
                $scope.products = $scope.removeByKey($scope.products, index);
            };
            // Remove item from object by key
            $scope.removeByKey = function (array, index) {
                if (array.hasOwnProperty(index)) {
                    array.splice(index, 1);
                }
                return array;
            };
            $scope.urls = {
                store: '{{ route('setting.store.search') }}',
            };
            $scope.initStoreDropDown = function (el, productId) {
                el.dropdown('setting', {
                    forceSelection: false,
                    saveRemoteData: false,
                    apiSettings: {
                        url: $scope.urls.store + '/{query}',
                        cache: false
                    },
                    onChange: function (val) {
                        var product = Object.keys($scope.products).find(key => $scope.products[key].product_id === productId);
                        $scope.products[product].store_id = parseInt(val);
                    }
                });
                el.dropdown('set text', '{{ env('DEFAULT_STORE_NAME') }}').dropdown('set value', '{{ env('DEFAULT_STORE_ID') }}')
            };

        }

    }).directive('productDirective', function () {
        return function (scope, element, attrs) {
            if(scope.el.btn.data('value')){
                scope.initStoreDropDown($('.store-drop-down'), scope.product.product_id);
            }
        };
    });
</script>