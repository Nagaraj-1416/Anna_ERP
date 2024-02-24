<script src="{{ asset('js/vendor/form.js') }}"></script>
<script src="{{ asset('js/vendor/object-helper.js') }}"></script>
<script>
    app.controller('SalesOrderController', function ($scope, $timeout, $http) {
        $scope.totalAdjustment = 0.00;
        $scope.totalDiscount = 0.00;
        $scope.total = 0.00;
        $scope.diableProductRate = false;

        $scope.order = {
            product_items: [],
            products: [],
            sales_type: "Retail",
            sales_location_id : null
        };

        $scope.productEditMode = false;
        $scope.priceBook = {};
        $scope.salesLocationId = null;

        $scope.productModel = {
            product_id: null,
            product_name: null,
            store_id: null,
            tore_name: null,
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
            cusDropDown: $('.cus-drop-down'),
            repDropDown: $('.rep-drop-down'),
            priceBookDropDown: $('.pb-drop-down'),
            discountRateInput: $('.discount-input'),
            adjustmentInput: $('.adjustment-input'),
            subTotalInput: $('.sub-total-input'),
            totalInput: $('.total-input'),
            totalDiscountTypeDropDown: $('.discount-type'),
            productDropDown: $('.product-drop-down'),
            storeDropDown: $('.store-drop-down'),
            orderTypeCheck: $('.order-type'),
            salesTypeCheck: $('.sales-type'),
            scheduleDatePicker: $('.schedule-date'),
            itemBtnContainer: $('.item-btn-container'),
            itemTemplate: $('#item-template'),
            resetPriceBook: $('#reset-price-book'),
            locationDropDown: $('.location-drop-down'),
            salesCategoryCheck: $('.sales-category'),
            repPanel: $('.rep-panel'),
            salesLocationPanel: $('.sales-location-panel')
        };

        $scope.isRemoveable = false;
        $scope.isInquiry = false;
        $scope.isEstimation = false;
        $scope.showPriceBook = false;

        /** require urls */
        $scope.urls = {
            bt: '{{ route('setting.business.type.search') }}',
            cus: '{{ route('sales.customer.search') }}',
            rep: '{{ route('setting.rep.search') }}',
            product: '{{ route('setting.sales.product.search') }}',
            store: '{{ route('setting.store.search') }}',
            pb: '{{ route('setting.price.book.search.by.rep', ['rep' => 'REP']) }}',
            sl: '{{ route('setting.sales.location.search.type', ['type' => 'Van']) }}'
        };

        @if (isset($inquiry) && $inquiry)
            $scope.order = @json($inquiry);
        $scope.isInquiry = true;
        @endif

        @if (isset($estimate) && $estimate)
            $scope.order = @json($estimate);
        $scope.isEstimation = true;
        @endif

        @if(isset($customer))
            $scope.el.cusDropDown.dropdown('set value', '{{ $customer->id }}');
            $scope.el.cusDropDown.dropdown('set text', '{{ $customer->display_name }}');
        @endif

        /** set request old data */
        @if (isset($order) && $order)
            $scope.order = @json($order);
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
        $scope.mapProductItems = function () {
            $.each($scope.order.product, function (k, v) {
                if (!$scope.order.hasOwnProperty('product_items')) {
                    $scope.order.product_items = [];
                }

                var item = {
                    product_id: null,
                    product_name: null,
                    store_id: null,
                    store_name: null,
                    quantity: 0,
                    rate: 0,
                    discount_rate: 0,
                    notes: null,
                    discount_type: "Amount",
                    amount: 0
                };

                if ($scope.order.hasOwnProperty('product') && $scope.order.product.hasOwnProperty(k)) {
                    item.product_id = $scope.order.product[k];
                }
                if ($scope.order.hasOwnProperty('product_name') && $scope.order.product_name.hasOwnProperty(k)) {
                    item.product_name = $scope.order.product_name[k];
                }
                if ($scope.order.hasOwnProperty('store') && $scope.order.store.hasOwnProperty(k)) {
                    item.store_id = $scope.order.store[k];
                }
                if ($scope.order.hasOwnProperty('store_name') && $scope.order.store_name.hasOwnProperty(k)) {
                    item.store_name = $scope.order.store_name[k];
                }
                if ($scope.order.hasOwnProperty('quantity') && $scope.order.quantity.hasOwnProperty(k)) {
                    item.quantity = $scope.order.quantity[k];
                }
                if ($scope.order.hasOwnProperty('rate') && $scope.order.rate.hasOwnProperty(k)) {
                    item.rate = $scope.order.rate[k];
                }
                if ($scope.order.hasOwnProperty('item_discount_rate') && $scope.order.item_discount_rate.hasOwnProperty(k)) {
                    item.discount_rate = $scope.order.item_discount_rate[k];
                }
                if ($scope.order.hasOwnProperty('product_notes') && $scope.order.product_notes.hasOwnProperty(k)) {
                    item.notes = $scope.order.product_notes[k];
                }
                if ($scope.order.hasOwnProperty('item_discount_type') && $scope.order.item_discount_type.hasOwnProperty(k)) {
                    item.discount_type = $scope.order.item_discount_type[k];
                }
                if ($scope.order.hasOwnProperty('amount') && $scope.order.amount.hasOwnProperty(k)) {
                    item.amount = $scope.order.amount[k];
                }
                $scope.order.product_items.push(item);
            })
        };

        /** set form old values */
        @if (old('_token'))
            $scope.order = @json(old());
            $scope.totalDiscount = $scope.order.discount_rate;
            $scope.mapProductItems();
        @endif

        /** map validation messages to form errors variable */
        $scope.errors = {};
        $scope.mappedErrors = {};
        @if (isset($errors))
            $scope.errors = @json($errors->toArray());
        $scope.mappedErrors = $scope.mapError($scope.errors);
        @endif

        $scope.hasError = function (name, index) {
            if ($scope.mappedErrors.hasOwnProperty(name)) {
                if ($scope.mappedErrors[name].hasOwnProperty(index)) {
                    return $scope.mappedErrors[name][index];
                }
            }
            return false;
        };
        $scope.getError = function (name, index) {
            if ($scope.mappedErrors.hasOwnProperty(name)) {
                if ($scope.mappedErrors[name].hasOwnProperty(index)) {
                    return $scope.mappedErrors[name][index];
                }
            }
            return "";
        };

        /** insert product item */
        $scope.insertItems = function () {
            $scope.order.product_items.push(angular.copy($scope.productModel));
            $scope.isRemoveable = $scope.order.product_items.length > 1;
        };

        /** add product item */
        $scope.addItem = function () {
            $scope.insertItems();
            setTimeout(function () {
                if ($scope.order.product_items.length > 0){
                    var index = $scope.order.product_items.length -1 ;
                    $('.product-drop-down[data-index="' + index +'"] input').focus();
                }
            }, 600);
        };

        /** check if items exist if not insert a new item */
        if (!$scope.order.hasOwnProperty('product_items') || ($scope.order.hasOwnProperty('product_items') && !$scope.order.product_items.length)) {
            $scope.insertItems();
        }

        /** remove product item */
        $scope.removeItem = function (index) {
            $scope.order.product_items = $scope.removeByKey($scope.order.product_items, index);
            $scope.isRemoveable = $scope.order.product_items.length > 1;
        };

        /** remove item from object by key */
        $scope.removeByKey = function (array, index) {
            if (array.hasOwnProperty(index)) {
                array.splice(index, 1);
            }
            return array;
        };

        /** Price book dropdown change event's function */
        $scope.priceBookChanged = function (id) {
            $scope.getPriceBook(id, function (data) {
                $scope.priceBook = data;
                $scope.updatePriceBookPrice();
            });
        };

        /** Update price book price for each items */
        $scope.updatePriceBookPrice = function () {
            $.each($scope.order.product_items, function (index, item) {
                $scope.updatePriceBookAmountToItem(index);
            });
        };

        /** Update price book price for a items */
        $scope.updatePriceBookAmountToItem = function (itemIndex) {
            if ($scope.order.product_items.hasOwnProperty(itemIndex) && $scope.priceBook.hasOwnProperty('prices')) {
                var productItem = $scope.order.product_items[itemIndex];
                var priceBook = _.first(_.filter($scope.priceBook.prices, function (priceItem) {
                    return productItem.product_id == priceItem.product_id
                    && productItem.quantity >= priceItem.range_start_from
                    &&  productItem.quantity <= priceItem.range_end_to
                }));

                if (priceBook) {
                    if (priceBook && $scope.order.product_items.hasOwnProperty(itemIndex) && $scope.order.product_items[itemIndex].hasOwnProperty('rate')) {
                        $scope.order.product_items[itemIndex]['rate'] = priceBook.price;
                        $scope.changedValue(itemIndex);
                    }
                } else {
                    if (!$scope.order.hasOwnProperty('products')) $scope.order['products'] = [];
                    if ($scope.order.products.hasOwnProperty(itemIndex)) {
                        var product = $scope.order.products[itemIndex];
                        $scope.updateItemRate(itemIndex, product);
                    }
                }
                if (!$scope.$$phase) $scope.$apply();
            } else {
                if (!$scope.order.hasOwnProperty('products')) $scope.order['products'] = [];
                if ($scope.order.products.hasOwnProperty(itemIndex)) {
                    var product = $scope.order.products[itemIndex];
                    $scope.updateItemRate(itemIndex, product);
                }

            }
        };

        /** Get price book remote data */
        $scope.getPriceBook = function (id, callback) {
            var url = '{{ route('setting.price.book.show', 'ID') }}'.replace('ID', id);
            $http.get(url).then(function (response) {
                if (typeof callback === 'function') {
                    callback(response.data)
                }
            });
        };

        /** Update product rate base on sales type */
        $scope.updateItemRate = function (itemIndex, product) {
            if (!product) return null;
            var amount = 0;
            switch ($scope.order.sales_type) {
                case 'Retail':
                    amount = product.retail_price;
                    break;
                case 'Wholesale':
                    amount = product.wholesale_price;
                    break;
                case 'Distribution':
                    amount = product.distribution_price;
                    break;
            }

            if ($scope.order.product_items.hasOwnProperty(itemIndex)) {
                $scope.order.product_items[itemIndex]['rate'] = amount;
                $scope.changedValue(itemIndex);
                if (!$scope.order.hasOwnProperty('products')) {
                    $scope.order['products'] = [];
                }
                $scope.order.products[itemIndex] = product;
                if (!$scope.$$phase) $scope.$apply();
            }
        };

        /** Sales type checkbox changed event;s function */
        $scope.changedSalesType = function () {
            $.each($scope.order.product_items, function (index, item) {
                if (!$scope.order.hasOwnProperty('products')) {
                    $scope.order['products'] = [];
                }
                if ($scope.order.products.hasOwnProperty(index)) {
                    var product = $scope.order.products[index];
                    $scope.updateItemRate(index, product);
                    $scope.updatePriceBookPrice();
                } else {
                    $scope.productChanged(item.product_id, index);
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

        /** Product dropdown change event's function */
        $scope.productChanged = function (id, index) {
            $scope.getProduct(id, function (product) {
                $scope.updateItemRate(index, product);
                $scope.updatePriceBookPrice();
            });
        };


        /** When change a value each item. Update the calculated values */
        $scope.changedValue = function (index) {
            if ($scope.order.product_items && $scope.order.product_items.hasOwnProperty(index)) {
                if (!$scope.order.product_items[index].hasOwnProperty('discount_rate')) {
                    $scope.order.product_items[index]['discount_rate'] = 0;
                }
                var product = $scope.order.product_items[index];
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
                if (!$scope.order.product_items[index].hasOwnProperty('amount')) {
                    $scope.order.product_items[index]['amount'] = 0;
                }
                $scope.order.product_items[index].amount = amount;
                if (!$scope.order.product_items[index].hasOwnProperty('discount')) {
                    $scope.order.product_items[index]['discount'] = 0;
                }
                $scope.order.product_items[index].discount = discount;
            }
            $scope.calculateTotal();
        };

        $scope.changedQuantity = function(index){
            $scope.updatePriceBookAmountToItem(index);
        };

        /** calculate order total */
        $scope.calculateTotal = function () {
            $scope.subTotal = sum($scope.order.product_items, 'amount');
            $scope.subTotal = chief_double($scope.subTotal);
            var discount = chief_double($scope.totalDiscount);
            var totalAdjustment = chief_double($scope.totalAdjustment);
            if ($scope.totalDiscountType === 'Percentage') {
                discount = $scope.subTotal * (discount / 100);
            }
            $scope.total = ($scope.subTotal - discount) + totalAdjustment;
            $scope.total = chief_double($scope.total);
            if (!$scope.$$phase) $scope.$apply();
        };

        setTimeout(function () {
            if ($scope.isInquiry || $scope.isEstimation) {
                $scope.calculateTotal();
            }
        }, 600);

        /** round the number */
        function roundToTwo(num) {
            return +(Math.round(num + "e+2") + "e-2");
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
            if ($scope.order.hasOwnProperty('discount_rate') && $scope.order.hasOwnProperty('discount_type')) {
                var label = $scope.order.discount_type === 'Percentage' ? '%' : 'LKR';
                $scope.setDropDownValue($scope.el.totalDiscountTypeDropDown, $scope.order.discount_type, label);
            }

            if ($scope.order.hasOwnProperty('business_type_id') && $scope.order.hasOwnProperty('business_type_name')) {
                $scope.setDropDownValue($scope.el.btDropDown, $scope.order.business_type_id, $scope.order.business_type_name);
            }

            if ($scope.order.hasOwnProperty('customer_id') && $scope.order.hasOwnProperty('customer_name')) {
                $scope.setDropDownValue($scope.el.cusDropDown, $scope.order.customer_id, $scope.order.customer_name);
            }

            if ($scope.order.hasOwnProperty('rep_id') && $scope.order.hasOwnProperty('rep_name')) {
                $scope.setDropDownValue($scope.el.repDropDown, $scope.order.rep_id, $scope.order.rep_name);
            }

            if ($scope.order.hasOwnProperty('price_book_id') && $scope.order.hasOwnProperty('price_book_name')) {
                $scope.setDropDownValue($scope.el.priceBookDropDown, $scope.order.price_book_id, $scope.order.price_book_name);
            }

            if ($scope.order.hasOwnProperty('sales_location_id') && $scope.order.hasOwnProperty('sales_location_name')) {
                $scope.setDropDownValue($scope.el.locationDropDown, $scope.order.sales_location_id, $scope.order.sales_location_name);
            }

            if ($scope.order.hasOwnProperty('adjustment')) {
                $scope.totalAdjustment = $scope.order.adjustment;
            }

            if ($scope.order.hasOwnProperty('sub_total')) {
                $scope.subTotal = $scope.order.sub_total;
            }

            if ($scope.order.hasOwnProperty('discount')) {
                $scope.totalDiscount = $scope.order.discount_rate;
            }

            if ($scope.order.hasOwnProperty('total')) {
                $scope.total = $scope.order.total;
            }
        };

        /** set values to semantic UI drop-down */
        $scope.setDropDownValue = function (dd, value, name) {
            dd.dropdown("refresh");
            dd.dropdown('set value', value);
            dd.dropdown('set text', name);
        };

        $scope.setMainFormValues();

        $scope.el.resetPriceBook.click(function (e) {
            e.preventDefault();
            $scope.el.priceBookDropDown.dropdown('clear');
            $scope.updatePriceBookPrice();
        });

        /** init business type drop-down */
        $scope.el.btDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            apiSettings: {
                url: $scope.urls.bt + '/{query}',
                cache: false
            }
        });

        /** set values to order type checkbox */
        if ($scope.order) {
            if ($scope.order.order_type === 'Direct' || $scope.order.order_type == null) {
                $scope.el.scheduleDatePicker.fadeOut();
            }
        }

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

        /** manage schedule DatePicker on order type check box change event */
        $scope.el.orderTypeCheck.change(function (e) {
            e.preventDefault();
            if ($(this).val() === 'Schedule') {
                $scope.el.scheduleDatePicker.fadeIn();
            } else {
                $scope.el.scheduleDatePicker.fadeOut();
            }
        });

        if ($scope.order.hasOwnProperty('sales_category') && $scope.order.sales_category == "Van"){
            $scope.el.repPanel.fadeIn();
            $scope.el.salesLocationPanel.fadeIn();
            $scope.diableProductRate = true;
        }else{
            $scope.el.repPanel.fadeOut();
            $scope.el.salesLocationPanel.fadeOut();
            $scope.diableProductRate = false;
        }

        /** make visible rep and location dropDowns */
        $scope.el.salesCategoryCheck.change(function (e) {
            e.preventDefault();
            if ($(this).val() === 'Van') {
                $scope.el.repPanel.fadeIn();
                $scope.el.salesLocationPanel.fadeIn();
                $scope.diableProductRate = true;
                $scope.$apply();
            } else {
                $scope.el.repPanel.fadeOut();
                $scope.el.salesLocationPanel.fadeOut();
                $scope.diableProductRate = false;
                $scope.$apply();
            }
        });


        /** Init Sales type check box change event */
        $scope.el.salesTypeCheck.change(function (e) {
            $scope.order.sales_type = $(this).val();
            $scope.changedSalesType($(this).val());
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

        /** init rep drop-down */
        $scope.el.repDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            apiSettings: {
                url: $scope.urls.rep + '/{query}',
                cache: false
            },
            onChange: function (value, name, el) {
                if (value){
                    $scope.order.rep_id = value;
                    $scope.showPriceBook = true;
                    $scope.$apply();
                    $scope.initPriceBookDropdown();
                }
            }
        });

        $scope.initPriceBookDropdown = function(){
            $scope.el.priceBookDropDown.dropdown('clear');
            /** init price book drop-down */
            var Url = $scope.urls.pb.replace('REP', $scope.order.rep_id);
            $scope.el.priceBookDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url:  Url + '/{query}',
                    cache: false
                },
                onChange: function (value, name, el) {
                    $scope.priceBookChanged(value);
                }
            });
        };

        if ($scope.order  && $scope.order.sales_location_id){
            $scope.showPriceBook = true;
            $scope.initPriceBookDropdown();
            $scope.setDropDownValue($scope.el.priceBookDropDown, $scope.order.price_book_id, $scope.order.price_book_name)
        }

        /** init sales location drop-down */
        $scope.el.locationDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            apiSettings: {
                url: $scope.urls.sl + '/{query}',
                cache: false
            },
            onChange: function (value, name, el) {
                if (value){
                    $scope.order.sales_location_id = value;
                    $scope.$apply();
                }
            }
        });

        /** init discount drop-down */
        $scope.initItemDiscountTypeDropDown = function (el) {
            el.dropdown('setting', {
                forceSelection: false,
                onChange: function (value, name, el) {
                    var index = $(this).data('index').toString();
                    if ($scope.order.product_items.hasOwnProperty(index) && $scope.order.product_items[index].hasOwnProperty('discount_type')) {
                        $scope.order.product_items[index]['discount_type'] = value;
                    }
                    $timeout(function () {
                        $scope.changedValue(index);
                    }, 10)
                }
            });
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
                    // if ($scope.order.product_items.hasOwnProperty(index) && !$scope.productEditMode) {
                    if ($scope.order.product_items.hasOwnProperty(index)) {
                        if ($scope.order.product_items[index].hasOwnProperty('product_id')) {
                            $scope.order.product_items[index]['product_id'] = value;
                        }
                        if ($scope.order.product_items[index].hasOwnProperty('product_name')) {
                            $scope.order.product_items[index]['product_name'] = name;
                        }
                        $scope.productChanged(value, index)
                    }
                    $scope.productEditMode = false;
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
                    if ($scope.order.product_items.hasOwnProperty(index)) {
                        if ($scope.order.product_items[index].hasOwnProperty('store_id')) {
                            $scope.order.product_items[index]['store_id'] = value;
                        }
                        if ($scope.order.product_items[index].hasOwnProperty('store_name')) {
                            $scope.order.product_items[index]['store_name'] = name;
                        }
                    }
                }
            });
        };

        /** product items drop-down values */
        $scope.setProductItemsDropDownValues = function () {
            $.each($scope.order.product_items, function (index, product) {
                if (product.product_id && product.product_name) {
                    $scope.productEditMode = true;
                    $scope.setDropDownValue($('.product-drop-down[data-index="' + index + '"]'), product.product_id, product.product_name);
                }
                if (product.store_id && product.store_name) {
                    $scope.setDropDownValue($('.store-drop-down[data-index="' + index + '"]'), product.store_id, product.store_name);
                }
                if (product.discount_type) {
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
