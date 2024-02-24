<script>
    app.controller('InvoiceController', function ($scope, $http) {
        $scope.el = {
            sidebar: $('#invoice-sidebar'),
            loader: $('.cus-create-preloader'),
            btn: $('.invoice-sidebar-btn'),
            typeDD: $('.payment-type-dropdown'),
            accountDD: $('.account-drop-down'),
        };
        $scope.errors = [];
        $scope.totalPayment = 0;
        $scope.paymentType = [
            {
                name: 'Advanced'
            },
            {
                name: 'Partial Payment'
            },
            {
                name: 'Final Payment'
            }
        ];
        $scope.payment = {
            payment_type: null,
            payment: '',
            payment_date: '',
            id: null,
            deposited_to: null,
        };
        $scope.cusSlider = $scope.el.sidebar.slideReveal({
            trigger: $scope.el.btn,
            position: "right",
            width: '400px',
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

            },
            hide: function () {
                $scope.errors = [];
                $scope.payment = {
                    payment_type: null,
                    payment: '',
                    payment_date: '',
                    id: null,
                    deposited_to: null,
                };
            }
        });
        $scope.paymentEditRoute = '{{ route('sales.payment.edit', ['payment' => 'ID']) }}';
        $scope.editPayment = function ($event) {
            var elem = $($event.currentTarget);
            var paymentId = elem.data('id');
            $http.get($scope.paymentEditRoute.replace('ID', paymentId)).then(function (response) {
                var data = response.data;
                $scope.totalPayment = data.payment;
                $scope.payment = {
                    payment_type: data.payment_type,
                    payment: data.payment,
                    payment_date: data.payment_date,
                    deposited_to: data.deposited_to,
                    id: data.id,
                    total: data.payment
                };
                $scope.el.typeDD.dropdown('set text', $scope.payment.payment_type).dropdown('set value', $scope.payment.payment_type);
                $scope.el.accountDD.dropdown('set text', data.deposited_to.name).dropdown('set value', $scope.payment.deposited_to);
            })
        };
        $scope.paymentUpdateRoute = '{{ route('sales.payment.credit.update', ['payment' => 'ID']) }}';
        $scope.updatePayment = function () {
            $scope.payment.total = $scope.payment.payment;
            $http.patch($scope.paymentUpdateRoute.replace('ID', $scope.payment.id), $scope.payment).then(function (response) {
                swal({
                    title: 'Success',
                    text: "Used Credits successfully updated!",
                    type: 'success',
                    showCancelButton: false
                }).then(function () {
                    window.location.reload();
                })
            }).catch(function (error) {
                if (error.hasOwnProperty('data') && error.data.hasOwnProperty('errors')) {
                    $scope.errors = [];
                    $scope.mapErrors(error.data.errors);
                }
            })
        };

        $scope.mapErrors = function (errors) {
            $.map(errors, function (values, field) {
                if (values.hasOwnProperty('0')) {
                    $scope.errors[field] = values[0];
                }
            });
            if (!$scope.$$phase) $scope.$apply()
        };

        $scope.hasError = function (name) {
            if ($scope.errors.hasOwnProperty(name)) {
                if ($scope.errors[name]) {
                    return true;
                }
            }
            return false;
        };

        $scope.getErrorMsg = function (name) {
            if ($scope.errors.hasOwnProperty(name)) {
                return $scope.errors[name];
            }
            return '';
        };
        $scope.el.typeDD.dropdown('setting', {
            onChange: function (val) {
                $scope.payment.payment_type = val;
            }
        });
        $scope.el.accountDD.dropdown('setting', {
            onChange: function (val) {
                console.log(deposited_to);
                $scope.payment.deposited_to = val;
            }
        });
        $scope.closeSideBar = function () {
            $scope.cusSlider.slideReveal("toggle");
        };

        $scope.getCreditRemain = function () {
            var creditUsed = '{{ getCustomerCreditUsed($credit) }}';
            var creditAmount = '{{ $credit->amount }}';
            var amount = creditAmount - creditUsed;
            return parseInt(amount) + parseInt($scope.totalPayment);
        };
        $scope.paymentDeleteRoute = '{{ route('sales.payment.delete', ['payment' => 'ID']) }}';

        $scope.deletePayment = function ($event) {
            var elem = $($event.currentTarget);
            var paymentId = elem.data('id');
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DB2828',
                confirmButtonText: 'Yes, Delete!'
            }).then(function (result) {
                if (result.value) {
                    $http.delete($scope.paymentDeleteRoute.replace('ID', paymentId)).then(function (response) {
                        if (response.data) {
                            swal({
                                title: 'Success',
                                text: "Used Credits successfully deleted!",
                                type: 'success',
                                showCancelButton: false
                            }).then(function () {
                                window.location.reload();
                            })
                        }
                    }).catch(function (error) {
                        if (error.hasOwnProperty('data') && error.data.hasOwnProperty('errors')) {
                            $scope.errors = [];
                            $scope.mapErrors(error.data.errors);
                        }
                    });
                }
            });
        }
    });
</script>