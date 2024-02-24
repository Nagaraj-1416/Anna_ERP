<script>
    app.controller('PriceController', ['$scope', '$http', function ($scope, $http) {
        $scope.prices = [];
        $scope.priceModal = {
            product: null,
            rangeStartFrom: null,
            rangeEndTo: null,
            amount: null,
            product_name: null
        };

        $scope.el = {
            productDropDown: $('.product-drop-down'),
        };

        $scope.oldData = @json(old());
        $scope.errors = {};

        @if (isset($errors))
            $scope.errors = @json($errors->toArray());
        @endif;

        @if (!old('_token') && isset($prices))
            $scope.oldData = @json($prices);
        @endif;

        $scope.urls = {
            product: '{{ route('setting.product.search', ['type' => 'Finished Good']) }}',
        };

        $scope.addNewPrice = function () {
            $scope.prices.push(angular.copy($scope.priceModal));
        };

        $scope.initProductDropDown = function (el) {
            el.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: $scope.urls.product + '/{query}',
                    cache: false
                },
                onChange: function (val, name, elem) {
                    if(elem){
                        var parentElm = elem.parent().parent();
                        var index = parentElm.data('index');
                        $scope.prices[index].product = val;
                        $scope.prices[index].product_name = name;
                    }
                }
            });
        };

        if ($scope.oldData.hasOwnProperty('products') && $scope.oldData['products'].length) {
            var products = $scope.oldData.products;
            var product_name = $scope.oldData.product_name;
            var amount = $scope.oldData.amount;
            var rangeStartFrom = $scope.oldData.range_start_from;
            var rangeEndTo = $scope.oldData.range_end_to;
            var ids = $scope.oldData.ids;
            $.each(products, function (index) {
                var newObject = angular.copy($scope.priceModal);
                newObject.product = products[index];
                newObject.rangeStartFrom = rangeStartFrom[index];
                newObject.rangeEndTo = rangeEndTo[index];
                newObject.amount = amount[index];
                newObject.id = ids[index];
                newObject.product_name = product_name[index];
                $scope.prices.push(newObject);
            });
        } else {
            $scope.addNewPrice();
        }

        $scope.setDataDropDown = function (elem, value) {
            if (value) {
                $scope.setDropDownValue(elem, value.product, value.product_name)
            } else {
                if ($scope.oldData.hasOwnProperty('products')) {
                    products = $scope.oldData.products;
                    productNames = $scope.oldData.product_name;
                    $.each(products, function (index, value) {
                        if (value && productNames[index]) {
                            $scope.setDropDownValue($('.product-drop-down[data-index="' + index + '"]'), value, productNames[index])
                        }
                    });
                }
            }
        };

        $scope.setDropDownValue = function (dd, value, name) {
            dd.dropdown("refresh");
            dd.dropdown('set value', value);
            dd.dropdown('set text', name);
        };

        $scope.errorMessage = function (elem, error) {
            elem.parent().addClass('has-danger')
        };

        $scope.mapError = function (errors) {
            var MappedErrors = {};
            $.map(errors, function (values, field) {
                var filedData = field.split(".");
                if (filedData.hasOwnProperty('0') && filedData.hasOwnProperty('1') && values.hasOwnProperty('0')) {
                    var $name = filedData[0];
                    var $index = filedData[1];
                    var $value = values[0].replace(/_/g, ' ').replace('.' + filedData[1], '').replace('item', '');
                    if ($name === 'products') {
                        $scope.errorMessage($('.product-drop-down[data-index="' + $index + '"]'), $value);
                    } else if ($name === 'amount') {
                        $scope.errorMessage($('.amount[data-index="' + $index + '"]'), $value);
                    } else if ($name === 'range_start_from') {
                        $scope.errorMessage($('.start-range[data-index="' + $index + '"]'), $value);
                    } else if ($name === 'range_end_to') {
                        $scope.errorMessage($('.end-range[data-index="' + $index + '"]'), $value);
                    }
                }
            });
            return MappedErrors;
        };

        $scope.removePrice = function (index) {
            $scope.prices.splice(index, 1);
        }

    }]).directive('productLoop', function () {
        return function (scope, element, attrs) {
            if (!_.toArray(scope.oldData).length && scope.price.product && scope.price.product_name) {
                scope.initProductDropDown($('.product-drop-down'));
                scope.setDataDropDown(element.find('.product-drop-down'), scope.price);
            }
            if (scope.$last) {
                scope.initProductDropDown($('.product-drop-down'));
                setTimeout(function () {
                    scope.setDataDropDown();
                    scope.mapError(scope.errors);
                }, 500);
            }
        }
    });
</script>
