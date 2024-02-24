<script>
    app.controller('SearchController', ['$scope', '$http', function ($scope, $http) {
        var url = '{{ route('search.result', ['keyword' => $query]) }}';
        $scope.purchaseData = [];
        $scope.salesData = [];
        $scope.expenseData = [];
        $scope.settingData = [];

        $scope.totalResults = 0;

        /** Sales related variables*/
        $scope.allocations = [];
        $scope.salesOrders = [];
        $scope.invoices = [];
        $scope.invoicePayments = [];
        $scope.customerCredits = [];
        $scope.estimates = [];
        $scope.inquiries = [];
        $scope.customers = [];

        /** Purchase related variables */
        $scope.bills = [];
        $scope.supplierCredits = [];
        $scope.Purchaseorders = [];
        $scope.billPayments = [];
        $scope.supplier = [];

        /** Expense related variables */
        $scope.receipts = [];
        $scope.reports = [];

        /** Settings related variables */
        $scope.products = [];

        $http.get(url).then(function (response) {
            $scope.main = response.data;

            $scope.purchaseData = $scope.main.purchase;
            $scope.salesData = $scope.main.sales;
            $scope.expenseData = $scope.main.expenses;
            $scope.settingData = $scope.main.settings;
            $scope.totalResults = $scope.main.total;
            if (_.toArray($scope.salesData).length) {
                $scope.allocations = $scope.salesData.allocations;
                $scope.salesOrders = $scope.salesData.orders;
                $scope.invoices = $scope.salesData.invoices;
                $scope.invoicePayments = $scope.salesData.payments;
                $scope.customerCredits = $scope.salesData.credits;
                $scope.estimates = $scope.salesData.estimates;
                $scope.inquiries = $scope.salesData.inquiries;
                $scope.customers = $scope.salesData.customers;
            }

            if (_.toArray($scope.purchaseData).length) {
                $scope.bills = $scope.purchaseData.bills;
                $scope.supplierCredits = $scope.purchaseData.credits;
                $scope.Purchaseorders = $scope.purchaseData.orders;
                $scope.billPayments = $scope.purchaseData.payments;
                $scope.suppliers = $scope.purchaseData.suppliers;
            }

            if (_.toArray($scope.expenseData).length) {
                $scope.receipts = $scope.expenseData.receipts;
                $scope.reports = $scope.expenseData.reports;
            }

            if (_.toArray($scope.settingData).length) {
                $scope.products = $scope.settingData.products;
            }
        });

        $scope.statusLabelColor = function (status) {
            return statusLabelColor(status);
        };

        $scope.checkSales = function () {
            return ($scope.allocations.length || $scope.salesOrders.length || $scope.invoices.length ||
                $scope.invoicePayments.length || $scope.customerCredits.length || $scope.estimates.length ||
                $scope.inquiries.length || $scope.customers.length);
        };

        $scope.checkPurchase = function () {
            return (
                $scope.bills.length ||
                $scope.supplierCredits.length ||
                $scope.Purchaseorders.length ||
                $scope.billPayments.length ||
                $scope.supplier.length
            );
        };

        $scope.checkExpense = function () {
            return (
                $scope.receipts.length ||
                $scope.reports.length
            );
        };

        $scope.checkSetting = function () {
            return (
                $scope.products.length
            );
        };
    }]);
</script>