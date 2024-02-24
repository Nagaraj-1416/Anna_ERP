<script src="{{ asset('js/vendor/form.js') }}"></script>
<script src="{{ asset('js/vendor/object-helper.js') }}"></script>
<script>
    app.controller('PurchaseOrderController', function ($scope, $timeout, $http) {
        $scope.totalAdjustment = 0.00;
        $scope.totalDiscount = 0.00;
        $scope.total = 0.00;

        // Models
        $scope.order = {
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
            discount_type: "Amount",
            show_info : false,
            is_expirable : 'No',
            manufacture_date : null,
            expired_date : null,
            batch_no : null,
            brand_id : null,
            brand_name : null,
            last_purchases : []
        };

        // related elements
        $scope.el = {
            btDropDown: $('.bt-drop-down'),
            supDropDown: $('.sup-drop-down'),
            discountRateInput: $('.discount-input'),
            adjustmentInput: $('.adjustment-input'),
            subTotalInput: $('.sub-total-input'),
            totalInput: $('.total-input'),
            totalDiscountTypeDropDown: $('.discount-type'),
            productDropDown: $('.product-drop-down'),
            unitDropDown: $('.unit-drop-down'),
            storeDropDown: $('.store-drop-down'),
            shopDropDown: $('.shop-drop-down'),
            companyDropDown: $('.company-drop-down'),
            poTypeCheck: $('.po-type'),
            poForCheck: $('.po-for'),
            scheduleDatePicker: $('.schedule-date'),
            itemBtnContainer: $('.item-btn-container'),
            itemTemplate: $('#item-template'),
            unitDropPanel: $('.unit-drop-panel'),
            storeDropPanel: $('.store-drop-panel'),
            shopDropPanel: $('.shop-drop-panel')
        };
        $scope.isRemoveable = false;
        // related urls
        $scope.urls = {
            bt: '{{ route('setting.business.type.search') }}',
            sup: '{{ route('purchase.supplier.search') }}',
            product: '{{ route('setting.product.search', ['type' => 'All']) }}',
            lastPurchases: '{{ route('setting.product.last.purchased.prices', ['product' => 'PRODUCT']) }}',
            store: '{{ route('setting.store.search') }}',
            brand: '{{ route('setting.brand.search') }}'
        };
        // Set old data
        @if (isset($order) && $order)
            $scope.order = @json($order);
        @endif
        // Mapping errors data
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

        // mapping product items
        $scope.mapProductItems = function(){
            $.each($scope.order.product, function (k, v) {
                if (!$scope.order.hasOwnProperty('product_items')) {
                    $scope.order.product_items = [];
                }

                var item = {
                    product_id: null,
                    product_name: null,
                    brand_id: null,
                    brand_name: null,
                    store_id: null,
                    store_name: null,
                    quantity: null,
                    rate: null,
                    discount_rate: null,
                    notes: null,
                    discount_type: null,
                    amount: null
                };

                if ($scope.order.hasOwnProperty('product') && $scope.order.product.hasOwnProperty(k)){
                    item.product_id = $scope.order.product[k];
                }
                if ($scope.order.hasOwnProperty('product_name') && $scope.order.product_name.hasOwnProperty(k)){
                    item.product_name = $scope.order.product_name[k];
                }
                if ($scope.order.hasOwnProperty('brand') && $scope.order.brand.hasOwnProperty(k)){
                    item.brand_id = $scope.order.brand[k];
                }
                if ($scope.order.hasOwnProperty('brand_name') && $scope.order.brand_name.hasOwnProperty(k)){
                    item.brand_name = $scope.order.brand_name[k];
                }
                if ($scope.order.hasOwnProperty('store') && $scope.order.store.hasOwnProperty(k)){
                    item.store_id = $scope.order.store[k];
                }
                if ($scope.order.hasOwnProperty('store_name') && $scope.order.store_name.hasOwnProperty(k)){
                    item.store_name = $scope.order.store_name[k];
                }
                if ($scope.order.hasOwnProperty('quantity') && $scope.order.quantity.hasOwnProperty(k)){
                    item.quantity = $scope.order.quantity[k];
                }
                if ($scope.order.hasOwnProperty('rate') && $scope.order.rate.hasOwnProperty(k)){
                    item.rate = $scope.order.rate[k];
                }
                if ($scope.order.hasOwnProperty('item_discount_rate') && $scope.order.item_discount_rate.hasOwnProperty(k)){
                    item.discount_rate = $scope.order.item_discount_rate[k];
                }
                if ($scope.order.hasOwnProperty('product_notes') && $scope.order.product_notes.hasOwnProperty(k)){
                    item.notes = $scope.order.product_notes[k];
                }
                if ($scope.order.hasOwnProperty('item_discount_type') && $scope.order.item_discount_type.hasOwnProperty(k)){
                    item.discount_type = $scope.order.item_discount_type[k];
                }
                if ($scope.order.hasOwnProperty('amount') && $scope.order.amount.hasOwnProperty(k)){
                    item.amount = $scope.order.amount[k];
                }

                if ($scope.order.hasOwnProperty('expired_date') && $scope.order.expired_date.hasOwnProperty(k)){
                    item.expired_date = $scope.order.expired_date[k];
                }

                if ($scope.order.hasOwnProperty('manufacture_date') && $scope.order.manufacture_date.hasOwnProperty(k)){
                    item.manufacture_date = $scope.order.manufacture_date[k];
                }

                if ($scope.order.hasOwnProperty('is_expirable') && $scope.order.is_expirable.hasOwnProperty(k)){
                    item.is_expirable = $scope.order.is_expirable[k];
                }

                if ($scope.order.hasOwnProperty('batch_no') && $scope.order.batch_no.hasOwnProperty(k)){
                    item.batch_no = $scope.order.batch_no[k];
                }
                $scope.order.product_items.push(item);
            })
        };
        // Set form old values
        @if (old('_token'))
            $scope.order = @json(old());
            $scope.totalDiscount = $scope.order.discount_rate;
            $scope.mapProductItems();
        @endif
        // Set the validation errors to errors variable
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

        // Add product item
        $scope.addItem = function () {
            $scope.order.product_items.push(angular.copy($scope.productModel));
            $scope.isRemoveable = $scope.order.product_items.length > 1;
        };
        // Check the items exist if not insert new item
        if (!$scope.order.hasOwnProperty('product_items') || ($scope.order.hasOwnProperty('product_items') && !$scope.order.product_items.length)) {
            $scope.addItem();
        }
        // Remove product item
        $scope.removeItem = function (index) {
            $scope.order.product_items = $scope.removeByKey($scope.order.product_items, index);
            $scope.isRemoveable = $scope.order.product_items.length > 1;
        };
        // Remove item from object by key
        $scope.removeByKey = function (array, index) {
            if (array.hasOwnProperty(index)) {
                array.splice(index, 1);
            }
            return array;
        };

        $scope.initInfo = function(index){
            if ($scope.order.product_items && $scope.order.product_items.hasOwnProperty(index) && $scope.order.product_items[index].product_id) {
                $scope.order.product_items[index].show_info = true;
                var url = $scope.urls.lastPurchases.replace('PRODUCT',$scope.order.product_items[index].product_id );
                $http.get(url).then(function (result) {
                    $scope.order.product_items[index].last_purchases = result.data;
                    $element = $('span[data-index="product-tool-tip-'+index+'"]');
                    var title = "<h6 class='m-1' style='color:white'>Last 5 purchased prices</h6><p> Purchases are not available </p>";
                    if ($scope.order.product_items[index].last_purchases.length > 0){
                        title = "<h6  class='m-1' style='color:white'>Last 5 purchased prices</h6>" +
                            "<table style='font-size: 10px;' class=\"table table-bordered\">" +
                            "<tr>\n" +
                            "    <td>PO No</td>\n" +
                            // "    <th>PO Date</th>\n" +
                            "    <td>Price</td>\n" +
                            "</tr>";
                        $.each($scope.order.product_items[index].last_purchases, function (key, value) {
                            title += "<tr>\n" +
                                "        <td>" + value.po_no + "</td>\n" +
                                // "        <td>" + value.order_date + "</td>\n" +
                                "        <td>" + value.price + "</td>\n" +
                                "    </tr>";
                        });
                        title += "</table>";
                    }

                    // $element.attr('title', title);
                    $element.attr('data-original-title', title);
                    $element.tooltip();
                });
            }
        };

        $scope.changedValue = function (index) {
            if ($scope.order.product_items && $scope.order.product_items.hasOwnProperty(index)) {
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
                if ($scope.order.product_items[index].hasOwnProperty('amount')) {
                    $scope.order.product_items[index].amount = amount;
                }
                if ($scope.order.product_items[index].hasOwnProperty('discount')) {
                    $scope.order.product_items[index].discount = discount;
                }
                $scope.initInfo(index);
            }
            $scope.calculateTotal();
        };

        // Calculate order total
        $scope.calculateTotal = function () {
            $scope.subTotal = sum($scope.order.product_items, 'quantity');
            $scope.subTotal = chief_double($scope.subTotal);
            $scope.total = chief_double($scope.subTotal);
        };

        function roundToTwo(num) {
            return +(Math.round(num + "e+2")  + "e-2");
        }

        // Check double value
        function chief_double(num) {
            var n = roundToTwo(parseFloat(num));
            if (isNaN(n)) {
                return 0.00;
            }
            else {
                return roundToTwo(parseFloat(num));
            }
        }

        // sum the key values in object
        function sum(object, key) {
            return _.reduce(object, function (memo, item) {
                if (item.hasOwnProperty(key)) {
                    var value = chief_double(item[key]);
                    return memo + value;
                }
                return memo;
            }, 0)
        }

        // Set main form dropdown values
        $scope.setMainFormValues = function () {
            if ($scope.order.hasOwnProperty('discount_rate') && $scope.order.hasOwnProperty('discount_type')){
                var label = $scope.order.discount_type === 'Percentage' ? '%' : 'LKR';
                $scope.setDropDownValue($scope.el.totalDiscountTypeDropDown, $scope.order.discount_type, label);
            }

            if ($scope.order.hasOwnProperty('business_type_id') && $scope.order.hasOwnProperty('business_type_name')) {
                $scope.setDropDownValue($scope.el.btDropDown, $scope.order.business_type_id, $scope.order.business_type_name);
            }

            if ($scope.order.hasOwnProperty('supplier_id') && $scope.order.hasOwnProperty('supplier_name')) {
                $scope.setDropDownValue($scope.el.supDropDown, $scope.order.supplier_id, $scope.order.supplier_name);
            }

            if ($scope.order.hasOwnProperty('company_id') && $scope.order.hasOwnProperty('company_name')) {
                $scope.setDropDownValue($scope.el.companyDropDown, $scope.order.company_id, $scope.order.company_name);
            }

            if ($scope.order.hasOwnProperty('adjustment')){
                $scope.totalAdjustment = $scope.order.adjustment;
            }
            if ($scope.order.hasOwnProperty('sub_total')){
                $scope.subTotal = $scope.order.sub_total;
            }

            if ($scope.order.hasOwnProperty('discount')){
                $scope.totalDiscount = $scope.order.discount_rate;
            }
            if ($scope.order.hasOwnProperty('total')){
                $scope.total = $scope.order.total;
            }
        };
        // Set values to semantic UI dropdown
        $scope.setDropDownValue = function (dd, value, name) {
            dd.dropdown("refresh");
            dd.dropdown('set value', value);
            dd.dropdown('set text', name);
        };

        $scope.setMainFormValues();
        // Init Business type dropdown
        $scope.el.btDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            apiSettings: {
                url: $scope.urls.bt + '/{query}',
                cache: false
            }
        });
        // set values to PO type checkbox
        if($scope.order){
            if($scope.order.po_type === 'Direct' || $scope.order.po_type == null){
                $scope.el.scheduleDatePicker.fadeOut();
            }
        }

        // Total discount Type drop down
        $scope.el.totalDiscountTypeDropDown.dropdown('setting', {
            forceSelection: false,
            onChange: function (value, name, el) {
                $scope.totalDiscountType = value;
                $timeout(function () {
                    $scope.calculateTotal();
                }, 10)
            }
        });

        // Manage schedule DatePicker on PO type check box change event
        $scope.el.poTypeCheck.change(function (e) {
            e.preventDefault();
            if ($(this).val() === 'Schedule') {
                $scope.el.scheduleDatePicker.fadeIn();
            } else {
                $scope.el.scheduleDatePicker.fadeOut();
            }
        });

        // Manage schedule DatePicker on PO type check box change event
        $scope.el.poForCheck.change(function (e) {
            e.preventDefault();
            if ($(this).val() === 'PUnit') {
                $scope.el.unitDropPanel.show();
                $scope.el.storeDropPanel.hide();
                $scope.el.shopDropPanel.hide();

                $scope.el.supDropDown.dropdown('clear');
                allSupplier($(this).val());

            } else if($(this).val() === 'Store') {
                $scope.el.storeDropPanel.show();
                $scope.el.unitDropPanel.hide();
                $scope.el.shopDropPanel.hide();

                $scope.el.supDropDown.dropdown('clear');
                storeSuppliers($(this).val());

            }else if($(this).val() === 'Shop') {
                $scope.el.shopDropPanel.show();
                $scope.el.storeDropPanel.hide();
                $scope.el.unitDropPanel.hide();

                $scope.el.supDropDown.dropdown('clear');
                shopSuppliers($(this).val());
            }
        });

        @if(old('_token'))
            handlePoFor('{{old('po_for')}}');
        @else
            var poForOnLoad = $scope.el.poForCheck.val();
            handlePoFor(poForOnLoad);
        @endif

        @if(isset($order))
            handlePoFor('{{ $order->po_for }}');
        @endif

        function handlePoFor(poForOnLoad) {
            if (poForOnLoad === 'PUnit') {
                $scope.el.unitDropPanel.show();
                $scope.el.storeDropPanel.hide();
                $scope.el.shopDropPanel.hide();

                $scope.el.supDropDown.dropdown('clear');
                allSupplier(poForOnLoad);

            } else if(poForOnLoad === 'Store') {
                $scope.el.storeDropPanel.show();
                $scope.el.unitDropPanel.hide();
                $scope.el.shopDropPanel.hide();

                $scope.el.supDropDown.dropdown('clear');
                storeSuppliers(poForOnLoad);

            }else if(poForOnLoad === 'Shop') {
                $scope.el.shopDropPanel.show();
                $scope.el.storeDropPanel.hide();
                $scope.el.unitDropPanel.hide();

                $scope.el.supDropDown.dropdown('clear');
                shopSuppliers(poForOnLoad);
            }
        }

        function allSupplier(type)
        {
            var url = '{{ route('purchase.supplier.by.type.search', ['poFor']) }}';
            url = url.replace('poFor', type);
            $scope.el.supDropDown.dropdown('setting', {
                forceSelection: false,
                apiSettings: {
                    url: url + '/{query}',
                    cache:false,
                },
                saveRemoteData:false
            });
        }

        function storeSuppliers(type)
        {
            var url = '{{ route('purchase.supplier.by.type.search', ['poFor']) }}';
            url = url.replace('poFor', type);
            $scope.el.supDropDown.dropdown('setting', {
                forceSelection: false,
                apiSettings: {
                    url: url + '/{query}',
                    cache:false,
                },
                saveRemoteData:false
            });
        }

        function shopSuppliers(type)
        {
            var url = '{{ route('purchase.supplier.by.type.search', ['poFor']) }}';
            url = url.replace('poFor', type);
            $scope.el.supDropDown.dropdown('setting', {
                forceSelection: false,
                apiSettings: {
                    url: url + '/{query}',
                    cache:false,
                },
                saveRemoteData:false
            });
        }

        // Init discount drop down
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

        /** Get product remote data */
        $scope.getProduct = function (id, callback) {
            var url = '{{ route('setting.product.show', 'ID') }}'.replace('ID', id);
            $http.get(url).then(function (response) {
                if (typeof callback === 'function') {
                    callback(response.data)
                }
            });
        };

        $scope.setPrice = function(value, index){
            $scope.getProduct(value, function (product) {
                if ($scope.order.product_items[index].hasOwnProperty('rate')) {
                    $scope.order.product_items[index]['rate'] = product.buying_price;
                    $scope.changedValue(index);
                }
                if ($scope.order.product_items[index].hasOwnProperty('is_expirable')) {
                    $scope.order.product_items[index].is_expirable = product.is_expirable;
                }
            });
        };

        // Init product drop down
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
                    if ($scope.order.product_items.hasOwnProperty(index)) {
                        if ($scope.order.product_items[index].hasOwnProperty('product_id')) {
                            $scope.order.product_items[index]['product_id'] = value;
                        }
                        if ($scope.order.product_items[index].hasOwnProperty('product_name')) {
                            $scope.order.product_items[index]['product_name'] = name;
                        }
                        $scope.setPrice(value, index);
                    }
                }
            });
        };

        $scope.setBrandValue = function(index, value, name){
            if ($scope.order.product_items.hasOwnProperty(index)) {
                if ($scope.order.product_items[index].hasOwnProperty('brand_id')) {
                    $scope.order.product_items[index]['brand_id'] = value;
                }
                if ($scope.order.product_items[index].hasOwnProperty('brand_name')) {
                    $scope.order.product_items[index]['brand_name'] = name;
                }
            }
        };

        // Init brand drop down
        $scope.initBrandDropDown = function (el) {
            el.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: $scope.urls.brand + '/{query}',
                    cache: false
                },
                onChange: function (value, name, el) {
                    var thisEl = $(this);
                    var index = thisEl.data('index').toString();
                    if (!name){
                        setTimeout(function () {
                            var name = thisEl.dropdown('get text');
                            $scope.setBrandValue(index, value, name);
                        }, 200);
                    }else{
                        $scope.setBrandValue(index, value, name);
                    }
                }
            });
        };

        // Init unit drop down
        $scope.initUnitDropDown = function (el) {
            el.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false
            });
        };

        // Init store drop down
        $scope.initStoreDropDown = function (el) {
            el.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false
            });
        };

        // Init shop drop down
        $scope.initShopDropDown = function (el) {
            el.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false
            });
        };

        // Init company drop down
        $scope.initCompanyDropDown = function (el) {
            el.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
            });
        };
        // Product items dropdown values
        $scope.setProductItemsDropDownValues = function () {
            $.each($scope.order.product_items, function (index, product) {
                if (product.product_id && product.product_name){
                    $scope.setDropDownValue($('.product-drop-down[data-index="' + index + '"]'), product.product_id, product.product_name);
                }

                if (product.brand_id && product.brand_name){
                    $scope.setDropDownValue($('.brand-drop-down[data-index="' + index + '"]'), product.brand_id, product.brand_name);
                }else{
                    $scope.setDropDownValue($('.brand-drop-down[data-index="' + index + '"]'), '{{ isset($brand) ? $brand->id : null }}', '{{ isset($brand) ? $brand->name : null }}');
                }

                if (product.discount_type){
                    var label = product.discount_type === 'Percentage' ? '%' : 'LKR';
                    $scope.setDropDownValue($('.item-discount-type[data-index="' + index + '"]'), product.discount_type, label);
                }
            })
        };

        $scope.initDatePicker = function () {
            $('.product-datepicker').datepicker({
                'autoclose': true
            });
        }

    }).directive('productLoop', function () {
        return function (scope, element, attrs) {
            if (scope.$last) {
                scope.initProductDropDown($('.product-drop-down'));
                scope.initBrandDropDown($('.brand-drop-down'));
                scope.initUnitDropDown($('.unit-drop-down'));
                scope.initStoreDropDown($('.store-drop-down'));
                scope.initShopDropDown($('.shop-drop-down'));
                scope.initCompanyDropDown($('.company-drop-down'));
                scope.initItemDiscountTypeDropDown($('.item-discount-type'));
                setTimeout(scope.setProductItemsDropDownValues, 500);
                setTimeout(scope.initDatePicker, 500);
            }
        }
    });
</script>