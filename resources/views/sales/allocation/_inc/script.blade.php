<script src="{{ asset('js/vendor/form.js') }}"></script>
<script src="{{ asset('js/vendor/object-helper.js') }}"></script>
<script>
    app.controller('AllocationController', ['$scope', '$http', function ($scope, $http) {
        // Today Var
        $scope.today = '{{ carbon()->toDateString() }}';
        $scope.old = false;
        //Scope Query Var
        $scope.query = {
            fromDate: $scope.today,
            toDate: $scope.today,
            dateType: 'Single',
            locationType: 'Van',
            customers: {},
            products: {},
            productsChecked: {},
            allowance: null,
            odo_meter_reading: null,
            customersChecked: {}
        };
        $scope.cFProducts = {
            salesLocation: null,
            rep: null,
            route: null,
            allocation: null
        };
        @if(old('_token'));
        $scope.oldData = @json(old());
        $scope.errors = @json($errors->toArray());
        @endif;

        @if(!old('_token') && isset($allocation));
        $scope.oldData = @json($allocation);
        @endif;
        $scope.productStore = {};
        $scope.drivers = [];
        var getDriversRoute = '{{ route('sales.allocation.get.drivers', ['fromDate' => 'FROM', 'toDate' => 'TO']) }}';
        var getLaboursRoute = '{{ route('sales.allocation.get.labours', ['fromDate' => 'FROM', 'toDate' => 'TO']) }}';
        $scope.driverDD = $('.driver-drop-down');
        $scope.labourDD = $('.labour-drop-down');
        if ($scope.oldData && $scope.oldData.driver) {
            $scope.driverDD.dropdown('set value', $scope.oldData.driver.id).dropdown('set text', $scope.oldData.driver.short_name);
        }
        if ($scope.oldData && _.toArray($scope.oldData.labours).length) {
            $.each($scope.oldData.labours, function (k, v) {
                $scope.template = '<a class="ui label transition visible" data-value="' + v.id + '" style="display: inline-block !important;">' +
                    v.short_name +
                    '<i class="delete icon"></i>' +
                    '</a>';
                $($scope.template).insertAfter($scope.labourDD.find('.dropdown.icon'))
            });
            $scope.labourDD.dropdown('set value', $scope.oldData.labour_id)
        }

        $scope.getDrivers = function () {
            $scope.driverDD.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: getDriversRoute.replace('FROM', $scope.query.fromDate).replace('TO', $scope.query.toDate) + '/{query}',
                    cache: false
                }
            });

            $scope.labourDD.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: getLaboursRoute.replace('FROM', $scope.query.fromDate).replace('TO', $scope.query.toDate) + '/{query}',
                    cache: false
                }
            });
            $scope.initLocationDropDown();
            $scope.getRepDD();
            $scope.routeDD();
        };
        //Sales Location DropDown
        $scope.allocationFormEl = {
            dropDown: $('.sales-drop-down'),
        };

        $scope.urls = {
            store: '{{ route('setting.store.search') }}',
            location: '{{ route('sales.allocation.get.sales.location', ['fromDate' => 'FROM', 'toDate' => 'TO', 'type' => 'TYPE']) }}',
            rep: '{{ route('sales.allocation.get.rep', ['fromDate' => 'FROM', 'toDate' => 'TO']) }}',
            route: '{{ route('sales.allocation.get.route', ['fromDate' => 'FROM', 'toDate' => 'TO']) }}',
        };

        $scope.initLocationDropDown = function () {
            $scope.allocationFormEl.dropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: $scope.urls.location.replace('FROM', $scope.query.fromDate).replace('TO', $scope.query.toDate).replace('TYPE', $scope.query.locationType) + '/{query}',
                    cache: false
                },
                onChange: $scope.handleLocationChange
            });
        };
        $scope.getVehicleRoute = '{{ route('sales.allocation.get.vehicle', ['ID']) }}';
        $scope.handleLocationChange = function (val) {

            if($scope.query.locationType === 'Shop'){
                $allocationFormEl.vanProductsPanel.hide();
                $allocationFormEl.shopProductsPanel.show();
            }

            $scope.cFProducts.salesLocation = val;
            if ($scope.query.locationType === 'Shop') {
                var route = '{{ route('sales.allocation.products', ['location' => 'ID']) }}';
                $scope.getProduct(route.replace('ID', val));
            }
            $scope.getCarryForwardProducts();
            $http.get($scope.getVehicleRoute.replace('ID', val)).then(function (response) {
                if (!$scope.old) {
                    $('#odo_meter_reading').val(response.data.ends_at)
                }
                $scope.old = false;
            })
        };
        $scope.initLocationDropDown();
        //Check Location type And related things
        $scope.ProductData = [];
        $scope.products = [];
        $scope.checkLocationType = function () {
            if ($scope.query.locationType === 'Van') {
                $scope.locations = _.where($scope.locations, {type: 'Sales Van'});
            } else {
                $scope.locations = _.where($scope.locations, {type: 'Shop'});
            }
            $scope.initLocationDropDown();
            if (!$scope.$$phase) $scope.$apply();
        };

        //Get All Sales Locations
        $scope.locations = @json(locationWithTypeDropDown());
        // call Check location type
        $scope.checkLocationType();

        //Sales Location Change function
        $scope.handleLocationTypeChange = function (val) {
            $scope.query.locationType = val;
            $scope.locations = @json(locationWithTypeDropDown());
            $scope.allocationFormEl.dropDown.dropdown('clear');
            $scope.checkLocationType();
        };

        //$allocationForm Elements
        var $allocationFormEl = {
            dayType: $('.day-type'),
            fromDate: $('.from-date'),
            toDate: $('.to-date'),
            locationType: $('.sales-location'),
            vanDetailsPanel: $('.van-details-panel'),
            dropDown: $('.rep-drop-down'),
            routeDropDown: $('.route-drop-down'),
            vanProductsPanel: $('.van-products'),
            shopProductsPanel: $('.shop-products')
        };

        $allocationFormEl.shopProductsPanel.hide();

        $scope.ProductData = [];
        $scope.getCarryForwardProducts = function () {
            $scope.ProductData = [];
            $scope.cFRoute = '{{ route('sales.allocation.last.products') }}';
            $http.get($scope.cFRoute + '?' + $.param($scope.cFProducts)).then(function (response) {
                $scope.ProductData = response.data;
                if (!$scope.$$phase) $scope.$apply();
            });
        };

        $scope.oldCustomers = [];
        $scope.getOldCustomers = function () {
            var getOldCustomerRoute = '{{ route('sales.allocation.get.old.customers', ['rep' => 'REP_ID', 'route' => 'ROUTE_ID']) }}';
            if ($scope.cFProducts.route && $scope.cFProducts.rep) {
                $http.get(getOldCustomerRoute.replace('REP_ID', $scope.cFProducts.rep).replace('ROUTE_ID', $scope.cFProducts.route)).then(function (response) {
                    $scope.oldCustomers = response.data;
                })
            }
        };

        //Drop down values
        $scope.getRepDD = function () {
            $allocationFormEl.dropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: $scope.urls.rep.replace('FROM', $scope.query.fromDate).replace('TO', $scope.query.toDate) + '/{query}',
                    cache: false
                },
                onChange: function (val) {
                    $scope.cFProducts.rep = val;
                    $scope.getCarryForwardProducts();
                    $scope.getOldCustomers();
                }
            });
        };
        $scope.getRepDD();


        //Route DropDown change handle
        $scope.oldData = [];
        $scope.errors = [];
        var getCustomerByRoute = '{{ route('sales.get.customer.by.route', ['route' => 'ROUTE']) }}';
        var getProductsByRoute = '{{ route('sales.get.products.by.route', ['route' => 'ROUTE']) }}';
        $scope.customers = [];
        $scope.products = [];
        $scope.handleRoutChange = function (val) {
            $scope.cFProducts.route = val;
            $scope.getCarryForwardProducts();
            $scope.query.productsChecked = {};
            $http.get(getCustomerByRoute.replace('ROUTE', val)).then(function (response) {
                $scope.customers = response.data;
            });
            route = getProductsByRoute.replace('ROUTE', val);
            $scope.getProduct(route);
            $scope.getOldCustomers();
            var getRouteAllowance = '{{ route('setting.route.get.allowance', ['route' => 'ROUTE']) }}';
            $http.get(getRouteAllowance.replace('ROUTE', val)).then(function (response) {
                $scope.allowance = response.data;
                if ($scope.oldData.allowance && !$scope.allowance.amount) {
                    $scope.query.allowance = $scope.oldData.allowance;
                } else {
                    $scope.query.allowance = $scope.allowance.amount;
                }
            });
        };

        // Get Products From given Route
        $scope.getProduct = function (route) {
            $http.get(route).then(function (response) {
                $scope.products = _.toArray(response.data);
            })
        };
        // $scope.handleRoutChange(2);
        //Set values for drop downs
        $scope.setDropDownValue = function (dd, value, text) {
            dd.dropdown('set value', value).dropdown('set text', text);
        };

        $scope.bindData = function (data) {
            $scope.cFProducts.allocation = data.id;
            $scope.query.locationType = data.sales_location;
            if (typeof data.sales_location === 'object') {
                if (data.sales_location.type === 'Sales Van') {
                    $scope.query.locationType = 'Van';
                } else {
                    $scope.query.locationType = 'Shop';
                }
            }
            $scope.checkLocationType();
            if (data.sales_location_id) {
                $scope.setDropDownValue($scope.allocationFormEl.dropDown, data.sales_location_id, data.location_name);
                var productRoute = '{{ route('sales.allocation.products', ['location' => 'ID']) }}';
                $scope.getProduct(productRoute.replace('ID', data.sales_location_id));
            }
            if (data.rep_id) {
                $scope.setDropDownValue($allocationFormEl.dropDown, data.rep_id, data.rep_name);
            }

            if (data.route_id) {
                $scope.setDropDownValue($allocationFormEl.routeDropDown, data.route_id, data.route_name);
            }
            // $scope.getCarryForwardProducts();
            $scope.cFProducts.salesLocation = $scope.allocationFormEl.dropDown.dropdown('get value');
            $scope.cFProducts.rep = $allocationFormEl.dropDown.dropdown('get value');
            if (!$scope.$$phase) $scope.$apply();
            var route = $allocationFormEl.routeDropDown.dropdown('get value');
            if (route) {
                $scope.handleRoutChange(route);
            }
            $scope.query.fromDate = data.from_date;
            $scope.query.toDate = data.to_date;
            $scope.query.allowance = data.allowance;

        };
        //Old Data Functions
        @if(old('_token'))
            $scope.oldData = @json(old());
            $scope.errors = @json($errors->toArray());
            $scope.bindData($scope.oldData);
            $scope.old = true;
        @endif

        @if(!old('_token') && isset($allocation))
            $scope.oldData = @json($allocation);
            $scope.bindData($scope.oldData);
        @endif

        if($scope.query.dateType === 'Single'){
            $allocationFormEl.fromDate.attr('readonly', true);
            $allocationFormEl.toDate.attr('readonly', true);
        }else{
            $allocationFormEl.fromDate.attr('readonly', false);
            $allocationFormEl.toDate.attr('readonly', false);
        }

        //Date DD change function
        $scope.handleDateTypeChange = function (val) {
            $scope.query.dateType = val;
            if (val === 'Single') {
                $allocationFormEl.fromDate.attr('readonly', true);
                $allocationFormEl.toDate.attr('readonly', true);
                $scope.query.fromDate = $scope.today;
                $scope.query.toDate = $scope.today;
            } else {
                $allocationFormEl.fromDate.attr('readonly', false);
                $allocationFormEl.toDate.attr('readonly', false);
                $scope.query.fromDate = $scope.today;
                $scope.query.toDate = $scope.today;
            }
        };

        $scope.routeDD = function () {
            $allocationFormEl.routeDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: $scope.urls.route.replace('FROM', $scope.query.fromDate).replace('TO', $scope.query.toDate) + '/{query}',
                    cache: false
                },
                onChange: $scope.handleRoutChange
            });
        };

        $scope.getDrivers();
        $scope.getProductsCount = function () {
            return _.toArray($scope.query.productsChecked).length;
        };

        $scope.getCustomersCount = function () {
            return _.toArray($scope.query.customersChecked).length;
        };

        $scope.productCheckBoxChanged = function (id, check) {
            if ($scope.query.productsChecked.hasOwnProperty(id) && !$scope.ProductData.hasOwnProperty(id)) {
                delete $scope.query.productsChecked[id];
                if ($scope.oldData.hasOwnProperty('product') && $scope.oldData.product.hasOwnProperty('id') && $scope.oldData.product['id'].hasOwnProperty(id)) {
                    delete $scope.oldData.product['id'][id];
                }
            } else {
                if (!check) {
                    $scope.query.productsChecked[id] = id;
                }
            }
            if (!$scope.$$phase) $scope.$apply();
        };

        $scope.customerCheckBoxChanged = function (id, check) {
            if ($scope.query.customersChecked.hasOwnProperty(id)) {
                delete $scope.query.customersChecked[id];
                if ($scope.oldData.hasOwnProperty('customer') && $scope.oldData.customer.hasOwnProperty('id') && $scope.oldData.customer['id'].hasOwnProperty(id)) {
                    delete $scope.oldData.customer['id'][id];
                }
            } else {
                if (!check) {
                    $scope.query.customersChecked[id] = id;
                }
            }
            if (!$scope.$$phase) $scope.$apply();
        };

        $scope.initStoreDropDown = function (el) {
            el.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: $scope.urls.store + '/{query}',
                    cache: false
                },
                onChange: function (val, name, elem) {
                    if (elem) {
                        $scope.productStore[elem.parent().parent().data('index')] = {
                            name: name,
                            val: val
                        };
                    }
                }
            });
            el.dropdown('set text', '{{ env('DEFAULT_STORE_NAME') }}').dropdown('set value', '{{ env('DEFAULT_STORE_ID') }}')
        };

        $scope.isChecked = function (object, index) {
            return object.hasOwnProperty(index);
        };


        $scope.hasError = function (name) {
            return $scope.errors.hasOwnProperty(name);
        };

        $scope.getError = function (name) {
            if ($scope.errors.hasOwnProperty(name)) {
                return $scope.errors[name][0];
            }
        };

        $scope.handleCustomerCheckAll = function ($event) {
            var check = false;
            if ($($event.target).is(':checked')) {
                $('.customer-check').prop('checked', true);
                $scope.query.customersChecked = {};
            } else {
                $('.customer-check').prop('checked', false);
                check = true;
            }
            $.each($('.customer-check'), function (key, value) {
                $scope.customerCheckBoxChanged($(value).data('customer'), check)
            });
        };

        $scope.handleProductCheckAll = function ($event) {
            var check = false;
            var productCheck = $('.product-check');
            $.each(productCheck, function (key, value) {
                var productId = $(value).data('product');
                if ($scope.ProductData.hasOwnProperty(productId)) {
                    $(value).prop('checked', true);
                } else {
                    if ($($event.target).is(':checked')) {
                        $(value).prop('checked', true);
                    } else {
                        $(value).prop('checked', false);
                        check = true;
                    }
                    $scope.productCheckBoxChanged($(value).data('product'), check);
                }
            });
            $scope.initStoreDropDown($('.store-drop-down'));
        };

        $scope.updateDefaultQty = function (product, once) {
            if (once) {
                if (!product.default_qty) product.default_qty = product.pivot.default_qty;
            } else {
                product.default_qty = product.pivot.default_qty;
            }
            if (!$scope.$$phase) $scope.$apply();
        };

        $scope.getCfQty = function (product) {
            if ($scope.ProductData.hasOwnProperty(product)) {
                return $scope.ProductData[product];
            }
            return 0;
        };

        $('.drop-down').dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
        });

        $('.store-drop-down').dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
        });

        $('#allocationForm').submit(function () {
            $('#endTimeInput').val(moment())
        })

    }]).directive('productDirective', function () {
        return function (scope, element, attrs) {
            scope.product.default_quantity = 0;
            scope.product.pivot.default_qty = 0;
            if (scope.ProductData.hasOwnProperty(scope.product.id)) {
                scope.product.cf_qty = scope.ProductData[scope.product.id];
                element.find('.product-check').prop('checked', true);
                element.find('.product-check').attr('readonly', true).attr('disabled', true);
                element.find('.support-input').attr('disabled', false);
                scope.productCheckBoxChanged(scope.product.id);
                if (scope.oldData.hasOwnProperty('product') && scope.oldData.product.quantity.hasOwnProperty(scope.product.id)) {
                    scope.product.pivot.default_qty = scope.oldData.product.quantity[scope.product.id];
                    scope.product.default_qty = scope.product.pivot.default_qty;
                } else {
                    if (scope.product.default_qty) {
                        scope.product.pivot.default_qty = scope.product.default_qty;
                    } else {
                        var totalQty = scope.product.pivot.default_qty - scope.product.cf_qty;
                        if (totalQty < 0) totalQty = 0;
                        scope.product.pivot.default_qty = totalQty;
                    }

                }
            } else {
                scope.product.cf_qty = 0;
            }

            scope.initStoreDropDown(element.find('.store-drop-down'));
            if (scope.query.productsChecked.hasOwnProperty(scope.product.id)) {
                element.find('.product-check').prop('checked', true);
            }
            if (!scope.query.productsChecked.hasOwnProperty(scope.product.id) && scope.oldData.hasOwnProperty('product') && scope.oldData.product.hasOwnProperty('id') && scope.oldData.product['id'].hasOwnProperty(scope.product.id)) {
                element.find('.product-check').prop('checked', true);
                if (scope.oldData.hasOwnProperty('product') && scope.oldData.product.quantity.hasOwnProperty(scope.product.id)) {
                    scope.product.pivot.default_qty = scope.oldData.product.quantity[scope.product.id];
                    scope.product.default_qty = scope.oldData.product.quantity[scope.product.id];
                }
                scope.productCheckBoxChanged(scope.product.id);
                if (scope.oldData.hasOwnProperty('store_id') && scope.oldData.hasOwnProperty('store_name')) {
                    scope.setDropDownValue(element.find('.store-drop-down'), scope.oldData.store_id[scope.product.id], scope.oldData.store_name[scope.product.id]);
                }
                element.find('.quantity-text').val(scope.oldData.product.quantity[scope.product.id]);
            }
            if (scope.productStore.hasOwnProperty(scope.product.id)) {
                scope.setDropDownValue(element.find('.store-drop-down'), parseInt(scope.productStore[scope.product.id].val), scope.productStore[scope.product.id].name);
            }
            scope.updateDefaultQty(scope.product, true);

            $('#product-section').mCustomScrollbar({
                setHeight: false,
                autoHideScrollbar: true,
                axis: 'yx',
                theme: 'minimal-dark',
                advanced: {
                    autoScrollOnFocus: false,
                }
            });
        };
    }).directive('customerDirective', function () {
        return function (scope, element, attrs) {
            if (scope.query.customersChecked.hasOwnProperty(scope.customer.id)) {
                element.find('.customer-check').prop('checked', true);
            }

            if (!scope.query.customersChecked.hasOwnProperty(scope.customer.id) && scope.oldData.hasOwnProperty('customer') && scope.oldData.customer.hasOwnProperty('id') && scope.oldData.customer['id'].hasOwnProperty(scope.customer.id)) {
                element.find('.customer-check').prop('checked', true);
                scope.customerCheckBoxChanged(scope.customer.id);
            }
            $('#customer-section').mCustomScrollbar({
                setHeight: false,
                autoHideScrollbar: true,
                axis: 'yx',
                theme: 'minimal-dark',
                advanced: {
                    autoScrollOnFocus: true,
                },
            });
        };
    });
</script>
