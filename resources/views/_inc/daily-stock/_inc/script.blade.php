<script src="{{ asset('js/vendor/slidereveal.js') }}"></script>
<script>
    app.controller('DailyStockProductsController', function ($scope, $http) {
        // expenseCategory model
        $scope.edit = false;
        //Error object
        $scope.errors = [];
        $scope.dailyStockId = null;
        $scope.products = [];
        $scope.issued = [];
        // Related elements
        $scope.el = {
            'btn': $('#daily-stock-add-btn'),
            'sidebar': $('#daily-stock-sidebar'),
            'loader': $('.expense-cat-create-preloader'),
        };

        // When click the add button open the model
        $scope.dailyStockSlider = $scope.el.sidebar.slideReveal({
            trigger: $scope.el.btn,
            position: "right",
            width: '1200px',
            push: false,
            overlay: true,
            shown: function (slider, trigger) {
                // init scroll for side bar body
                $('#daily-stock').slimScroll({
                    color: 'gray',
                    height: '100%',
                    railVisible: true,
                    alwaysVisible: false
                });
            },
            show: function (slider, trigger) {
                $scope.hideLoader();
                $scope.resetForm();
            },
        });

        var dailyStockRoute = '{{ route('daily.stock.get.items', ['dailyStock' => 'ID']) }}';
        $scope.el.btn.click(function () {
            $scope.dailyStockId = $(this).data('id');
            $scope.getData($scope.dailyStockId);
        });


        $scope.getData = function (dailyStockId, show) {
            $scope.showLoader();
            $scope.dailyStockId = dailyStockId;
            if (dailyStockId) {
                $http.get(dailyStockRoute.replace('ID', dailyStockId)).then(function (response) {
                    $scope.products = response.data;
                    $scope.hideLoader();
                    if (show) {
                        $scope.el.sidebar.slideReveal('show');
                    }
                });
            }
        };
        // close side bar
        $scope.closeSideBar = function () {
            $scope.el.sidebar.slideReveal("toggle");
        };

        $scope.resetForm = function () {
            //Error object
            $scope.errors = [];
            if (!$scope.$$phase) $scope.$apply();
        };

        // mapping errors
        $scope.mapErrors = function (errors) {
            $.map(errors, function (values, field) {
                if (values.hasOwnProperty('0')) {
                    $scope.errors[field] = values[0];
                }
            });
        };

        // check has error
        $scope.hasError = function (name, index) {
            if ($scope.errors.hasOwnProperty(`${name}.${index}`)) {
                if ($scope.errors[`${name}.${index}`]) {
                    return true;
                }
            }
            return false;
        };

        // check has error
        $scope.getErrorMsg = function (name, index) {
            if ($scope.errors.hasOwnProperty(`${name}.${index}`)) {
                return $scope.errors[`${name}.${index}`][0];
            }
            return '';
        };

        // show loader
        $scope.el.loader.addClass('hidden');
        $scope.showLoader = function () {
            $scope.el.loader.addClass('loading');
            $scope.el.loader.removeClass('hidden');
        };

        // hide loading
        $scope.hideLoader = function () {
            $scope.el.loader.removeClass('loading');
            $scope.el.loader.addClass('hidden');
        };

        $scope.listDailyStockData = function (dailyStock) {
            $scope.getData(dailyStock.id, true);
            if (dailyStock.status === 'Pending') {
                $scope.edit = true;
            }
        };

        $scope.saveDailyStockIssuedQty = function () {
            var $postRoute = '{{ route('daily.stock.update.items', ['dailyStock' => 'ID']) }}';
            $http.post($postRoute.replace('ID', $scope.dailyStockId), {data: $scope.issued}).then(function (response) {
                window.location.reload();
            }).catch(function (error) {
                $scope.errors = error.data.errors;
            });
        };


        $scope.getStatusColor = function (status) {
            if (status === 'Allocated') {
                return 'td-bg-success';
            } else if (status === 'Canceled') {
                return 'td-bg-danger';
            }
        };

        $scope.updatePendingQty = function (product) {
            var issued = $scope.issued[product.id] ? $scope.issued[product.id] : 0;
            if (parseInt(issued) || parseInt(issued) === 0) {
                var pending = product.required_qty - parseInt(issued);
                if (pending >= 0) {
                    product.pending_qty = pending;
                } else {
                    product.pending_qty = 0;
                }
            }
        };

        $scope.getIssuedProduct = function () {
            $scope.count = 0;
            $.each($scope.issued, function (index, value) {
                if (value && value != 0) {
                    $scope.count += 1;
                }
            });
            return $scope.count;
        }

    }).directive('productDirective', function () {
        return function (scope, element, attrs) {
            if (scope.product.issued_qty) {
                scope.issued[scope.product.id] = scope.product.issued_qty;
            }
            scope.updatePendingQty(scope.product);
        };
    });
</script>