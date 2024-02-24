<script>
    app.controller('CashSalesController', function ($scope, $timeout, $http) {
        var dropDown = $('.drop-down');
        dropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });
        $scope.products = [];
        $scope.payment = {
            payment_mode: $('.payment-mode').val(),
        };
        $scope.received = '';
        $scope.change = 0.00;
        $scope.paymentModel = {
            "id": null,
            "payment": null,
            "payment_date": '2018-06-13',
            "payment_type": null,
            "payment_mode": null,
            "cheque_no": null,
            "cheque_date": null,
            "account_no": null,
            "deposited_date": null,
            "card_holder_name": null,
            "card_no": null,
            "expiry_date": null,
        };

        $scope.dropdowns = {
            itemDropDown: $('.item-drop-down')
        };

        $scope.edit = false;
        // Product dropDown Change Function
        $scope.productRoute = '{{ route('cash.sales.get.product', ['allocation' => $allocation, 'product' => 'ID']) }}';
        $scope.handleItemDDChange = function (val, callback) {
            if (!val) return;
            $http.get($scope.productRoute.replace('ID', val)).then(function (response) {
                var product = response.data;
                product.amount = null;
                //Find This product Already attached
                let name = '.' + product.id + '-qty';
                var old = _.find($scope.products, function (k, v) {
                    return parseInt(k.id) === parseInt(product.id);
                });
                if (!old) {
                    product['qty'] = 1;
                    $scope.products.push(product);
                } else {
                    var index = _.findLastIndex($scope.products, {
                        id: product.id
                    });
                    if($scope.products.hasOwnProperty(index)){
                        if ($scope.products[index].hasOwnProperty('qty')){
                            var qty = $scope.products[index].qty;
                            $scope.products[index]['qty'] = qty + 1;
                        }
                    }
                }
                var productIndex = _.findLastIndex($scope.products, {
                    id: product.id
                });
                if($scope.products.hasOwnProperty(productIndex)){
                    $scope.getProductTotalAmount($scope.products[productIndex]);
                }

                if (typeof callback === 'function'){
                    callback();
                }
            });
            $scope.dropdowns.itemDropDown.dropdown('clear');
        };

        // Product DropDown
        $scope.dropdowns.itemDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            selectOnKeydown: false,
            showOnFocus: true,
            apiSettings: {
                url: '{{ route('cash.sales.product.search') }}/{query}',
                cache: false,
            },
            onChange: $scope.handleItemDDChange
        });

        /**
         * Check box Change
         */
        $('[name="payment_mode"]').click(function () {
            $scope.payment.payment_mode = $(this).val();
            if (!$scope.$$phase) $scope.$apply();
        });

        //Get Product Amount
        $scope.getProductTotalAmount = function (product) {
            var key = $scope.getObjectKeyByValue($scope.products, 'id', product.id);
            $scope.products[key].amount = (product.qty * product.selling_price);
        };

        //Get Object key Using value
        $scope.getObjectKeyByValue = function (object, index, value) {
            return Object.keys(object).find(key => object[key][index] === value);
        };

        // Get Total of given Array
        $scope.sum = function (array) {
            var count = 0;
            $.each(array, function (k, v) {
                if (v) {
                    count = count + parseInt(v);
                }
            });
            return count;
        };

        //Get All Product Total
        $scope.getTotal = function () {
            return $scope.sum(_.pluck($scope.products, 'amount'));
        };

        $scope.removeProduct = function (index) {
            $scope.products = $scope.removeByKey($scope.products, index);
        };

        $scope.removeByKey = function (array, index) {
            if (array.hasOwnProperty(index)) {
                array.splice(index, 1);
            }
            return array;
        };

        $scope.oldData = [];
        @if(old('_token'));
        $scope.oldProducts = @json(old('sales_items'));

        $scope.oldData = @json(old());

        $.each($scope.oldProducts, function (k, v) {
            $scope.products.push(v);
        });

        $scope.salesOrder = '{{ old('salesOrder') }}';

        if ($scope.salesOrder) {
            $scope.edit = true;
        }

        if (!$scope.$$phase) $scope.$apply();

        $scope.payment.payment_mode = '{{ old('payment_mode') }}'
        @endif;

        $scope.errors = @json($errors->toArray() ?? []);

        $scope.hasError = function (name) {
            if ($scope.errors && $scope.errors.hasOwnProperty(name)) {
                if ($scope.errors[name]) {
                    return true;
                }
            }
            return false;
        };

        // check has error
        $scope.getErrorMsg = function (name) {
            if ($scope.errors && $scope.errors.hasOwnProperty(name)) {
                return $scope.errors[name][0];
            }
            return '';
        };

        $scope.handleEditBtn = function (event) {
            $('html, body').animate({
                scrollTop: $('body').offset().top
            }, 1000);
            $scope.salesOrder = $(event.target).data('id');
            $scope.edit = true;
            var route = '{{ route('sales.order.show', ['ID']) }}';
            $http.get(route.replace('ID', $scope.salesOrder)).then(function (response) {
                var data = response.data.orders;
                var payment = response.data.payments;
                $scope.addPaymentData(payment);
                $.each(data.products, function (k, product) {
                    product.qty = product.pivot.quantity;
                    product.amount = product.pivot.amount;
                    $scope.products.push(product);
                });
            });
            if (!$scope.$$phase) $scope.$apply();
        };

        $scope.handleCancelClick = function () {
            $scope.salesOrder = null;
            $scope.edit = false;
            $scope.products = [];
            $scope.resetPaymentForm();
            if (!$scope.$$phase) $scope.$apply();
        };

        $scope.addPaymentData = function (payment) {
            $scope.payment.payment_mode = payment.payment_mode;
            $.each($('.payment-mode'), function (k, v) {
                if ($(v).val() === $scope.payment.payment_mode) {
                    $(v).prop('checked', true);
                }
            });
            $.each(payment, function (k, v) {
                var name = '#' + k;
                $(name).val(v);
            });
            if (payment.bank) {
                $('.bank-drop-down').dropdown('set text', payment.bank.name).dropdown('set value', payment.bank.id);
            }
        };

        $scope.resetPaymentForm = function () {
            $scope.payment.payment_mode = 'Cash';
            $.each($('.payment-mode'), function (k, v) {
                if ($(v).val() === $scope.payment.payment_mode) {
                    $(v).prop('checked', true);
                }
            });
            $.each($scope.paymentModel, function (k, v) {
                var name = '#' + k;
                $(name).val(v);
            });
            $('.bank-drop-down').dropdown('clear');
        };

        //List Down

        $scope.orders = [];
        $scope.filterd = false;
        $scope.loading = true;
        $scope.pagination = {};
        $scope.currentPaginationPage = 0;
        $scope.query = {
            ajax: true,
            page: null,
            filter: null,
            search: null,
            from_date: '{{carbon()->toDateString()}}',
            to_date: '{{carbon()->toDateString()}}',
        };
        $scope.filterUpdated = function () {
            $scope.filterd = true;
            $scope.query.search = $scope.searchOrders;
            $scope.fetchOrders();
        };

        $scope.range = function () {
            var rangeSize = 10;
            $scope.pages = [];
            var start;
            var ret = [];
            if ($scope.pagination.total < 10) {
                rangeSize = $scope.pagination.total
            }
            start = $scope.currentPaginationPage > 5 ? $scope.currentPaginationPage - 5 : 0;
            if (start > $scope.pageCount() - rangeSize) {
                start = $scope.pageCount() - rangeSize + 1;
            }

            for (var i = start; i < start + rangeSize; i++) {
                if (i < 0) continue;
                if (i >= $scope.pagination.last_page) continue;
                ret.push(i);
                $scope.pages.push(i);
            }
            return ret;
        };

        $scope.prevPage = function () {
            if ($scope.currentPaginationPage > 0) {
                $scope.currentPaginationPage--;
            }
            $scope.paginationChanged()
        };

        $scope.prevPageDisabled = function () {
            return $scope.currentPaginationPage === 0 ? "disabled" : "";
        };

        $scope.pageCount = function () {
            return $scope.pagination.last_page - 1;
        };

        $scope.nextPage = function () {
            if ($scope.currentPaginationPage < $scope.pageCount()) {
                $scope.currentPaginationPage++;
            }
            $scope.paginationChanged()
        };

        $scope.nextPageDisabled = function () {
            return $scope.currentPaginationPage === $scope.pageCount() ? "disabled" : "";
        };

        $scope.setPage = function (n) {
            if ($scope.pagination.current_page === n + 1) return;
            $scope.currentPaginationPage = n;
            $scope.paginationChanged()
        };

        $scope.paginationChanged = function () {
            $scope.fetchOrders();
        };
        var moduleRoute = '{{ route('cash.sales.index') }}';
        $scope.fetchOrders = function () {
            $scope.loading = true;
            $scope.query.page = $scope.currentPaginationPage + 1;
            var queryRoute = $.param($scope.query);
            $http.get(moduleRoute + '?' + queryRoute).then(function (response) {
                $scope.loading = false;
                $scope.orders = response.data.data;
                $scope.pagination = response.data;
                $scope.total = response.data.total;
                $scope.range();
            });
        };
        $scope.fetchOrders();


        $scope.cancelBtnClick = function (order) {
            $scope.cancelUrl = '{{ route('cash.sales.cancel', ['order' => 'ID']) }}';
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DB2828',
                confirmButtonText: 'Yes, Cancel!'
            }).then(function (isConfirm) {
                if (isConfirm.value) {
                    $.get($scope.cancelUrl.replace('ID', order)).then(function (response) {
                        swal(
                            'Canceled!',
                            'Cash sales canceled successfully!',
                            'success'
                        );
                        $scope.fetchOrders();
                    });
                }
            });
        };

        if ($scope.oldData) {
            if ($scope.oldData.received) {
                $scope.received = $scope.oldData.received;
            }
        }

        $(document).keydown(function (event) {
                if (event.which === 112) {
                    event.preventDefault();
                    $('.item-drop-down input').focus();
                    return false;
                }
                if (event.which === 113) {
                    event.preventDefault();
                    $('.customer-drop-down input').focus();
                    return false;
                }
            }
        );
        var productBarcodeUrl = "{{ route('cash.sales.barcode.product') }}";
        $('#barcode-input').scannerDetection({
            timeBeforeScanTest: 200,
            avgTimeByChar: 40,
            endChar: [13],
            onComplete: function(barcode, qty) {
                $('.item-drop-down input.search').val('').focus();
                $scope.dropdowns.itemDropDown.dropdown('clear');
                $http.post(productBarcodeUrl, {
                    '_token': '',
                    'barcode': barcode
                }).then(function (response) {
                    var product = response.data;
                    if(product.hasOwnProperty('id')){
                        $scope.handleItemDDChange(product.id);
                    }else{
                        $scope.barcodeError = 'Product not founded!';
                    }
                });
                setTimeout(function () {
                    $scope.dropdowns.itemDropDown.dropdown('hide');
                }, 300);
            }
          });
    }).directive('productDirective', function () {
        return function (scope, element, attrs) {
            // focus qty input
            // element.find('.item-qty').focus();
        };
    });
</script>
