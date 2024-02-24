<script src="{{ asset('js/vendor/form.js') }}"></script>
<script src="{{ asset('js/vendor/object-helper.js') }}"></script>
<script>

    app.filter('showRemoveBtn', function() {
        return function(index) {
            return index % 2 === 0;
        };
    });

    app.controller('TransactionOrderController', function ($scope, $timeout, $http) {
        $scope.el = {
            dropDown : $('.drop-down')
        };

        $scope.transaction = {
            records : [],
            debit_total: 0,
            credit_total : 0
        };

        $scope.record = {
            account_id : null,
            account_name : null,
            debit : 0,
            credit : 0,
            readonly : {
                debit : false,
                credit : false,
            }
        };

        $scope.setTotal = function(){
            $scope.transaction.debit_total = sum($scope.transaction.records, 'debit');
            $scope.transaction.credit_total = sum($scope.transaction.records, 'credit');
        };

        $scope.mapRecords = function () {
            if (!$scope.transaction.hasOwnProperty('records')) {
                $scope.transaction.records = [];
            }
            $.each($scope.transaction.account_id, function (k, v) {
                var record = {
                    account_id : null,
                    account_name : null,
                    debit : 0,
                    credit : 0,
                };

                if ($scope.transaction.hasOwnProperty('account_id') && $scope.transaction.account_id.hasOwnProperty(k)){
                    record.account_id = $scope.transaction.account_id[k];
                }
                if ($scope.transaction.hasOwnProperty('account_name') && $scope.transaction.account_name.hasOwnProperty(k)){
                    record.account_name = $scope.transaction.account_name[k];
                }
                if ($scope.transaction.hasOwnProperty('debit') && $scope.transaction.debit.hasOwnProperty(k)){
                    record.debit = $scope.transaction.debit[k];
                }
                if ($scope.transaction.hasOwnProperty('credit') && $scope.transaction.credit.hasOwnProperty(k)){
                    record.credit = $scope.transaction.credit[k];
                }
                $scope.transaction.records.push(record);
            })
        };

        @if(isset($trans))
            var records = @json($trans->records->toArray());
            $.each(records, function (k, v) {
                var record = angular.copy($scope.record);
                record.account_id = v.account_id;
                if (v.type === 'Credit'){
                    record.credit = v.amount;
                    record.readonly.credit = false;
                    record.readonly.debit = true;
                }else{
                    record.debit = v.amount;
                    record.readonly.credit = true;
                    record.readonly.debit = false;
                }
                $scope.transaction.records.push(record);
            });
            $scope.setTotal();
        @endif

        /** set form old values */
        @if (old('_token'))
            $scope.transaction = @json(old());
            $scope.mapRecords();
        @endif

        $scope.hasError = function(name, index){
            if ($scope.mappedErrors.hasOwnProperty(name)) {
                if ($scope.mappedErrors[name].hasOwnProperty(index)) {
                    return $scope.mappedErrors[name][index];
                }
            }
            return false;
        };

        $scope.errors = {};
        $scope.mappedErrors = {};
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

        /** map validation messages to form errors variable */
        @if (isset($errors))
            $scope.errors = @json($errors->toArray());
            $scope.mappedErrors = $scope.mapError($scope.errors);
            $scope.setTotal();
        @endif

        $scope.addRecord = function () {
            $scope.transaction.records.push(angular.copy($scope.record));
            $scope.transaction.records.push(angular.copy($scope.record));
        };

        if ($scope.transaction.records.length === 0){
            $scope.addRecord();
        }

        $scope.removeRecord = function (index) {
            $scope.transaction.records = $scope.removeByKey($scope.transaction.records, index);
        };

        // Remove item from object by key
        $scope.removeByKey = function (array, index) {
            if (array.hasOwnProperty(index)) {
                array.splice(index, 1);
            }
            return array;
        };

        $scope.el.dropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });

        $scope.disableItemInput = function(index, disableType, disable = true){
            if ($scope.transaction.records[index].hasOwnProperty(disableType)) {
                if (disable){
                    $scope.transaction.records[index][disableType] = 0;
                }
                if ($scope.transaction.records[index].hasOwnProperty('readonly')) {
                    if ($scope.transaction.records[index].readonly.hasOwnProperty(disableType)) {
                        $scope.transaction.records[index].readonly[disableType] = disable;
                    }
                }
            }
        };

        $scope.disableAmountInput = function (index, type) {
            if ($scope.transaction.records.hasOwnProperty(index)) {
                var disableType = (type === "debit") ? 'credit' : 'debit';
                var value = 0;
                if ($scope.transaction.records[index].hasOwnProperty(type)) {
                    value = $scope.transaction.records[index][type]
                }
                if (value == 0 || value === ''){
                    console.log(value)
                    $scope.disableItemInput(index, type, false);
                    $scope.disableItemInput(index, disableType, false);
                }else{
                    $scope.disableItemInput(index, type, false);
                    $scope.disableItemInput(index, disableType, true);
                }
            }
        };

        $scope.calculateTotal = function(index, type) {
            $scope.disableAmountInput(index, type);
            $scope.setTotal();
        };

        function roundToTwo(num) {
            return +(Math.round(num + "e+2")  + "e-2");
        }

        // Check double value
        function chief_double(num) {
            var n = roundToTwo(parseFloat(num));
            if (isNaN(n)) {
                return 0.00;
            }
            else {
                return roundToTwo(parseFloat(num));
            }
        }

        // sum the key values in object
        function sum(object, key) {
            return _.reduce(object, function (memo, item) {
                if (item.hasOwnProperty(key)) {
                    var value = chief_double(item[key]);
                    return memo + value;
                }
                return memo;
            }, 0)
        }

        $scope.initAccountDropDown = function (el) {
            el.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false
            });
        };

        $scope.setAccountDropdownValue = function () {
            angular.forEach($scope.transaction.records, function (record, index) {
                $('.account-drop-down[data-index="' + index + '"]').dropdown('set selected', record.account_id);
            });
        }
    }).directive('accountLoop', function () {
        return function (scope, element, attrs) {
            if (scope.$last) {
                scope.initAccountDropDown($('.account-drop-down'));
                setTimeout(scope.setAccountDropdownValue, 300);
            }
        }
    });;
</script>
