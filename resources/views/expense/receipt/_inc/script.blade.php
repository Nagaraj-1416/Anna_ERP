<script src="{{ asset('js/vendor/form.js') }}"></script>
<script src="{{ asset('js/vendor/object-helper.js') }}"></script>
<script>
    app.controller('ExpenseFormController', function ($scope, $http) {

        $scope.todayDate = '{{ carbon()->toDateString() }}';
        $scope.currentTime = '{{ carbon()->toTimeString() }}';

        $scope.query = {
            expTypeId: null,
            chequeType: null
        };

        /** Module related jQuery objects */
        $scope.el = {
            paidThroughAccountDropDown: $('.paid-through-drop-down'),
            companyDropDown: $('.company-drop-down'),
            branchDropDown: $('.branch-drop-down'),
            expenseTypeDropDown: $('.expense-type-drop-down'),
            expenseAccountDropDown: $('.exp-acc-drop-down'),
            vehicleDropDown: $('.vehicle-drop-down'),
            monthDropDown: $('.month-drop-down'),
            staffDropDown: $('.staff-drop-down'),
            supplierDropDown: $('.supplier-drop-down'),
            driverDropDown: $('.driver-drop-down'),
            vehicleMainTypeDropDown: $('.vehicle-main-type-drop-down'),
            paymentModeHidden: $('.payment-mode-hidden'),

            additionalData: $('.additional-data'),

            // fields panel
            monthField: $('.month-field'),
            staffField: $('.staff-field'),
            installmentPeriodField: $('.installment-period-field'),
            daysField: $('.days-field'),
            vehicleField: $('.vehicle-field'),
            literField: $('.liter-field'),
            odometerField: $('.odometer-field'),
            whatRepairedField: $('.what-repaired-field'),
            changedItemField: $('.changed-item-field'),
            supplierField: $('.supplier-field'),
            expiryDateField: $('.expiry-date-field'),
            repairingShopField: $('.repairing-shop-field'),
            labourChargeField: $('.labour-charge-field'),
            driverField: $('.driver-field'),
            odoAtRepairField: $('.odo-at-repair-field'),
            serviceStationField: $('.service-station-field'),
            odoAtServiceField: $('.odo-at-service-field'),
            parkingNameField: $('.parking-name-field'),
            vehicleMainTypeField: $('.vehicle-maintenance-type-field'),
            fromDateField: $('.from-date-field'),
            toDateField: $('.to-date-field'),
            noMonthsField: $('.no-of-months-field'),
            fineReasonField: $('.fine-reason-field'),
            fromDestinationField: $('.from-destination-field'),
            toDestinationField: $('.to-destination-field'),
            noOfBagsField: $('.no-of-bags-field'),
            accountNumberField: $('.account-number-field'),
            unitsReadingField: $('.units-reading-field'),
            machineField: $('.machine-field'),
            festivalNameField: $('.festival-name-field'),
            donatedToField: $('.donated-to-field'),
            donatedReasonField: $('.donated-reason-field'),
            hotelNameField: $('.hotel-name-field'),
            bankNumberField: $('.bank-number-field'),
            branchField: $('.branch-field'),
            shopField: $('.shop-field'),
            expenseMode: $('.expense-mode'),
            expenseCategory: $('.expense-category'),
            approvalRequired: $('.approval-field'),
            expenseModeHidden: $('.expense-mode-hidden'),
            expenseCategoryHidden: $('.expense-category-hidden'),
            chequeTypeDropDown: $('.cheque-type-drop-down'),
            chequeDetailsPanel1: $('.cheque-details-panel-1'),
            chequeDetailsPanel2: $('.cheque-details-panel-2'),
            thirdPartyChequeDropDown: $('.third-party-cheque-drop-down'),
            paidThroughFormPanel: $('.paid-through-form-panel')
        };

        /** module related urls */
        $scope.urls = {
            company :  '{{ route('setting.company.search') }}',
            expenseType: '{{ route('expense.type.search') }}'
        };

        /** module related model */
        $scope.expense = {};
        $scope.expenseType = null;
        $scope.totalExpense = 0;

        /** set values to semantic UI drop-down */
        $scope.setDropDownValue = function (dd, value, name) {
            if (!dd) return false;
            dd.dropdown("refresh");
            dd.dropdown('set value', value);
            dd.dropdown('set text', name);
        };

        $scope.setDropdownNameAndValues = function(){
            if ($scope.expense.hasOwnProperty('expense_type')
                && $scope.expense.hasOwnProperty('expense_type_name')) {
                $scope.setDropDownValue(
                    $scope.el.expenseTypeDropDown,
                    $scope.expense.expense_type,
                    $scope.expense.expense_type_name
                );
            }
            if ($scope.expense.hasOwnProperty('paid_through')
                && $scope.expense.hasOwnProperty('paid_through_name')) {
                if ($scope.expense.paid_through && typeof  $scope.expense.paid_through  === 'object')
                    $scope.expense.paid_through = $scope.expense.paid_through.id;
                $scope.setDropDownValue(
                    $scope.el.paidThroughAccountDropDown,
                    $scope.expense.paid_through,
                    $scope.expense.paid_through_name
                );
            }
            if ($scope.expense.hasOwnProperty('company_id')
                && $scope.expense.hasOwnProperty('company_name')) {
                $scope.setDropDownValue(
                    $scope.el.companyDropDown,
                    $scope.expense.company_id,
                    $scope.expense.company_name
                );
            }
            if ($scope.expense.hasOwnProperty('company_id')
                && $scope.expense.hasOwnProperty('company_name')) {
                $scope.setDropDownValue(
                    $scope.el.branchDropDown,
                    $scope.expense.company_id,
                    $scope.expense.company_name
                );
            }
            if ($scope.expense.hasOwnProperty('expense_account')
                && $scope.expense.hasOwnProperty('expense_account_name')) {
                $scope.setDropDownValue(
                    $scope.el.expenseAccountDropDown,
                    $scope.expense.expense_account,
                    $scope.expense.expense_account_name
                );
            }
        };

        $scope.setValues = function () {
            $scope.expenseType = $scope.expense.expense_type;
            $scope.calculateMileage = $scope.expense.calculate_mileage_using;
            $scope.setDropdownNameAndValues();
        };

        /** Expense category dropdown init */
        $scope.el.thirdPartyChequeDropDown.dropdown({
            forceSelection: false,
            saveRemoteData: false
        });

        /** Expense category dropdown init */
        $scope.el.expenseTypeDropDown.dropdown({
            forceSelection: false,
            saveRemoteData: false,
            apiSettings: {
                url: $scope.urls.expenseType + '/{query}',
                cache: false
            },
            onChange: function (val, name) {
                $scope.query.expTypeId = val;
                handleExpenseTypeData(val);
            }
        });

        @if(old('_token'))
            handleExpenseTypeData('{{old('type_id')}}');
        @else
            handleExpenseTypeData($scope.query.expTypeId);
        @endif

        @if(isset($expense))
            handleExpenseTypeData('{{ $expense->type_id }}');
        @endif

        function handleExpenseTypeData(expType) {

            if(!expType){
                $scope.el.additionalData.hide();
            }else{
                $scope.el.additionalData.show();
            }

            // Salary, Salary Advance, Bonus, Commission
            // EPF, ETF, NBT, Vat, Rent
            const monthFieldType = ['12', '13', '11', '15', '20', '21', '22', '23', '33'];
            if (monthFieldType.includes(expType)) {
                $scope.el.monthField.show()
            }else{
                $scope.el.monthField.hide()
            }

            // Salary, Salary Advance, Bonus, Loan -->
            // Commission, Allowance, Fine, Transport -->
            const staffFieldType = ['12', '13', '11', '14', '15', '3', '9', '8'];
            if (staffFieldType.includes(expType)) {
                $scope.el.staffField.show()
            }else{
                $scope.el.staffField.hide()
            }

            // Loan -->
            const installPeriodFieldType = ['14'];
            if (installPeriodFieldType.includes(expType)) {
                $scope.el.installmentPeriodField.show()
            }else{
                $scope.el.installmentPeriodField.hide()
            }

            // Allowance -->
            const daysFieldType = ['3'];
            if (daysFieldType.includes(expType)) {
                $scope.el.daysField.show()
            }else{
                $scope.el.daysField.hide()
            }

            // Vehicle Repair, Fuel, Service, Parking, Vehicle Maintenance, Lease, Fine, transport -->
            const vehicleFieldType = ['6', '2', '5', '17', '18', '9', '8', '16'];
            if (vehicleFieldType.includes(expType)) {
                $scope.el.vehicleField.show()
            }else{
                $scope.el.vehicleField.hide()
            }

            // Fuel -->
            const literFieldType = ['2'];
            if (literFieldType.includes(expType)) {
                $scope.el.literField.show()
            }else{
                $scope.el.literField.hide()
            }

            // Fuel -->
            const odometerFieldType = ['2'];
            if (odometerFieldType.includes(expType)) {
                $scope.el.odometerField.show()
            }else{
                $scope.el.odometerField.hide()
            }

            // Vehicle Repair -->
            const whatRepairedFieldType = ['6'];
            if (whatRepairedFieldType.includes(expType)) {
                $scope.el.whatRepairedField.show()
            }else{
                $scope.el.whatRepairedField.hide()
            }

            // Vehicle Repair, Service, Machine Maintenance -->
            const changedItemFieldType = ['6', '16', '29'];
            if (changedItemFieldType.includes(expType)) {
                $scope.el.changedItemField.show()
            }else{
                $scope.el.changedItemField.hide()
            }

            // Vehicle Repair, Machine Maintenance -->
            const supplierFieldType = ['6', '29'];
            if (supplierFieldType.includes(expType)) {
                $scope.el.supplierField.show()
            }else{
                $scope.el.supplierField.hide()
            }

            // Vehicle & Machine Repair, Machine Maintenance -->
            const expiryDateFieldType = ['6', '29'];
            if (expiryDateFieldType.includes(expType)) {
                $scope.el.expiryDateField.show()
            }else{
                $scope.el.expiryDateField.hide()
            }

            // Vehicle & Machine Repair, Machine Maintenance -->
            const repairingShopFieldType = ['6', '29'];
            if (repairingShopFieldType.includes(expType)) {
                $scope.el.repairingShopField.show()
            }else{
                $scope.el.repairingShopField.hide()
            }

            // Vehicle, Machine Repair, Service, Machine Maintenance -->
            const labourChargeFieldType = ['6', '16', '29'];
            if (labourChargeFieldType.includes(expType)) {
                $scope.el.labourChargeField.show()
            }else{
                $scope.el.labourChargeField.hide()
            }

            // Vehicle Repair, Service -->
            const driverFieldType = ['6', '16'];
            if (driverFieldType.includes(expType)) {
                $scope.el.driverField.show()
            }else{
                $scope.el.driverField.hide()
            }

            // Vehicle Repair -->
            const odoAtRepairFieldType = ['6'];
            if (odoAtRepairFieldType.includes(expType)) {
                $scope.el.odoAtRepairField.show()
            }else{
                $scope.el.odoAtRepairField.hide()
            }

            // Service -->
            const serviceStationFieldType = ['16'];
            if (serviceStationFieldType.includes(expType)) {
                $scope.el.serviceStationField.show()
            }else{
                $scope.el.serviceStationField.hide()
            }

            // Service -->
            const odoAtServiceFieldType = ['16'];
            if (odoAtServiceFieldType.includes(expType)) {
                $scope.el.odoAtServiceField.show()
            }else{
                $scope.el.odoAtServiceField.hide()
            }

            // Parking -->
            const parkingNameFieldType = ['5'];
            if (parkingNameFieldType.includes(expType)) {
                $scope.el.parkingNameField.show()
            }else{
                $scope.el.parkingNameField.hide()
            }

            // Vehicle Maintenance -->
            const vehicleMainTypeFieldType = ['17'];
            if (vehicleMainTypeFieldType.includes(expType)) {
                $scope.el.vehicleMainTypeField.show()
            }else{
                $scope.el.vehicleMainTypeField.hide()
            }

            // Vehicle Maintenance, Income Tax, CEB, Telephone, Room Charge, Water -->
            const fromDateFieldType = ['17', '24', '25', '27', '7', '26'];
            if (fromDateFieldType.includes(expType)) {
                $scope.el.fromDateField.show()
            }else{
                $scope.el.fromDateField.hide()
            }

            // Vehicle Maintenance, Income Tax, CEB, Telephone, Room Charge, Water -->
            const toDateFieldType = ['17', '24', '25', '27', '7', '26'];
            if (toDateFieldType.includes(expType)) {
                $scope.el.toDateField.show()
            }else{
                $scope.el.toDateField.hide()
            }

            // Lease -->
            const noMonthsFieldType = ['18'];
            if (noMonthsFieldType.includes(expType)) {
                $scope.el.noMonthsField.show()
            }else{
                $scope.el.noMonthsField.hide()
            }

            // Fine -->
            const fineFieldType = ['9'];
            if (fineFieldType.includes(expType)) {
                $scope.el.fineReasonField.show()
            }else{
                $scope.el.fineReasonField.hide()
            }

            // Transport -->
            const fromDestinationFieldType = ['8'];
            if (fromDestinationFieldType.includes(expType)) {
                $scope.el.fromDestinationField.show()
            }else{
                $scope.el.fromDestinationField.hide()
            }

            // Transport -->
            const toDestinationFieldType = ['8'];
            if (toDestinationFieldType.includes(expType)) {
                $scope.el.toDestinationField.show()
            }else{
                $scope.el.toDestinationField.hide()
            }

            // Transport -->
            const noOfBagsFieldType = ['8'];
            if (noOfBagsFieldType.includes(expType)) {
                $scope.el.noOfBagsField.show()
            }else{
                $scope.el.noOfBagsField.hide()
            }

            // CEB, Telephone, Water -->
            const accountNumberFieldType = ['25', '27', '26'];
            if (accountNumberFieldType.includes(expType)) {
                $scope.el.accountNumberField.show()
            }else{
                $scope.el.accountNumberField.hide()
            }

            // CEB, Water -->
            const unitsReadingFieldType = ['25', '26'];
            if (unitsReadingFieldType.includes(expType)) {
                $scope.el.unitsReadingField.show()
            }else{
                $scope.el.unitsReadingField.hide()
            }

            // Machine Maintenance -->
            const machineFieldType = ['29'];
            if (machineFieldType.includes(expType)) {
                $scope.el.machineField.show()
            }else{
                $scope.el.machineField.hide()
            }

            // Festival Expense -->
            const festivalNameFieldType = ['31'];
            if (festivalNameFieldType.includes(expType)) {
                $scope.el.festivalNameField.show()
            }else{
                $scope.el.festivalNameField.hide()
            }

            // Donation -->
            const donatedToFieldType = ['32'];
            if (donatedToFieldType.includes(expType)) {
                $scope.el.donatedToField.show()
            }else{
                $scope.el.donatedToField.hide()
            }

            // Donation -->
            const donatedReasonFieldType = ['32'];
            if (donatedReasonFieldType.includes(expType)) {
                $scope.el.donatedReasonField.show()
            }else{
                $scope.el.donatedReasonField.hide()
            }

            // Room Charge -->
            const hotelNameFieldType = ['7'];
            if (hotelNameFieldType.includes(expType)) {
                $scope.el.hotelNameField.show()
            }else{
                $scope.el.hotelNameField.hide()
            }

            // OD Interest, CHQ Book Issue -->
            const bankNumberFieldType = ['34', '35'];
            if (bankNumberFieldType.includes(expType)) {
                $scope.el.bankNumberField.show()
            }else{
                $scope.el.bankNumberField.hide()
            }
        }

        $scope.el.expenseMode.change(function (e) {
            e.preventDefault();
            handleExpenseModeData($(this).val());
        });

        @if(old('_token'))
            handleExpenseModeData('{{old('expense_mode')}}');
        @else
            handleExpenseModeData($scope.el.expenseMode.val());
        @endif

        @if(isset($expense))
            handleExpenseModeData('{{ $expense->expense_mode }}');
        @endif

        if($scope.el.expenseMode.val() === 'ForOthers'){
            $scope.el.branchField.hide();
            $scope.el.shopField.hide();
            $scope.el.expenseCategory.attr('disabled', false);
            $scope.el.expenseModeHidden.val($scope.el.expenseMode.val());
        }else if($scope.el.expenseMode.val() === 'Own'){
            $scope.el.branchField.hide();
            $scope.el.shopField.hide();
            $scope.el.expenseCategory.prop('checked', false);
            $scope.el.expenseCategory.attr('disabled', 'disabled');
            $scope.el.expenseModeHidden.val($scope.el.expenseMode.val());
        }

        function handleExpenseModeData(expMode) {
            if (expMode === 'ForOthers') {
                $scope.el.branchField.hide();
                $scope.el.shopField.hide();
                $scope.el.expenseCategory.attr('disabled', false);
                $scope.el.expenseModeHidden.val(expMode);
            }else if(expMode === 'Own'){
                $scope.el.branchField.hide();
                $scope.el.shopField.hide();
                $scope.el.expenseCategory.prop('checked', false);
                $scope.el.expenseCategory.attr('disabled', 'disabled');
                $scope.el.expenseModeHidden.val(expMode);
            }
        }

        $scope.el.expenseCategory.change(function (e) {
            e.preventDefault();
            handleExpenseCategoryData($(this).val());
        });

        @if(old('_token'))
            handleExpenseCategoryData('{{old('expense_category')}}');
        @endif

        @if(isset($expense))
            handleExpenseCategoryData('{{ $expense->expense_category }}');
        @endif

        function handleExpenseCategoryData(expCategory) {
            if (expCategory === 'Shop') {
                $scope.el.branchField.hide();
                $scope.el.shopField.show();
                $scope.el.approvalRequired.show();
                $scope.el.expenseCategoryHidden.val(expCategory);
            }else if(expCategory === 'Office'){
                $scope.el.branchField.show();
                $scope.el.shopField.hide();
                $scope.el.approvalRequired.show();
                $scope.el.expenseCategoryHidden.val(expCategory);
            }
        }

        /** cheque type dropdown init */
        $scope.el.chequeTypeDropDown.dropdown({
            forceSelection: false,
            saveRemoteData: false,
            onChange: function (val, name) {
                $scope.query.chequeType = val;
                handleChequeTypeData(val);
            }
        });

        @if(old('_token'))
            handleChequeTypeData('{{old('cheque_type')}}');
        @else
            handleChequeTypeData($scope.query.chequeType);
        @endif

        @if(isset($expense))
            handleChequeTypeData('{{ $expense->cheque_type }}');
        @endif

        function handleChequeTypeData(chequeType) {
            if (chequeType === 'Own') {
                $scope.el.chequeDetailsPanel2.show();
                $scope.el.chequeDetailsPanel1.hide();
                $scope.el.paidThroughFormPanel.hide();
            }else if(chequeType === 'Third Party'){
                $scope.el.chequeDetailsPanel1.show();
                $scope.el.chequeDetailsPanel2.hide();
                $scope.el.paidThroughFormPanel.show();
            }
        }

        /** paid through account dropdown init */
        /*$scope.el.paidThroughAccountDropDown.dropdown({
            forceSelection: false,
            saveRemoteData: false
        });*/

        /** expense account dropdown init */
        /*$scope.el.expenseAccountDropDown.dropdown({
            forceSelection: false,
            saveRemoteData: false
        });*/

        /** expense account dropdown init */
        $scope.el.vehicleDropDown.dropdown({
            forceSelection: false,
            saveRemoteData: false
        });

        /** expense account dropdown init */
        $scope.el.branchDropDown.dropdown({
            forceSelection: false,
            saveRemoteData: false,
            /*onChange: function (val, name) {
                if($scope.el.expenseMode.val() === 'ForOthers' && $scope.el.expenseCategory.val() === 'Office'){
                    $scope.el.expenseAccountDropDown.dropdown('clear');
                    $scope.el.paidThroughAccountDropDown.dropdown('clear');
                    expenseAccountDropDown(val);
                    paidThroughAccountDropDown(val);
                }
            }*/
        });

        /** company dropdown init */
        $scope.el.companyDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            onChange: function (val, name) {
                $scope.el.expenseAccountDropDown.dropdown('clear');
                $scope.el.paidThroughAccountDropDown.dropdown('clear');
                expenseAccountDropDown(val);
                if($scope.el.paymentModeHidden.val() === 'Cash'){
                    cashPaidThroughAccountDropDown(val);
                }else if($scope.el.paymentModeHidden.val() === 'Cheque'){
                    cihPaidThroughAccountDropDown(val);
                }else{
                    othersPaidThroughAccountDropDown(val);
                }
            }
        });

        function expenseAccountDropDown(company) {
            var url = '{{ route('finance.expense.account.by.company.search', ['companyId']) }}';
            url = url.replace('companyId', company);
            $scope.el.expenseAccountDropDown.dropdown('setting', {
                forceSelection: false,
                apiSettings: {
                    url: url + '/{query}',
                    cache:false,
                },
                saveRemoteData:false
            });
        }

        function cashPaidThroughAccountDropDown(company) {
            var url = '{{ route('finance.cash.paid.through.account.by.company.search', ['companyId']) }}';
            url = url.replace('companyId', company);
            $scope.el.paidThroughAccountDropDown.dropdown('setting', {
                forceSelection: false,
                apiSettings: {
                    url: url + '/{query}',
                    cache:false,
                },
                saveRemoteData:false
            });
        }

        function cihPaidThroughAccountDropDown(company) {
            var url = '{{ route('finance.cih.paid.through.account.by.company.search', ['companyId']) }}';
            url = url.replace('companyId', company);
            $scope.el.paidThroughAccountDropDown.dropdown('setting', {
                forceSelection: false,
                apiSettings: {
                    url: url + '/{query}',
                    cache:false,
                },
                saveRemoteData:false
            });
        }

        function othersPaidThroughAccountDropDown(company) {
            var url = '{{ route('finance.others.paid.through.account.by.company.search', ['companyId']) }}';
            url = url.replace('companyId', company);
            $scope.el.paidThroughAccountDropDown.dropdown('setting', {
                forceSelection: false,
                apiSettings: {
                    url: url + '/{query}',
                    cache:false,
                },
                saveRemoteData:false
            });
        }

        /** month dropdown init */
        $scope.el.monthDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });

        /** staff dropdown init */
        $scope.el.staffDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });

        /** supplier dropdown init */
        $scope.el.supplierDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });

        /** driver dropdown init */
        $scope.el.driverDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });

        /** vehicleMainTypeDropDown dropdown init */
        $scope.el.vehicleMainTypeDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });

    }).directive('itemLoop', function () {
        return function (scope, element, attrs) {

        }
    });
</script>