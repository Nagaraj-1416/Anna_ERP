<script src="{{ asset('js/vendor/slidereveal.js') }}"></script>
<script>
    app.controller('RefundController', function ($scope, $http) {
        // refund model
        $scope.refund = {
            payment_mode: null,
            refunded_on: null,
            cheque_no: null,
            notes: null,
            amount: null,
            refunded_from: null,
            cheque_date: null,
            bank_id: null,
            account_no: null,
            deposited_date: null
        };
        $scope.edit = false;
        $scope.paymentMode = [
            {name: 'Cash', val: 'Cash'},
            {name: 'Cheque', val: 'Cheque'},
            {name: 'Direct Deposit', val: 'Direct Deposit'},
            {name: 'Credit Card', val: 'Credit Card'}
        ];
        $scope.refundedOn = @json(depositedToAccDropDown());
        $scope.banks = @json(bankDropDown());
        //Error object
        $scope.errors = [];

        // Related elements
        $scope.el = {
            btn: $('.sidebar-btn'),
            sidebar: $('#refund-sidebar'),
            loader: $('.cus-create-preloader'),
            paymentModeDD: $('.payment-mode-dropdown'),
            refundedFromDD: $('.refunded-from-dropdown'),
            chequeBankDD: $('.cheque-bank-dropdown')
        };

        // When click the add button open the model
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
                $scope.hideLoader();
                $scope.resetForm();
                $scope.edit = false;
                if (!$scope.$$phase) $scope.$apply()
            }
        });
        // close side bar
        $scope.closeSideBar = function () {
            $scope.cusSlider.slideReveal("toggle");
        };


        $scope.resetForm = function () {
            $scope.refund = {
                payment_mode: '',
                refunded_on: '',
                cheque_no: '',
                notes: '',
                amount: '',
                refunded_from: '',
                cheque_date: '',
                bank_id: '',
                account_no: '',
                deposited_date: ''
            };
            $scope.el.paymentModeDD.dropdown('clear');
            $scope.el.refundedFromDD.dropdown('clear');
            $scope.el.chequeBankDD.dropdown('clear');
            //Error object
            $scope.errors = [];
            if (!$scope.$$phase) $scope.$apply();
        };

        //save refund
        $scope.saveRefund = function () {
            $scope.showLoader();
            if (!$scope.edit) {
                $scope.storeRefund();
            } else {
                $scope.updateRefund();
            }
        };

        // store refund
        $scope.refundStoreRoute = '{{ route('sales.credit.refund.save', ['credit' => $credit]) }}';
        $scope.storeRefund = function () {
            $http.post($scope.refundStoreRoute, $scope.refund).then(function (response) {
                if (response.data) {
                    swal({
                        title: 'Success',
                        text: "Refund successfully created!",
                        type: 'success',
                        showCancelButton: false
                    }).then(function () {
                        window.location.reload();
                    })
                }
                $scope.hideLoader();
                $scope.closeSideBar();
            }).catch(function (error) {
                if (error.hasOwnProperty('data') && error.data.hasOwnProperty('errors')) {
                    $scope.errors = [];
                    $scope.mapErrors(error.data.errors);
                }
                $scope.hideLoader();
            });
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
        $scope.hasError = function (name) {
            if ($scope.errors.hasOwnProperty(name)) {
                if ($scope.errors[name]) {
                    return true;
                }
            }
            return false;
        };

        // check has error
        $scope.getErrorMsg = function (name) {
            if ($scope.errors.hasOwnProperty(name)) {
                return $scope.errors[name];
            }
            return '';
        };

        // show loader
        $scope.el.loader.addClass('hidden');
        $scope.showLoader = function () {
            $scope.el.loader.addClass('loading');
            $scope.el.loader.removeClass('hidden');
        };
        $scope.clearRelativeFields = function () {
            if (!$scope.edit) {
                $scope.refund.cheque_no = '';
                $scope.refund.refunded_from = '';
                $scope.refund.cheque_date = '';
                $scope.refund.bank_id = '';
                $scope.refund.account_no = '';
                $scope.refund.deposited_date = '';
                $scope.el.chequeBankDD.dropdown('clear');
            }
        };
        // hide loading
        $scope.hideLoader = function () {
            $scope.el.loader.removeClass('loading');
            $scope.el.loader.addClass('hidden');
        };
        $scope.el.paymentModeDD.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            onChange: function (val) {
                $scope.clearRelativeFields();
                $scope.refund.payment_mode = val;
                if (!$scope.$$phase) $scope.$apply();
            }
        }).dropdown('set text', 'Cash').dropdown('set value', 'Cash');

        $scope.el.refundedFromDD.dropdown('setting', {
            onChange: function (val) {
                $scope.refund.refunded_from = val;
                if (!$scope.$$phase) $scope.$apply()
            }
        });

        $scope.el.chequeBankDD.dropdown('setting', {
            onChange: function (val) {
                $scope.refund.bank_id = val;
                if (!$scope.$$phase) $scope.$apply()
            }
        });
        /** set values to semantic UI drop-down */
        $scope.setDropDownValue = function (dd, value, name) {
            dd.dropdown("refresh");
            dd.dropdown('set value', value);
            dd.dropdown('set text', name);
        };

        $scope.refundEditRoute = '{{ route('sales.credit.refund.edit', ['credit' => $credit, 'refund' => 'ID']) }}';
        $scope.editRefund = function ($event) {
            $scope.edit = true;
            var elem = $($event.currentTarget);
            var refundId = elem.data('id');
            $http.get($scope.refundEditRoute.replace('ID', refundId)).then(function (response) {
                $scope.refund = response.data.refund;
                $scope.amount = $scope.refund.amount;
                $scope.setDropDownValue($scope.el.paymentModeDD, $scope.refund.payment_mode, $scope.refund.payment_mode);

                if ($scope.refund.account) {
                    $scope.setDropDownValue($scope.el.refundedFromDD, $scope.refund.refunded_from, $scope.refund.account.name);
                }
                if ($scope.refund.bank) {
                    $scope.setDropDownValue($scope.el.chequeBankDD, $scope.refund.bank_id, $scope.refund.bank.name);
                }
            })
        };
        $scope.refundUpdateRoute = '{{ route('sales.credit.refund.update', ['credit' => $credit, 'refund' => 'ID']) }}';
        $scope.updateRefund = function ($event) {
            $http.patch($scope.refundUpdateRoute.replace('ID', $scope.refund.id), $scope.refund).then(function (response) {
                if (response.data) {
                    swal({
                        title: 'Success',
                        text: "Refund successfully updated!",
                        type: 'success',
                        showCancelButton: false,
                    }).then(function () {
                        window.location.reload();
                    })
                }
                $scope.hideLoader();
                $scope.closeSideBar();

            }).catch(function (error) {
                if (error.hasOwnProperty('data') && error.data.hasOwnProperty('errors')) {
                    $scope.errors = [];
                    $scope.mapErrors(error.data.errors);
                }
                $scope.hideLoader();
            });
        };


        $scope.refundDeleteRoute = '{{ route('sales.credit.refund.delete', ['credit' => $credit, 'refund' => 'ID']) }}';
        $scope.deleteRefund = function ($event) {
            var id = $($event.currentTarget).data('id');
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DB2828',
                confirmButtonText: 'Yes, Delete!'
            }).then(function (result) {
                if(result.value){
                    $http.delete($scope.refundDeleteRoute.replace('ID', id)).then(function (response) {
                        if (response.data) {
                            swal({
                                title: 'Success',
                                text: "Refund successfully deleted!",
                                type: 'success',
                                showCancelButton: false,
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
        };

        $scope.getCreditRemain = function () {
            var limit = '{{ getCustomerCreditLimit($credit) }}';
            if ($scope.edit) {
                limit = parseInt(limit) + parseInt($scope.amount);
            }
            return limit;
        }
    });
</script>