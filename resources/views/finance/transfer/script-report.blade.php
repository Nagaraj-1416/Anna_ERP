<script src="{{ asset('js/vendor/object-helper.js') }}"></script>
<script>
    app.controller('TransferController', ['$scope', '$http', function ($scope, $http) {
        $scope.company = null;
        $scope.fromRange = null;
        $scope.toRange = null;
        $scope.sales = null;
        $scope.cashReceived = null;
        $scope.chequeReceived = null;
        $scope.expenses = null;
        $scope.expensesTotal = null;
        $scope.balCash = null;
        $scope.shortage = null;
        $scope.excess = null;

        $scope.query = {
            fromDate: '',
            toDate: '',
            companyId: null
        };

        var url = '{{ route('finance.transfer.report') }}';

        $scope.companyDropDown = $('.company-drop-down');

        $scope.loading = false;

        //initiate date range drop down
        dateRangeDropDown($scope);

        $scope.companyDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            onChange: function (val) {
                $scope.query.companyId = val;
            }
        });

        $scope.errors = [];

        $scope.book_data = [];

        $scope.filterd = false;

        $scope.generate = function (companyId) {
            $scope.loading = true;
            $http.get(url + '?' + $.param($scope.query)).then(function (response) {
                $scope.company = response.data.company;
                $scope.fromRange = response.data.fromRange;
                $scope.toRange = response.data.toRange;
                $scope.sales = response.data.sales;
                $scope.cashReceived = response.data.cashReceived;
                $scope.chequeReceived = response.data.chequeReceived;
                $scope.expenses = response.data.expenses;
                $scope.expensesTotal = response.data.expensesTotal;
                $scope.balCash = response.data.balCash;
                $scope.shortage = response.data.shortage;
                $scope.excess = response.data.excess;
                $scope.loading = false;
            });
        };

        $scope.resetFilters = function () {
            $scope.company = null;
            $scope.query = {
                fromDate: '',
                toDate: '',
                companyId: null
            };
            dateRangeDropDown($scope);
            $scope.filterd = true;
            $scope.companyDropDown.dropdown('clear');
        };

    }]);
</script>