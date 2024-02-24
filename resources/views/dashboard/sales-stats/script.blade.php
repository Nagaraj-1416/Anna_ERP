<script src="{{ asset('js/vendor/object-helper.js') }}"></script>
<script>
    app.controller('SalesStatController', ['$scope', '$http', function ($scope, $http) {

        // Scope variables
        $scope.query = {
            company: null,
            businessType: null,
            rep: null,
            fromDate: '{{ carbon()->toDateString() }}',
            toDate: '{{ carbon()->toDateString() }}'
        };
        $scope.loading = false;
        $scope.filterd = true;
        $scope.businessTypeName = '';
        $scope.companyName = '';
        $scope.repName = '';
        $scope.businessTypeName = 'None';
        $scope.loader = $('.preloader');
        $scope.dropdowns = {
            company: $('.company-drop-down'),
            type: $('.type-drop-down'),
            rep: $('.rep-drop-down'),
        };

        // company Drop Down
        $scope.dropdowns.company.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            onChange: function (val, name) {
                $scope.query.company = val;
                $scope.companyName = name;
                $scope.dropdowns.rep.dropdown('clear');
                repDropDown(val);
            }
        });

        // Business Type Drop Down
        $scope.dropdowns.type.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            onChange: function (val, name) {
                $scope.query.businessType = val;
                $scope.businessTypeName = name;
            }
        });

        function repDropDown(company) {
            var url = '{{ route('setting.rep.by.company.search', ['repId']) }}';
            url = url.replace('repId', company);
            $scope.dropdowns.rep.dropdown('setting', {
                apiSettings: {
                    url: url + '/{query}',
                    cache:false,
                },
                saveRemoteData:false,
                onChange: function(val, name){
                    $scope.query.rep = val;
                    $scope.repName = name;
                }
            });
        }

        $scope.errors = [];
        $scope.orderDetails = {};
        $scope.paymentsData = {};
        $scope.salesVisitData = {};
        $scope.masterData = {};
        $scope.salesExpensesData = {};
        // generate Data
        $scope.generate = function (loader) {
            $scope.loading = true;
            $scope.filterd = true;
            var route = '{{ route('sales.stats') }}';
            var params = $.param($scope.query);
            $scope.loading = true;
            $http.get(route + '?ajax=true&' + params).then(function (response) {
                $scope.orderDetails = response.data.orderData;
                $scope.loading = false;
                $scope.filterd = false;
                $scope.paymentsData = response.data.paymentsData;
                $scope.masterData = response.data.masterData;
                $scope.salesVisitData = response.data.salesVisitData;
                $scope.salesExpensesData = response.data.salesExpensesData;
                $scope.apply();
                $scope.setScroll();
            }).catch(function (error) {
                $scope.loading = false;
                $scope.errors = error.data.errors;
            });
        };
        // Order Balance Calculator
        $scope.orderBalance = function () {
            return parseInt($scope.orderDetails.totalSales) - parseInt($scope.orderDetails.totalPaid)
        };
        // Payment Total Calculator
        $scope.paymentTotal = function () {
            return parseInt($scope.paymentsData.cash) + parseInt($scope.paymentsData.cheque) + parseInt($scope.paymentsData.deposit)
        };

        $scope.cashTotal = function () {
            return parseInt($scope.paymentsData.cash)
        };

        $scope.chequeTotal = function () {
            return parseInt($scope.paymentsData.cheque)
        };

        $scope.depositTotal = function () {
            return parseInt($scope.paymentsData.deposit)
        };

        $scope.cardTotal = function () {
            return parseInt($scope.paymentsData.card)
        };

        //First Generate Call
        // $scope.generate(false);

        //State Update function
        $scope.apply = function () {
            if (!$scope.$$phase) $scope.$apply();
        };
        // Sum Function For Array
        $scope.sum = function (array) {
            var sum = _.reduce(array, function (memo, num) {
                return memo + num;
            }, 0);
            return sum;
        };

        $scope.hasError = function (name) {
            if ($scope.errors.hasOwnProperty(name)) {
                return $scope.errors[name][0];
            }
        };

        // Get Sales Order Balance Amount
        $scope.getBalanced = function (order) {
            return order.total - $scope.getTotal(order);
        };

        //Get Sales Order Payments Total
        $scope.getTotal = function (order) {
            var payments = order.payments;
            var amounts = _.pluck(payments, 'payment');
            var paid = $scope.sum(amounts);
            return paid;
        };

        // Get Sales Order Invoice Amount
        $scope.getInvoiceTotal = function (order) {
            var invoices = order.invoices;
            var amounts = _.pluck(invoices, 'amount');
            var paid = $scope.sum(amounts);
            return paid;
        };

        $scope.exportRoute = '{{ route('sales.stats.export') }}';
        $scope.getExportRoute = function () {
            var params = $.param($scope.query);
            return $scope.exportRoute + '?' + params;
        };

        $scope.printRoute = '{{ route('sales.stats.print') }}';
        $scope.getPrintRoute = function () {
            var params = $.param($scope.query);
            return $scope.printRoute + '?' + params;
        };

        $scope.resetFilters = function () {
            $scope.query = {
                company: null,
                businessType: null,
                rep: null,
                fromDate: '{{ carbon()->toDateString() }}',
                toDate: '{{ carbon()->toDateString() }}'
            };
            $scope.dropdowns.company.dropdown('clear');
            $scope.dropdowns.type.dropdown('clear');
            $scope.dropdowns.rep.dropdown('clear');
            $scope.orderDetails = [];
            $scope.paymentsData = [];
            $scope.masterData = [];
            $scope.salesVisitData = [];
            $scope.salesExpensesData = [];
            $scope.filterd = true;
            $scope.apply();
        };

        $scope.setScroll = function () {
            $('.scroll').slimScroll({
                height: '500px'
            });
        };
    }]);
</script>