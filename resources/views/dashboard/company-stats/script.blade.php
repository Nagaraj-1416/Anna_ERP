<script src="{{ asset('js/vendor/object-helper.js') }}"></script>
<script>
    app.controller('CompanyStatsController', ['$scope', '$http', function ($scope, $http) {
        $scope.query = {
            fromDate: '',
            toDate: '',
            company: null,
            page: ''
        };
        var url = '{{ route('company.stats') }}';
        $scope.companyDD = $('.company-drop-down');
        $scope.loading = false;
        //Initiate Date Range Drop down
        dateRangeDropDown($scope);
        $scope.companyDD.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            onChange: function (val) {
                $scope.query.company = val;
            }
        });

        $scope.errors = [];
        $scope.purchase_data = [];
        $scope.sales_data = [];
        $scope.expense_data = [];
        $scope.filterd = false;
        $scope.generate = function () {
            $scope.getPurchaseData();
        };

        $scope.getPurchaseData = function () {
            $scope.purchaseLoading = true;
            $scope.purchaseFilter = false;
            $scope.query.page = 'purchase_data';
            $http.get(url + '?' + $.param($scope.query)).then(function (response) {
                $scope.errors = [];
                $scope.purchase_data = response.data.purchase_data;
                $scope.purchaseLoading = false;
                $scope.purchaseFilter = true;
                scroll();
                $scope.getSalesData();
            }).catch(function (error) {
                $scope.errors = error.data.errors;
                $scope.purchaseLoading = false;
                $scope.getSalesData();
            })
        };


        $scope.getSalesData = function () {
            $scope.salesLoading = true;
            $scope.salesFilter = false;
            $scope.query.page = 'sales_data';
            $http.get(url + '?' + $.param($scope.query)).then(function (response) {
                $scope.errors = [];
                $scope.sales_data = response.data.sales_data;
                $scope.salesLoading = false;
                $scope.salesFilter = true;
                scroll();
                $scope.getExpenseData();
            }).catch(function (error) {
                $scope.errors = error.data.errors;
                $scope.salesLoading = false;
                $scope.getExpenseData();
            })
        };

        $scope.getExpenseData = function () {
            $scope.expenseLoading = true;
            $scope.expenseFilter = false;
            $scope.query.page = 'expense_data';
            $http.get(url + '?' + $.param($scope.query)).then(function (response) {
                $scope.errors = [];
                $scope.expense_data = response.data.expense_data;
                $scope.expenseLoading = false;
                $scope.expenseFilter = true;
                scroll();
                $scope.getSalesByShopData();
            }).catch(function (error) {
                $scope.errors = error.data.errors;
                $scope.expenseLoading = false;
                $scope.getSalesByShopData();
            })
        };

        $scope.getSalesByShopData = function () {
            $scope.salesByShopLoading = true;
            $scope.salesByShopFilter = false;
            $scope.query.page = 'sales_by_shop';
            $http.get(url + '?' + $.param($scope.query)).then(function (response) {
                $scope.errors = [];
                $scope.salesByShop = response.data.sales_by_shop;
                $scope.salesByShopLoading = false;
                $scope.salesByShopFilter = true;
                scroll();
                $scope.getSalesByRepData();
            }).catch(function (error) {
                $scope.errors = error.data.errors;
                $scope.salesByShopLoading = false;
                $scope.getSalesByRepData();
            })
        };

        $scope.getSalesByRepData = function () {
            $scope.salesByRepLoading = true;
            $scope.salesByRepFilter = false;
            $scope.query.page = 'sales_by_rep';
            $http.get(url + '?' + $.param($scope.query)).then(function (response) {
                $scope.errors = [];
                $scope.salesByRep = response.data.sales_by_rep;
                $scope.salesByRepLoading = false;
                $scope.salesByRepFilter = true;
                scroll();
                $scope.getSalesByCustomersData();
            }).catch(function (error) {
                $scope.errors = error.data.errors;
                $scope.salesByRepLoading = false;
                $scope.getSalesByCustomersData();
            })
        };

        $scope.getSalesByCustomersData = function () {
            $scope.salesByCustomersLoading = true;
            // $scope.salesByRepFilter = false;
            $scope.query.page = 'sales_by_customer';
            $http.get(url + '?' + $.param($scope.query)).then(function (response) {
                $scope.errors = [];
                $scope.salesByCustomer = response.data.sales_by_customer;
                $scope.salesByCustomersLoading = false;
                // $scope.salesByRepFilter = true;
                $scope.getPurchaseBySupplierData();
                scroll();
            }).catch(function (error) {
                $scope.errors = error.data.errors;
                $scope.salesByCustomersLoading = false;
                $scope.getPurchaseBySupplierData();
            })
        };

        $scope.getPurchaseBySupplierData = function () {
            $scope.purchaseBySupplierLoading = true;
            // $scope.salesByRepFilter = false;
            $scope.query.page = 'purchase_by_supplier';
            $http.get(url + '?' + $.param($scope.query)).then(function (response) {
                $scope.errors = [];
                $scope.purchaseBySupplier = response.data.purchase_by_supplier;
                $scope.purchaseBySupplierLoading = false;
                // $scope.salesByRepFilter = true;
                $scope.getSalesByProductsData();
                scroll();
            }).catch(function (error) {
                $scope.errors = error.data.errors;
                $scope.purchaseBySupplierLoading = false;
                $scope.getSalesByProductsData();
            })
        };

        $scope.getSalesByProductsData = function () {
            $scope.salesByProductsLoading = true;
            // $scope.salesByRepFilter = false;
            $scope.query.page = 'sales_by_products';
            $http.get(url + '?' + $.param($scope.query)).then(function (response) {
                $scope.errors = [];
                $scope.salesByProducts = response.data.sales_by_products;
                $scope.salesByProductsLoading = false;
                // $scope.salesByRepFilter = true;
                $scope.getPurchaseByProductsData();
                scroll();
            }).catch(function (error) {
                $scope.errors = error.data.errors;
                $scope.salesByProductsLoading = false;
                $scope.getPurchaseByProductsData();
            })
        };

        $scope.getPurchaseByProductsData = function () {
            $scope.purchaseByProductsLoading = true;
            // $scope.salesByRepFilter = false;
            $scope.query.page = 'purchase_by_products';
            $http.get(url + '?' + $.param($scope.query)).then(function (response) {
                $scope.errors = [];
                $scope.purchaseByProducts = response.data.purchase_by_products;
                $scope.purchaseByProductsLoading = false;
                // $scope.salesByRepFilter = true;
                $scope.getCustomerBalanceData();
                scroll();
            }).catch(function (error) {
                $scope.errors = error.data.errors;
                $scope.purchaseByProductsLoading = false;
                $scope.getCustomerBalanceData();
            })
        };

        $scope.getCustomerBalanceData = function () {
            $scope.customerBalanceLoading = true;
            // $scope.salesByRepFilter = false;
            $scope.query.page = 'customer_balance';
            $http.get(url + '?' + $.param($scope.query)).then(function (response) {
                $scope.errors = [];
                $scope.customerBalance = response.data.customer_balance;
                $scope.customerBalanceLoading = false;
                // $scope.salesByRepFilter = true;
                $scope.getSupplierBalanceData();
                scroll();
            }).catch(function (error) {
                $scope.errors = error.data.errors;
                $scope.customerBalanceLoading = false;
                $scope.getSupplierBalanceData();
            })
        };

        $scope.getSupplierBalanceData = function () {
            $scope.supplierBalanceLoading = true;
            // $scope.salesByRepFilter = false;
            $scope.query.page = 'supplier_balance';
            $http.get(url + '?' + $.param($scope.query)).then(function (response) {
                $scope.errors = [];
                $scope.supplierBalance = response.data.supplier_balance;
                $scope.supplierBalanceLoading = false;
                // $scope.salesByRepFilter = true;
                scroll();
            }).catch(function (error) {
                $scope.errors = error.data.errors;
                $scope.supplierBalanceLoading = false;
            })
        };

        $scope.hasError = function (name) {
            if ($scope.errors.hasOwnProperty(name)) {
                return $scope.errors[name][0];
            }
        };

        $scope.getCount = function (object) {
            return _.toArray(object).length;
        };

        $scope.sum = function (array) {
            var sum = _.reduce(array, function (memo, num) {
                return memo + num;
            }, 0);
            return sum;
        };


        $scope.getOrderTotal = function (collection, rep) {
            var orders = collection.orders;
            if (rep) {
                orders = collection.sales_orders;
            }
            var amounts = _.pluck(orders, 'total');
            var paid = $scope.sum(amounts);
            return paid;
        };

        $scope.getSalesProductTotal = function (collection) {
            var orders = collection.sales_orders;
            var amounts = _.pluck(orders, 'total');
            var paid = $scope.sum(amounts);
            return paid;
        };

        $scope.getPurchaseProductTotal = function (collection) {
            var orders = collection.purchase_orders;
            var amounts = _.pluck(orders, 'total');
            var paid = $scope.sum(amounts);
            return paid;
        };

        $scope.getBalance = function (customer) {
            var orders = customer.orders;
            var payments = customer.payments;
            var orderTotal = $scope.sum(_.pluck(orders, 'total'));
            var paymentTotal = $scope.sum(_.pluck(payments, 'payment'));

            return (orderTotal - paymentTotal);
        };

        function scroll() {
            $('.slim-scroll').slimScroll({
                color: 'gray',
                height: '100%',
                railVisible: true,
                alwaysVisible: false
            })
        }

        $scope.getExpenseAmount = function (customer) {
            var expenses = customer.expenses;
            return $scope.sum(_.pluck(expenses, 'amount'));
        };

        $scope.resetFilters = function () {
            $scope.query = {
                fromDate: '',
                toDate: '',
                company: null
            };
            dateRangeDropDown($scope);
            $scope.filterd = true;
            $scope.companyDD.dropdown('clear');
        };

        $scope.exportRoute = '{{ route('company.stats.export') }}';
        $scope.getExportRoute = function () {
            var params = $.param($scope.query);
            return $scope.exportRoute + '?' + params;
        };

        $scope.printRoute = '{{ route('company.stats.print') }}';
        $scope.getPrintRoute = function () {
            var params = $.param($scope.query);
            return $scope.printRoute + '?' + params;
        };

        $scope.getProductQty = function (product, order) {
            return $scope.sum(_.pluck(_.pluck(product[order], 'pivot'), 'quantity'));
        };
    }]);
</script>