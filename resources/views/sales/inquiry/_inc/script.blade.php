<script src="{{ asset('js/vendor/form.js') }}"></script>
<script src="{{ asset('js/vendor/object-helper.js') }}"></script>
<script>
    app.controller('InquiryController', function ($scope, $http) {
        /** inquiries object */
        $scope.inquiry = {
            product_items: []
        };
        /** product Model */
        $scope.productModel = {
            product_id: null,
            product_name: null,
            quantity: 0,
            delivery_date: null,
            notes: null,
        };

        $scope.isRemoveable = false;

        /** Dom elements object */
        $scope.el = {
            btDropDown: $('.bt-drop-down'),
            cusDropDown: $('.cus-drop-down'),
        };

        /** require urls */
        $scope.urls = {
            bt: '{{ route('setting.business.type.search') }}',
            cus: '{{ route('sales.customer.search') }}',
            product: '{{ route('setting.product.search', ['type' => 'Finished Good']) }}',
        };

        /** set values for edit */
        @if (isset($inquiry) && $inquiry)
            $scope.inquiry = @json($inquiry);
        @endif


        /** mapping product items */
        $scope.mapProductItems = function () {
            $.each($scope.inquiry.product_id, function (k, productId) {
                if (!$scope.inquiry.hasOwnProperty('product_items')) {
                    $scope.inquiry.product_items = [];
                }

                var item = {
                    product_id: null,
                    product_name: null,
                    quantity: 0,
                    delivery_date: null,
                    notes: null,
                };

                if ($scope.inquiry.hasOwnProperty('product_id') && $scope.inquiry.product_id.hasOwnProperty(k)){
                    item.product_id = $scope.inquiry.product_id[k];
                }
                if ($scope.inquiry.hasOwnProperty('product_name') && $scope.inquiry.product_name.hasOwnProperty(k)){
                    item.product_name = $scope.inquiry.product_name[k];
                }
                if ($scope.inquiry.hasOwnProperty('quantity') && $scope.inquiry.quantity.hasOwnProperty(k)){
                    item.quantity = $scope.inquiry.quantity[k];
                }
                if ($scope.inquiry.hasOwnProperty('delivery_date') && $scope.inquiry.delivery_date.hasOwnProperty(k)){
                    item.delivery_date = $scope.inquiry.delivery_date[k];
                }
                if ($scope.inquiry.hasOwnProperty('product_notes') && $scope.inquiry.product_notes.hasOwnProperty(k)){
                    item.notes = $scope.inquiry.product_notes[k];
                }

                $scope.inquiry.product_items.push(item);
            })
        };


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

        /** set form old values */
        @if (old('_token'))
            $scope.inquiry = @json(old());
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

        /** add product item */
        $scope.addItem = function () {
            if (!$scope.inquiry.hasOwnProperty('product_items')){
                $scope.inquiry.product_items = [];
            }
            $scope.inquiry.product_items.push(angular.copy($scope.productModel));
            $scope.isRemoveable = $scope.inquiry.product_items.length > 1;
        };


        /** check if items exist if not insert a new item */
        if (!$scope.inquiry.hasOwnProperty('product_items')
            || ($scope.inquiry.hasOwnProperty('product_items')
                && !$scope.inquiry.product_items.length)) {
            $scope.addItem();
        }

        /** remove product item */
        $scope.removeItem = function (index) {
            $scope.inquiry.product_items = $scope.removeByKey($scope.inquiry.product_items, index);
            $scope.isRemoveable = $scope.inquiry.product_items.length > 1;
        };

        /** remove item from object by key */
        $scope.removeByKey = function (array, index) {
            if (array.hasOwnProperty(index)) {
                array.splice(index, 1);
            }
            return array;
        };

        /** set main form drop-down values */
        $scope.setMainFormValues = function () {
            if ($scope.inquiry.hasOwnProperty('business_type_id') && $scope.inquiry.hasOwnProperty('business_type_name')) {
                $scope.setDropDownValue($scope.el.btDropDown, $scope.inquiry.business_type_id, $scope.inquiry.business_type_name);
            }
            if ($scope.inquiry.hasOwnProperty('customer_id') && $scope.inquiry.hasOwnProperty('customer_name')) {
                $scope.setDropDownValue($scope.el.cusDropDown, $scope.inquiry.customer_id, $scope.inquiry.customer_name);
            }
        };


        /** set values to semantic UI drop-down */
        $scope.setDropDownValue = function (dd, value, name) {
            dd.dropdown("refresh");
            dd.dropdown('set value', value);
            dd.dropdown('set text', name);
        };

        $scope.setMainFormValues();
        /** init customer drop-down */
        $scope.el.cusDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            apiSettings: {
                url: $scope.urls.cus + '/{query}',
                cache: false
            }
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
                    if ($scope.inquiry.product_items.hasOwnProperty(index)) {
                        if ($scope.inquiry.product_items[index].hasOwnProperty('product_id')) {
                            $scope.inquiry.product_items[index]['product_id'] = value;
                        }
                        if ($scope.inquiry.product_items[index].hasOwnProperty('product_name')) {
                            $scope.inquiry.product_items[index]['product_name'] = name;
                        }
                    }
                }
            });
        };

        /** init product date picker */
        $scope.initDatePicker = function () {
            $(".delivery-date").datepicker({
                'autoclose': true,
                'format': 'yyyy-mm-dd'
            });
        };

        /** product items drop-down values */
        $scope.setProductItemsDropDownValues = function () {
            $.each($scope.inquiry.product_items, function (index, product) {
                if (product.product_id && product.product_name) {
                    $scope.setDropDownValue($('.product-drop-down[data-index="' + index + '"]'), product.product_id, product.product_name);
                }
            })
        };

    }).directive('inquiryLoop', function () {
        return function (scope, element, attrs) {
            if (scope.$last) {
                scope.initProductDropDown($('.product-drop-down'));
                scope.initDatePicker();
                setTimeout(scope.setProductItemsDropDownValues, 500);
            }
        }
    });
</script>