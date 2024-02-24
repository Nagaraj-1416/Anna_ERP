<script>
    app.controller('AddBillController', function ($scope, $http) {
        // Elements
        $scope.el = {
            formBtn: $('#apply_to_bills_btn'),
            referenceDD: $('.reference-drop-down')
        };
        // Payment Type DropDown Values
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
        // Data For Form Submit
        $scope.data = {
            bill_id: [],
            payment_date: [],
            payment_type: [],
            payment: [],
            account:[]
        };
        // DropDown urls
        $scope.urls = {
            referenceDD: '{{ route('purchase.bill.reference.search', ['businessType' => 'BT',
             'supplier' => 'SUP', 'where' => json_encode(['Draft', 'Open', 'Overdue', 'Partially Paid']),'formatted' => true]) }}'
        };
        // Form Related Vars
        $scope.addBillForm = false;
        $scope.supplierId = '{{ $credit->supplier_id }}';
        $scope.btId = '{{ $credit->business_type_id }}';
        $scope.bills = [];
        $scope.errors = [];
        // Show the apply to Bill form
        $scope.el.formBtn.click(function () {
            $scope.addBillForm = true;
            if (!$scope.$$phase) $scope.$apply()
        });
        // Get Bill when Bill DropDown Changed
        $scope.getBillRoute = '{{ route('purchase.bill.get', ['bill' => 'BILL']) }}';
        $scope.handleBillDDChange = function (val) {
            $http.get($scope.getBillRoute.replace('BILL', val)).then(function (response) {
                var $id = $scope.bills.filter(function (value) {
                    return response.data.id === value.id
                });
                if (!$id.length) {
                    $scope.bills.push(response.data);
                    var $key = getKeyByValue($scope.bills, response.data);
                    $scope.data.bill_id[$key] = response.data.id;
                    $scope.data.payment_date[$key] = '';
                    $scope.data.payment_type[$key] = '';
                    $scope.data.payment[$key] = '';
                    $scope.data.account[$key] = '';
                }
            })
        };

        // Get Key For the Object By Value
        function getKeyByValue(object, value) {
            return Object.keys(object).find(function (key) {
                return object[key] === value
            });
        }

        // Payment Type DropDown Change Event
        $scope.handlePaymentTypeChange = function (val, name, elem) {
            var index = $(elem).parent().parent().data('index').toString();
            $scope.data.payment_type[index] = val;
        };

        $scope.handleAccountDDChange = function (val, name, elem) {
            var index = $(elem).parent().parent().data('index').toString();
            $scope.data.account[index] = val;
        };
        //Bill DropDown init
        $scope.el.referenceDD.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            apiSettings: {
                url: $scope.urls.referenceDD.replace('BT', $scope.btId).replace('SUP', $scope.supplierId) + '/{query}',
                cache: false
            },
            onChange: $scope.handleBillDDChange
        });
        //Remove Data
        $scope.removeData = function (key) {
            $scope.removeByKey($scope.data.bill_id, key);
            $scope.removeByKey($scope.data.payment_date, key);
            $scope.removeByKey($scope.data.payment_type, key);
            $scope.removeByKey($scope.data.payment, key);
            $scope.removeByKey($scope.bills, key);
            if (!$scope.$$phase) $scope.$apply()
        };
        //Remove Item from the array by key
        $scope.removeByKey = function (array, index) {
            if (array.hasOwnProperty(index)) {
                array.splice(index, 1);
            }
            return array;
        };

        $scope.paymentRoute = '{{ route('purchase.credit.bill.save', ['credit' => $credit]) }}';
        $scope.submitForm = function () {
            $http.post($scope.paymentRoute, $scope.data).then(function (response) {
                swal({
                    title: 'Success',
                    text: "Credit successfully applied to selected bill(s).",
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

        $scope.cancelForm = function () {
            $scope.bills = [];
            $scope.addBillForm = false;
            $scope.data = {
                bill_id: [],
                payment_date: [],
                payment_type: [],
                payment: []
            };
        }

    }).directive('billDirective', function () {
        return function (scope, element, attrs) {
            if (scope.$last) {
                $('.datepicker').datepicker({
                    autoclose: true
                });
                $('.payment-type-dropdown').dropdown('setting', {
                    onChange: scope.handlePaymentTypeChange,
                    forceSelection: false,
                    saveRemoteData: false,
                });
                $('.account-drop-down').dropdown('setting', {
                    forceSelection: false,
                    saveRemoteData: false,
                    onChange: scope.handleAccountDDChange
                });
            }
        }
    });
</script>