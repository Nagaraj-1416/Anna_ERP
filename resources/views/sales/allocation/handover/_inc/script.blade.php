<script src="{{ asset('js/vendor/form.js') }}"></script>
<script src="{{ asset('js/vendor/object-helper.js') }}"></script>
<script>
    app.controller('HandoverController', ['$scope', '$http', function ($scope, $http) {
        //Empty cash collection model
        $scope.cash = {
            type: null,
            count: null,
            total: 0
        };

        $scope.cheques = [];
        $scope.expenses = [];
        $scope.chequesData = @json($cheques->toArray());
        //$scope vars
        $scope.totalCash = 0;
        $scope.isRemoveable = 0;
        $scope.cashCollection = [];
        $scope.errors = [];
        $scope.total_collect = '{{ $handover->total_collect }}';
        $scope.cash_collection = '{{ $handover->cash_sales + $handover->old_cash_sales + $handover->rc_cash }}';
        $scope.refundedAmount = '{{ $refundedAmount }}';
        $scope.cheque_sales = '{{ $handover->cheque_sales + $handover->old_cheque_sales + $handover->rc_cheque }}';
        $scope.oldChequeAmount = '{{ $handover->cheque_sales + $handover->old_cheque_sales + $handover->rc_cheque }}';
        @if(old('shortage'))
            $scope.shortage = '{{ old('shortage') }}';
        @else
            $scope.shortage = 0;
        @endif;
        @if(old('excess'))
            $scope.excess = '{{ old('excess') }}';
        @else
            $scope.excess = 0;
        @endif;
        $scope.allowance = '{{ $handover->allowance }}';
        $scope.total_expense = '{{ $handover->total_expense }}';

        $scope.getBalance = function () {
            $scope.balance = (parseFloat($scope.cash_collection) + parseFloat($scope.excess)) - (parseFloat($scope.total_expense) + parseFloat($scope.refundedAmount) + parseFloat($scope.shortage))
        };

        $scope.getFullBalance = function () {
            return (parseFloat($scope.cash_collection)) - (parseFloat($scope.total_expense) + parseFloat($scope.refundedAmount));
        };

        $scope.getBalance();

        $scope.shortages = {
            'None': 'None', 'Damaged': 'Damaged', 'Lost': 'Lost', 'Invalid': 'Invalid', 'Other': 'Other'
        };
        $scope.chequeTypes = {
            'Own': 'Own', 'Third Party': 'Third Party'
        };
        // Get Old values as json
        $scope.oldValues = @json(old());
        $scope.formErrors = @json($errors->toArray());
        $scope.banks = @json(bankDropDown());
        //Add a New Cash Detail function
        $scope.addMoreCash = function (data, index) {
            var object = angular.copy($scope.cash);
            if (data) {
                object.type = data.type;
                object.count = data.count;
                object.total = data.total;
            }
            $scope.cashCollection.push(object);
            $scope.isRemoveable++;
            if (typeof index === 'number') {
                $scope.handleCashUpdated(index);
            }
        };

        //Get Cash Total amount
        $scope.total_cash = '{{ $handover->cash_sales + $handover->old_cash_sales + $handover->rc_cash }}';
        $scope.shortageBalance = 0;

        $scope.getShortageAmount = function () {
            $scope.getBalance();
            if (parseFloat($scope.getFullBalance()) >= parseFloat($scope.totalCash)) {
                $scope.shortage = parseFloat($scope.getFullBalance()) - parseFloat($scope.totalCash);
                $scope.excess = 0;
            } else {
                $scope.shortage = 0;
                $scope.excess = parseFloat($scope.totalCash) - parseFloat($scope.getFullBalance());
            }
            $scope.getBalance();
            $scope.total_collect = parseFloat($scope.cash_collection) + parseFloat($scope.cheque_sales);
        };

        $scope.handleCashUpdated = function (key) {
            $scope.totalCash = 0;
            if(key || key === 0){
                $scope.cashCollection[key].total = (parseFloat($scope.cashCollection[key].type) * parseFloat($scope.cashCollection[key].count));
            }
            $.each($scope.cashCollection, function (index, value) {
                $scope.hasDuplicate(index);
                $scope.totalCash += parseFloat(value.total);
            });
            if ($scope.totalCash) {
                $scope.getShortageAmount();
            }
            if (!$scope.$$phase) $scope.$apply();
        };

        $scope.sum = function (array) {
            return _.reduce(array, function (memo, num) {
                return parseFloat(memo) + parseFloat(num);
            }, 0);
        };

        $scope.updateChequePayment = function () {
            $scope.cheque_sales = 0;
            $.each($scope.chequeAmounts, function (k, v) {
                if (parseFloat(v)) {
                    $scope.cheque_sales += parseFloat(v);
                }
            });

            $scope.total_collect = parseFloat($scope.cash_collection) + parseFloat($scope.cheque_sales);
        };

        $scope.chequeShortage = {};

        //Remove Cash Detail
        $scope.removeCash = function (index) {
            $scope.cashCollection = $scope.removeByKey($scope.cashCollection, index);
            $scope.isRemoveable = $scope.cashCollection.length;
            $scope.handleCashUpdated();
            $scope.getShortageAmount();
        };

        //Remove Array Data using key
        $scope.removeByKey = function (array, index) {
            if (array.hasOwnProperty(index)) {
                array.splice(index, 1);
            }
            return array;
        };

        //Cash Rupee type duplication check
        $scope.hasDuplicate = function (key) {
            var type = $scope.cashCollection[key].type;
            var status = false;
            $.each($scope.cashCollection, function (index, value) {
                if (index !== key && !status) {
                    status = value.type === type;
                }
            });
            $scope.errors[key] = status;
            return status;
        };

        //Old value setup
        if ($scope.oldValues.cashCollection && $scope.oldValues.cashCollection.length) {
            $.each($scope.oldValues.cashCollection, function (index, value) {
                $scope.addMoreCash(value, index);
            });
        } else {
            $scope.addMoreCash();
        }

        $scope.hasError = function (name, key, message) {
            if (!key && $scope.formErrors.hasOwnProperty(name) && message) {
                return $scope.formErrors[name][0];
            }
            if (!key && key !== 0) {
                if ($scope.formErrors.hasOwnProperty(name) || $scope.errors[key]) {
                    if (message && $scope.formErrors[name] && $scope.formErrors[name].hasOwnProperty(0)) {
                        return $scope.formErrors[name][0]
                    }
                    return 'error';
                }
            } else {
                if ($scope.formErrors.hasOwnProperty(name + '.' + key) || $scope.errors[key]) {
                    if (message && $scope.formErrors[name + '.' + key] && $scope.formErrors[name + '.' + key].hasOwnProperty(0)) {
                        return $scope.formErrors[name + '.' + key][0]
                    }
                    return 'error';
                }
            }
            return '';
        };

        $scope.handleProductCheckAll = function ($event) {
            var productCheck = $('.product-check');
            if ($($event.target).is(':checked')) {
                productCheck.prop('checked', true);
            } else {
                productCheck.prop('checked', false);
            }
        };

        $scope.handleShortageChange = function (val) {
            if (val !== 'None') {
                var chequeAmount = $(this).parent().parent().parent().find('.amount-p').data('amount');
                if (val) {
                    $scope.chequeShortage[$(this).data('id')] = chequeAmount;
                }
                if (!$scope.$$phase) $scope.$apply();
            } else {
                if ($scope.chequeShortage.hasOwnProperty($(this).data('id'))) {
                    delete $scope.chequeShortage[$(this).data('id')];
                }
            }
            $scope.getChequeAmount();
        };

        $scope.getChequeAmount = function () {
            $scope.cheque_sales = '{{ $handover->cheque_sales + $handover->old_cheque_sales + $handover->rc_cheque }}';
            $scope.cheque_sales = parseFloat($scope.cheque_sales) - $scope.sum($scope.chequeShortage);
            $scope.total_collect = parseFloat($scope.cash_collection) + parseFloat($scope.cheque_sales);
            if (!$scope.$$phase) $scope.$apply();
        };


        $('.shortage-dropdown').dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            onChange: $scope.handleShortageChange
        });
        $('.cheque-type-dropdown').dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });

        $scope.bankDD = $('.bank-dropdown');
        $scope.bankDD.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });

        $scope.chequeOld = @json(old('cheques'));
        if ($scope.chequeOld) {
            $.each($scope.chequeOld.id, function (k, v) {
                $scope.cheques[k] = true;
            });
            // $scope.cheques = $scope.chequeOld.id;
            if ($scope.chequeOld.hasOwnProperty('shortage')) {
                $.each($scope.chequeOld['shortage'], function (ind, value) {
                    var elem = '[name="cheques[shortage][' + ind + ']"]';
                    if (value) {
                        $(elem).parent().dropdown('set text', value).dropdown('set value', value);
                    }
                })
            }

            if ($scope.chequeOld.hasOwnProperty('cheque_type')) {
                $.each($scope.chequeOld['cheque_type'], function (ind, value) {
                    var elem = '[name="cheques[cheque_type][' + ind + ']"]';
                    if (value) {
                        $(elem).parent().dropdown('set text', value).dropdown('set value', value);
                    }
                })
            }

            if ($scope.chequeOld.hasOwnProperty('cheque_bank')) {
                $.each($scope.chequeOld['cheque_bank'], function (ind, value) {
                    var elem = '[name="cheques[cheque_bank][' + ind + ']"]';
                    $scope.cheque = _.find($scope.chequesData, function (k, v) {
                        return k.id === parseFloat(ind);
                    });
                    if ($scope.cheque) {
                        $(elem).parent().dropdown('set text', $scope.banks[value]).dropdown('set value', value);
                    }
                })
            }
        }

        // $(document).ready(function () {
        //     $('#productScroll').slimScroll({
        //         height: '500px'
        //     });
        // });
        $scope.chequeAmounts = [];
        $scope.chequeCheck = function ($event) {
            var chequeId = $($event.target).data('id');
            var amount = $($event.target).parent().parent().parent().find('.amount-p').data('amount');
            if (!$scope.cheques.hasOwnProperty(chequeId) || !$scope.cheques[chequeId]) {
                $scope.cheques[chequeId] = true;
                $scope.cheque = _.find($scope.chequesData, function (k, v) {
                    return k.id === chequeId;
                });
                if ($scope.cheque) {
                    var dd = $($event.target).parent().parent().parent().find('.bank-dropdown');
                    dd.dropdown('set text', $scope.cheque.bank.name).dropdown('set value', $scope.cheque.bank.id);

                    var chequeTypeDropDown = $($event.target).parent().parent().parent().find('.cheque-type-dropdown');
                    chequeTypeDropDown.dropdown('set text', $scope.cheque.cheque_type).dropdown('set value', $scope.cheque.cheque_type);
                }
            } else {
                if ($scope.cheques.hasOwnProperty(chequeId)) {
                    $scope.cheques[chequeId] = false;
                }
            }
            $scope.chequeAmounts[chequeId] = parseFloat(amount ? amount : 0);
            $scope.updateChequePayment();
        };

        $scope.expenseAmounts = [];

        $scope.expenseCheck = function ($event) {
            var expenseId = $($event.target).data('id');
            var amount = $($event.target).parent().parent().parent().find('.amount-p').data('amount');
            if (!$scope.expenses.hasOwnProperty(expenseId) || !$scope.expenses[expenseId]) {
                $scope.expenses[expenseId] = true;
            } else {
                if ($scope.expenses.hasOwnProperty(expenseId)) {
                    $scope.expenses[expenseId] = false;
                }
            }
            $scope.expenseAmounts[expenseId] = parseFloat(amount);
            $scope.totalExpense();
        };

        $scope.getShow = function (test, expense) {
            if (expense) {
                if (!$scope.expenses && !$scope.expenses.hasOwnProperty(test)) return false;
                return $scope.expenses[test];
            }
            if (!$scope.cheques && !$scope.cheques.hasOwnProperty(test)) return false;
            return $scope.cheques[test];
        };

        $scope.errors = @json($errors->toArray());
        $scope.hasErrorForCheque = function (name, second, key, className) {
            if (!$scope.errors.hasOwnProperty(name + '.' + second + '.' + key)) return '';
            var elem = '[name="' + name + '[' + second + '][' + key + ']"]';
            $(elem).parent().find('.form-control-feedback').text($scope.errors[name + '.' + second + '.' + key][0]);
            return 'error';
        };

        $scope.hasErrorForProduct = function (name) {
            if ($scope.errors.hasOwnProperty(name) && $scope.errors[name].hasOwnProperty(0)) {
                return $scope.errors[name][0];
            }
        };

        $scope.expenseAmounts = [];
        $scope.addAmount = function (id, amount, cheque) {
            if (cheque) {
                $scope.chequeAmounts[id] = parseFloat(amount);
                $scope.updateChequePayment();
            } else {
                $scope.expenseAmounts[id] = parseFloat(amount);
                $scope.totalExpense();
            }

        };


        $scope.totalExpense = function () {
            $scope.total_expense = 0;
            $.each($scope.expenseAmounts, function (k, v) {
                if (v) {
                    $scope.total_expense += parseFloat(v);
                    $scope.getShortageAmount();
                }
            });
            $scope.getBalance();
        };



        $scope.urls = {
            route: '{{ route('sales.allocation.get.route', ['fromDate' => carbon($allocation->from_date)->addDay(1)->toDateString(), 'toDate' => carbon($allocation->to_date)->addDay(1)->toDateString()]) }}',
            store: '{{ route('setting.store.search') }}',
        };

        $scope.allocationFormEl = {
            dropDown: $('.route-drop-down'),
            store: $('.store-drop-down')
        };

        $scope.allocationFormEl.dropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            onChange: $scope.handleLocationChange
        });

        $scope.initStoreDropDown = function () {
            $scope.allocationFormEl.store.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: $scope.handleLocationChange
            });
        };
        $scope.initStoreDropDown();

        $scope.oldData = @json(old());
        if ($scope.oldData.route_id && $scope.oldData.route_name) {
            $scope.allocationFormEl.dropDown.dropdown('set text', $scope.oldData.route_name).dropdown('set value', $scope.oldData.route_id);
        }

        if ($scope.oldData.store_name && $scope.oldData.store_id) {
            $scope.allocationFormEl.store.dropdown('set text', $scope.oldData.store_name).dropdown('set value', $scope.oldData.store_id);
        }
    }]);
</script>