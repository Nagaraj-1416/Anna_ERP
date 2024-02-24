<script src="{{ asset('js/vendor/form.js') }}"></script>
<script src="{{ asset('js/vendor/object-helper.js') }}"></script>
<script>
    app.controller('ExpenseReportFormController', function ($scope, $http) {
        $scope.expenses = [];
        $scope.isRemoveable = false;
        $scope.report = [];

        /** Module related jQuery objects */
        $scope.el = {
            businessTypeDropDown: $('.bt-drop-down'),
            approvedByDropDown: $('.approved-by-drop-down'),
            expenseDropDown: $('.expense-drop-down'),
            fromDatePicker: $('#from-date'),
            toDatePicker: $('#to-date'),
            addExpensesBtn: $('.add-expenses'),
        };

        /** module related urls */
        $scope.urls = {
            businessType: '{{ route('setting.business.type.search') }}',
            users: '{{ route('setting.user.search') }}',
            expense: '{{ route('expense.receipt.search') }}',
            expense_old: '{{ route('expense.receipt.search.by.business.type', ['businessType' => 'BT_ID']) }}',
            getExpense: '{{ route('expense.receipt.get.expenses') }}',
        };

        /** set values to semantic UI drop-down */
        $scope.setDropDownValue = function (dd, value, name) {
            dd.dropdown("refresh");
            dd.dropdown('set value', value);
            dd.dropdown('set text', name);
        };

        /** set dropdown values */
        $scope.setDropdownValues = function () {
            if ($scope.report.hasOwnProperty('business_type_id') && $scope.report.hasOwnProperty('business_type_name')) {
                $scope.setDropDownValue($scope.el.businessTypeDropDown, $scope.report.business_type_id, $scope.report.business_type_name);
            }
            if ($scope.report.hasOwnProperty('approved_by')
                && $scope.report.hasOwnProperty('approved_by_name')) {
                if ($scope.report.approved_by && typeof  $scope.report.approved_by === 'object')
                    $scope.report.approved_by = $scope.report.approved_by.id;
                $scope.setDropDownValue(
                    $scope.el.approvedByDropDown,
                    $scope.report.approved_by,
                    $scope.report.approved_by_name
                );
            }
            if ($scope.expenses.length) {
                var value = '';
                $.each($scope.expenses, function (key, item) {
                    var selected = '<a class="ui label transition visible" data-value="' + item.id + '" style="display: inline-block !important;">' + item.expense_no + '<i class="delete icon"></i></a>';
                    $scope.el.expenseDropDown.parent().find('input').before(selected);
                    value += item.id + ',';
                });
                $scope.el.expenseDropDown.parent().find('input').val(value.replace(/,\s*$/, ""));
            }
        };

        /** set report edit values */
        @if(isset($report))
            $scope.report = @json($report);
        if ($scope.report.hasOwnProperty('expenses')) {
            $scope.expenses = $scope.report.expenses;
        }
        $scope.setDropdownValues();
        @endif

        /** old values to json */
        @if (old('_token'))
            $scope.report = @json(old());
        if ($scope.report.hasOwnProperty('expenses')) {
            $scope.expenses = $scope.report.expenses;
        }
        $scope.setDropdownValues();
        @endif


            $scope.loadExpenses = function () {
            var expensesIds = $scope.el.expenseDropDown.dropdown('get value');
            $http.post($scope.urls.getExpense, {ids: expensesIds}).then(function (response) {
                $scope.expenses = response.data;
            })
        };

        $scope.el.addExpensesBtn.click(function (e) {
            e.preventDefault();
            $scope.loadExpenses();
        });

        $scope.initExpenseDropdown = function (val) {
            /** expense dropdown init */
            var url = $scope.urls.expense;
            $scope.el.expenseDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: url + '/{query}',
                    cache: false
                },
                onAdd: function () {
                    setTimeout($scope.loadExpenses, 200);
                },
                onRemove: function () {
                    setTimeout($scope.loadExpenses, 200);
                },
            });

        };
        $scope.initExpenseDropdown();
        /** business type dropdown init */
        $scope.el.businessTypeDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            apiSettings: {
                url: $scope.urls.businessType + '/{query}',
                cache: false
            },
            onChange: $scope.initExpenseDropdown
        });

        /** company dropdown init */
        $scope.el.approvedByDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            apiSettings: {
                url: $scope.urls.users + '/{query}',
                cache: false
            }
        });

        $scope.totalAmount = function () {
            return sum($scope.expenses, 'amount');
        };

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

        $.fn.datepicker.defaults.format = "yyyy-mm-dd";
        $scope.el.fromDatePicker.datepicker({
            'autoclose': true
        }).on('changeDate', function () {
            var date = $(this).datepicker('getFormattedDate');
            $scope.el.toDatePicker.datepicker('setStartDate', date);
        });

        $scope.el.toDatePicker.datepicker({
            'autoclose': true
        }).on('changeDate', function () {
            var date = $(this).datepicker('getFormattedDate');
            $scope.el.fromDatePicker.datepicker('setEndDate', date);
        });
    });
</script>