<script src="{{ asset('js/vendor/form.js') }}"></script>
<script src="{{ asset('js/vendor/object-helper.js') }}"></script>
<script>
    app.controller('EstimateController', function ($scope, $timeout, $http) {
        $scope.totalAdjustment = 0.00;
        $scope.totalDiscount = 0.00;
        $scope.total = 0.00;

        $scope.estimate = {
            product_items: []
        };

        $scope.productModel = {
            product_id: null,
            product_name: null,
            store_id: null,
            store_name: null,
            quantity: 0,
            rate: 0,
            discount_rate: 0,
            amount: 0,
            notes: null,
            discount_type: "Amount"
        };

        /** form elements */
        $scope.el = {
            btDropDown: $('.bt-drop-down'),
            repDropDown: $('.rep-drop-down'),
            cusDropDown: $('.cus-drop-down'),
            discountRateInput: $('.discount-input'),
            adjustmentInput: $('.adjustment-input'),
            subTotalInput: $('.sub-total-input'),
            totalInput: $('.total-input'),
            totalDiscountTypeDropDown: $('.discount-type'),
            productDropDown: $('.product-drop-down'),
            storeDropDown: $('.store-drop-down'),
            scheduleDatePicker: $('.schedule-date'),
            itemBtnContainer: $('.item-btn-container'),
            itemTemplate: $('#item-template')
        };

        $scope.isRemoveable = false;

        /** require urls */
        $scope.urls = {
            bt: '{{ route('setting.business.type.search') }}',
            rep: '{{ route('setting.rep.search') }}',
            cus: '{{ route('sales.customer.search') }}',
            product: '{{ route('setting.sales.product.search') }}',
            store: '{{ route('setting.store.search') }}'
        };

        /** set request old data */
        @if (isset($inquiry) && $inquiry)
            $scope.estimate = @json($inquiry);
        @endif

        /** set request old data */
        @if (isset($estimate) && $estimate)
            $scope.estimate = @json($estimate);
        @endif

        /** mapping errors data */
        $scope.mapError = function (errors) {
            var MappedErrors = {};
            $.map(errors, function (values, field) {
                var filedData = field.split(".");
                if (filedData.hasOwnProperty('0') && filedData.hasOwnProperty('1') && values.hasOwnProperty('0')) {
                    if (!MappedErrors.hasOwnProperty(filedData[0])) {
                        MappedErrors[filedData[0]] = [];
                    }
                    MappedErrors[filedData[0]][filedData[1]] = values[0].replace(/_/g, ' ').replace('.' + filedData[1], '').replace('item', '');
                }
            });
            return MappedErrors;
        };

        /** mapping product items */
        $scope.mapProductItems = function(){
            $.each($scope.estimate.product, function (k, v) {
                if (!$scope.estimate.hasOwnProperty('product_items')) {
                    $scope.estimate.product_items = [];
                }

                var item = {
                    product_id: null,
                    product_name: null,
                    store_id: null,
                    store_name: null,
                    quantity: null,
                    rate: null,
                    discount_rate: null,
                    notes: null,
                    discount_type: null,
                    amount: null
                };

                if ($scope.estimate.hasOwnProperty('product') && $scope.estimate.product.hasOwnProperty(k)){
                    item.product_id = $scope.estimate.product[k];
                }
                if ($scope.estimate.hasOwnProperty('product_name') && $scope.estimate.product_name.hasOwnProperty(k)){
                    item.product_name = $scope.estimate.product_name[k];
                }
                if ($scope.estimate.hasOwnProperty('store') && $scope.estimate.store.hasOwnProperty(k)){
                    item.store_id = $scope.estimate.store[k];
                }
                if ($scope.estimate.hasOwnProperty('store_name') && $scope.estimate.store_name.hasOwnProperty(k)){
                    item.store_name = $scope.estimate.store_name[k];
                }
                if ($scope.estimate.hasOwnProperty('quantity') && $scope.estimate.quantity.hasOwnProperty(k)){
                    item.quantity = $scope.estimate.quantity[k];
                }
                if ($scope.estimate.hasOwnProperty('rate') && $scope.estimate.rate.hasOwnProperty(k)){
                    item.rate = $scope.estimate.rate[k];
                }
                if ($scope.estimate.hasOwnProperty('item_discount_rate') && $scope.estimate.item_discount_rate.hasOwnProperty(k)){
                    item.discount_rate = $scope.estimate.item_discount_rate[k];
                }
                if ($scope.estimate.hasOwnProperty('product_notes') && $scope.estimate.product_notes.hasOwnProperty(k)){
                    item.notes = $scope.estimate.product_notes[k];
                }
                if ($scope.estimate.hasOwnProperty('item_discount_type') && $scope.estimate.item_discount_type.hasOwnProperty(k)){
                    item.discount_type = $scope.estimate.item_discount_type[k];
                }
                if ($scope.estimate.hasOwnProperty('amount') && $scope.estimate.amount.hasOwnProperty(k)){
                    item.amount = $scope.estimate.amount[k];
                }
                $scope.estimate.product_items.push(item);
            })
        };

        /** set form old values */
        @if (old('_token'))
            $scope.estimate = @json(old());
            $scope.mapProductItems();
        @endif

        /** map validation messages to form errors variable */
        $scope.errors = {};
        $scope.mappedErrors = {};
        @if (isset($errors))
            $scope.errors = @json($errors->toArray());
            $scope.mappedErrors = $scope.mapError($scope.errors);
        @endif

        $scope.hasError = function(name, index){
            if ($scope.mappedErrors.hasOwnProperty(name)) {
                if ($scope.mappedErrors[name].hasOwnProperty(index)) {
                    return $scope.mappedErrors[name][index];
                }
            }
            return false;
        };

        /** add product item */
        $scope.addItem = function () {
            $scope.estimate.product_items.push(angular.copy($scope.productModel));
            $scope.isRemoveable = $scope.estimate.product_items.length > 0;
        };

        /** check if items exist if not insert a new item */
        if (!$scope.estimate.hasOwnProperty('product_items') || ($scope.estimate.hasOwnProperty('product_items') && !$scope.estimate.product_items.length)) {
            $scope.addItem();
        }

        /** remove product item */
        $scope.removeItem = function (index) {
            $scope.estimate.product_items = $scope.removeByKey($scope.estimate.product_items, index);
            $scope.isRemoveable = $scope.estimate.product_items.length > 0;
        };

        /** remove item from object by key */
        $scope.removeByKey = function (array, index) {
            if (array.hasOwnProperty(index)) {
                array.splice(index, 1);
            }
            return array;
        };

        $scope.changedValue = function (index) {
            if ($scope.estimate.product_items && $scope.estimate.product_items.hasOwnProperty(index)) {
                if (!$scope.estimate.product_items[index].hasOwnProperty('discount_rate')) {
                    $scope.estimate.product_items[index]['discount_rate'] = 0;
                }
                var product = $scope.estimate.product_items[index];
                var quantity = chief_double(product.quantity);
                var rate = chief_double(product.rate);
                var netAmount = quantity * rate;
                var discountRate = chief_double(product.discount_rate);
                var discountType = product.discount_type;
                var discount = discountRate;
                if (discountType === 'Percentage') {
                    discount = netAmount * (discountRate / 100);
                }
                discount = chief_double(discount);
                netAmount = chief_double(netAmount);
                var amount = netAmount - discount;
                if (!$scope.estimate.product_items[index].hasOwnProperty('amount')) {
                    $scope.estimate.product_items[index]['amount'] = 0;
                }
                $scope.estimate.product_items[index].amount = amount;
                if ($scope.estimate.product_items[index].hasOwnProperty('discount')) {
                    $scope.estimate.product_items[index]['discount'] = 0;
                }
                $scope.estimate.product_items[index].discount = discount;
            }
            $scope.calculateTotal();
        };

        /** calculate estimate total */
        $scope.calculateTotal = function () {
            $scope.subTotal = sum($scope.estimate.product_items, 'amount');
            $scope.subTotal = chief_double($scope.subTotal);
            var discount = chief_double($scope.totalDiscount);
            var totalAdjustment = chief_double($scope.totalAdjustment);
            if ($scope.totalDiscountType  === 'Percentage') {
                discount = $scope.subTotal * (discount / 100);
            }
            $scope.total = ($scope.subTotal - discount) + totalAdjustment;
            $scope.total = chief_double($scope.total);
        };

        function roundToTwo(num) {
            return +(Math.round(num + "e+2")  + "e-2");
        }

        /** check double value */
        function chief_double(num) {
            var n = roundToTwo(parseFloat(num));
            if (isNaN(n)) {
                return 0.00;
            }
            else {
                return roundToTwo(parseFloat(num));
            }
        }

        /** sum the key values in object */
        function sum(object, key) {
            return _.reduce(object, function (memo, item) {
                if (item.hasOwnProperty(key)) {
                    var value = chief_double(item[key]);
                    return memo + value;
                }
                return memo;
            }, 0)
        }

        /** set main form drop-down values */
        $scope.setMainFormValues = function () {
            if ($scope.estimate.hasOwnProperty('discount_rate') && $scope.estimate.hasOwnProperty('discount_type')){
                var label = $scope.estimate.discount_type === 'Percentage' ? '%' : 'LKR';
                $scope.setDropDownValue($scope.el.totalDiscountTypeDropDown, $scope.estimate.discount_type, label);
            }

            if ($scope.estimate.hasOwnProperty('business_type_id') && $scope.estimate.hasOwnProperty('business_type_name')) {
                $scope.setDropDownValue($scope.el.btDropDown, $scope.estimate.business_type_id, $scope.estimate.business_type_name);
            }

            if ($scope.estimate.hasOwnProperty('rep_id') && $scope.estimate.hasOwnProperty('rep_name')) {
                $scope.setDropDownValue($scope.el.repDropDown, $scope.estimate.rep_id, $scope.estimate.rep_name);
            }

            if ($scope.estimate.hasOwnProperty('customer_id') && $scope.estimate.hasOwnProperty('customer_name')) {
                $scope.setDropDownValue($scope.el.cusDropDown, $scope.estimate.customer_id, $scope.estimate.customer_name);
            }

            if ($scope.estimate.hasOwnProperty('adjustment')){
                $scope.totalAdjustment = $scope.estimate.adjustment;
            }

            if ($scope.estimate.hasOwnProperty('sub_total')){
                $scope.subTotal = $scope.estimate.sub_total;
            }

            if ($scope.estimate.hasOwnProperty('discount')){
                $scope.totalDiscount = $scope.estimate.discount_rate;
            }

            if ($scope.estimate.hasOwnProperty('total')){
                $scope.total = $scope.estimate.total;
            }
        };

        /** set values to semantic UI drop-down */
        $scope.setDropDownValue = function (dd, value, name) {
            dd.dropdown("refresh");
            dd.dropdown('set value', value);
            dd.dropdown('set text', name);
        };

        $scope.setMainFormValues();

        /** init business type drop-down */
        $scope.el.btDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            apiSettings: {
                url: $scope.urls.bt + '/{query}',
                cache: false
            }
        });

        /** init sales rep drop-down */
        $scope.el.repDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            apiSettings: {
                url: $scope.urls.rep + '/{query}',
                cache: false
            }
        });

        /** grand total discount Type drop down */
        $scope.el.totalDiscountTypeDropDown.dropdown('setting', {
            forceSelection: false,
            onChange: function (value, name, el) {
                $scope.totalDiscountType = value;
                $timeout(function () {
                    $scope.calculateTotal();
                }, 10)
            }
        });

        /** init customer drop-down */
        $scope.el.cusDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            apiSettings: {
                url: $scope.urls.cus + '/{query}',
                cache: false
            }
        });

        /** init discount drop-down */
        $scope.initItemDiscountTypeDropDown = function (el) {
            el.dropdown('setting', {
                forceSelection: false,
                onChange: function (value, name, el) {
                    var index = $(this).data('index').toString();
                    if ($scope.estimate.product_items.hasOwnProperty(index) && $scope.estimate.product_items[index].hasOwnProperty('discount_type')) {
                        $scope.estimate.product_items[index]['item_discount_type'] = value;
                    }
                    $timeout(function () {
                        $scope.changedValue(index);
                    }, 10)
                }
            });
        };

        /** Get product remote data */
        $scope.getProduct = function (id, callback) {
            var url = '{{ route('setting.product.show', 'ID') }}'.replace('ID', id);
            $http.get(url).then(function (response) {
                if (typeof callback === 'function') {
                    callback(response.data)
                }
            });
        };

        $scope.productChanged = function(id, index){
            $scope.getProduct(id, function (response) {
                if ($scope.estimate.product_items.hasOwnProperty(index)) {
                    $scope.estimate.product_items[index]['rate'] = response.wholesale_price;
                    $scope.changedValue(index);
                }
            })
        };

        /** init product drop down */
        $scope.initProductDropDown = function (el) {
            el.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: $scope.urls.product + '/{query}',
                    cache: false
                },
                onChange: function (value, name, el) {
                    var index = $(this).data('index').toString();
                    if ($scope.estimate.product_items.hasOwnProperty(index)) {
                        if ($scope.estimate.product_items[index].hasOwnProperty('product_id')) {
                            $scope.estimate.product_items[index]['product_id'] = value;
                        }
                        if ($scope.estimate.product_items[index].hasOwnProperty('product_name')) {
                            $scope.estimate.product_items[index]['product_name'] = name;
                        }
                        $scope.productChanged(value, index)
                    }
                }
            });
        };

        /** init store drop down */
        $scope.initStoreDropDown = function (el) {
            el.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: $scope.urls.store + '/{query}',
                    cache: false
                },
                onChange: function (value, name, el) {
                    var index = $(this).data('index').toString();
                    if ($scope.estimate.product_items.hasOwnProperty(index)) {
                        if ($scope.estimate.product_items[index].hasOwnProperty('store_id')) {
                            $scope.estimate.product_items[index]['store_id'] = value;
                        }
                        if ($scope.estimate.product_items[index].hasOwnProperty('store_name')) {
                            $scope.estimate.product_items[index]['store_name'] = name;
                        }
                    }
                }
            });
        };

        /** product items drop-down values */
        $scope.setProductItemsDropDownValues = function () {
            $.each($scope.estimate.product_items, function (index, product) {
                if (product.product_id && product.product_name){
                    $scope.setDropDownValue($('.product-drop-down[data-index="' + index + '"]'), product.product_id, product.product_name);
                    $scope.setDropDownValue($('.store-drop-down[data-index="' + index + '"]'), product.store_id, product.store_name);
                }
                if (product.discount_type){
                    var label = product.discount_type === 'Percentage' ? '%' : 'LKR';
                    $scope.setDropDownValue($('.item-discount-type[data-index="' + index + '"]'), product.discount_type, label);
                }
            })
        }
    }).directive('productLoop', function () {
        return function (scope, element, attrs) {
            if (scope.$last) {
                scope.initProductDropDown($('.product-drop-down'));
                scope.initStoreDropDown($('.store-drop-down'));
                scope.initItemDiscountTypeDropDown($('.item-discount-type'));
                setTimeout(scope.setProductItemsDropDownValues, 500);
            }
        }
    });
</script>