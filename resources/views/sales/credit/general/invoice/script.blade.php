<script>
    app.controller('AddInvoiceController', function ($scope, $http) {
        // Elements
        $scope.el = {
            formBtn: $('#apply_to_invoices_btn'),
            referenceDD: $('.reference-drop-down'),
            accountDD: $('.account-drop-down')
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
            invoice_id: [],
            payment_date: [],
            payment_type: [],
            payment: [],
            account: [],
        };
        // DropDown urls
        $scope.urls = {
            referenceDD: '{{ route('sales.invoice.reference.search', ['businessType' => 'BT',
             'customer' => 'SUP', 'where' => json_encode(['Draft', 'Open', 'Overdue', 'Partially Paid']),'formatted' => true]) }}'
        };
        // Form Related Vars
        $scope.addInvoiceForm = false;
        $scope.customerId = '{{ $credit->customer_id }}';
        $scope.btId = '{{ $credit->business_type_id }}';
        $scope.invoices = [];
        $scope.errors = [];
        // Show the apply to Invoice form
        $scope.el.formBtn.click(function () {
            $scope.addInvoiceForm = true;
            if (!$scope.$$phase) $scope.$apply()
        });
        // Get Invoice when Invoice DropDown Changed
        $scope.getInvoiceRoute = '{{ route('sales.invoice.get', ['invoice' => 'BILL']) }}';
        $scope.handleInvoiceDDChange = function (val) {
            $http.get($scope.getInvoiceRoute.replace('BILL', val)).then(function (response) {
                var $id = $scope.invoices.filter(function (value) {
                    return response.data.id === value.id
                });
                if (!$id.length) {
                    $scope.invoices.push(response.data);
                    var $key = getKeyByValue($scope.invoices, response.data);
                    $scope.data.invoice_id[$key] = response.data.id;
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
        //Invoice DropDown init
        $scope.el.referenceDD.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            apiSettings: {
                url: $scope.urls.referenceDD.replace('BT', $scope.btId).replace('SUP', $scope.customerId) + '/{query}',
                cache: false
            },
            onChange: $scope.handleInvoiceDDChange
        });
        //Remove Data
        $scope.removeData = function (key) {
            $scope.removeByKey($scope.data.invoice_id, key);
            $scope.removeByKey($scope.data.payment_date, key);
            $scope.removeByKey($scope.data.payment_type, key);
            $scope.removeByKey($scope.data.payment, key);
            $scope.removeByKey($scope.invoices, key);
            if (!$scope.$$phase) $scope.$apply()
        };
        //Remove Item from the array by key
        $scope.removeByKey = function (array, index) {
            if (array.hasOwnProperty(index)) {
                array.splice(index, 1);
            }
            return array;
        };

        $scope.paymentRoute = '{{ route('sales.credit.invoice.save', ['credit' => $credit]) }}';
        $scope.submitForm = function () {
            $http.post($scope.paymentRoute, $scope.data).then(function (response) {
                swal({
                    title: 'Success',
                    text: "Credit successfully applied to selected invoice(s).",
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

        $scope.cancelForm = function () {
            $scope.invoices = [];
            $scope.addInvoiceForm = false;
            $scope.data = {
                invoice_id: [],
                payment_date: [],
                payment_type: [],
                payment: []
            };
        }

    }).directive('invoiceDirective', function () {
        return function (scope, element, attrs) {
            if (scope.$last) {
                $('.datepicker').datepicker({
                    autoclose: true
                });
                $('.payment-type-dropdown').dropdown('setting', {
                    onChange: scope.handlePaymentTypeChange,
                    forceSelection: false,
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