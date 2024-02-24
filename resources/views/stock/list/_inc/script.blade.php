<script>
    app.controller('StockController', function ($scope, $timeout, $http){
        $scope.items = [];
        $scope.item = {
            product_id: null,
            product_name :null,
            available_stock: null,
            rate: null
        };

        /** require urls */
        $scope.urls = {
            product: '{{ route('setting.sales.product.search') }}',
        };


        $scope.addItem = function () {
            $scope.items.push(angular.copy($scope.item));
            $scope.isRemoveable =  $scope.items.length > 1;
        };


        /** remove product item */
        $scope.removeItem = function (index) {
            $scope.items = $scope.removeByKey($scope.items, index);
            $scope.isRemoveable = $scope.items.length > 1;
        };

        /** remove item from object by key */
        $scope.removeByKey = function (array, index) {
            if (array.hasOwnProperty(index)) {
                array.splice(index, 1);
            }
            return array;
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


        $scope.errors = {};
        $scope.mappedErrors = {};
        @if (isset($errors))
            $scope.errors = @json($errors->toArray());
            $scope.mappedErrors = $scope.mapError($scope.errors);
            console.log('mappedErrors', $scope.mappedErrors);
        @endif
            $scope.hasError = function (name, index) {
            if ($scope.mappedErrors.hasOwnProperty(name)) {
                if ($scope.mappedErrors[name].hasOwnProperty(index)) {
                    return $scope.mappedErrors[name][index];
                }
            }
            return false;
        };

        /** mapping items */
        $scope.mapItems = function (data) {
            $.each(data.product_id, function (k, v) {
                var item = {
                    product_id: null,
                    available_stock: null,
                    rate: null,
                    product_name: null
                };

                if (data.hasOwnProperty('product_id') && data.product_id.hasOwnProperty(k)) {
                    item.product_id = data.product_id[k];
                }
                if (data.hasOwnProperty('product_name') &&data.product_name.hasOwnProperty(k)) {
                    item.product_name = data.product_name[k];
                }
                if (data.hasOwnProperty('available_stock') &&data.available_stock.hasOwnProperty(k)) {
                    item.available_stock = data.available_stock[k];
                }
                if (data.hasOwnProperty('rate') &&data.rate.hasOwnProperty(k)) {
                    item.rate = data.rate[k];
                }

                $scope.items.push(item);
            })
        };

        /** set form old values */
        @if (old('_token'))
            $scope.mapItems(@json(old()));
        @else
            @if(isset($stock))
                $scope.items = @json($stock->items);
                console.log($scope.items);
            @else
                $scope.addItem();
            @endif
        @endif

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
                    $scope.items[index].product_id = value;
                }
            });
        };

        /** set values to semantic UI drop-down */
        $scope.setDropDownValue = function (dd, value, name) {
            dd.dropdown("refresh");
            dd.dropdown('set value', value);
            dd.dropdown('set text', name);
        };

        /** product items drop-down values */
        $scope.setProductItemsDropDownValues = function () {
            $.each($scope.items, function (index, item) {
                if (item.product_id && item.product_name) {
                    $scope.setDropDownValue($('.product-drop-down[data-index="' + index + '"]'), item.product_id, item.product_name);
                }
            })
        }
    }).directive('stockLoop', function () {
        return function (scope, element, attrs) {
            if (scope.$last) {
                scope.initProductDropDown($('.product-drop-down'));
                setTimeout(scope.setProductItemsDropDownValues, 500);
            }
        }
    });
</script>
