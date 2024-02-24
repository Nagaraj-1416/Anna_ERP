<script src="{{ asset('js/vendor/object-helper.js') }}"></script>
<script src="{{ asset('js/vendor/collapse-table.js') }}"></script>
<script>
    app.controller('DashboardController', ['$scope', '$http', function ($scope, $http) {
        var invoiceDueRoute = '{{ route('dashboard.invoice') }}';
        $scope.invoiceData = [];
        $http.get(invoiceDueRoute).then(function (response) {
            $scope.invoiceData = response.data;
        });

        $scope.getDueInvoiceCount = function (key) {
            if ($scope.invoiceData.hasOwnProperty(key)) {
                return $scope.invoiceData[key].length;
            }
        };

        $scope.getDueInvoiceTotal = function (key) {
            if ($scope.invoiceData.hasOwnProperty(key)) {
                if (!$scope.invoiceData[key].length) return 0;
                return $scope.invoiceData[key].reduce((x, y) => x + y);
            }
        };

        //For Bill
        var billDueRoute = '{{ route('dashboard.bill') }}';
        $scope.billData = [];
        $http.get(billDueRoute).then(function (response) {
            $scope.billData = response.data;
        });

        $scope.getDueBillCount = function (key) {
            if ($scope.billData.hasOwnProperty(key)) {
                return $scope.billData[key].length;
            }
        };

        $scope.getDueBillTotal = function (key) {
            if ($scope.billData.hasOwnProperty(key)) {
                if (!$scope.billData[key].length) return 0;
                return $scope.billData[key].reduce((x, y) => x + y);
            }
        };

        /** BEGIN | Top Expenses */
        $scope.topExpenseChartData = [];
        $scope.topExpenseChartLabels = [];

        $scope.getRandomColor = function () {
            var baseColor = 'rgba(' + randomIntFromInterval(25, 50) + ', ' + randomIntFromInterval(120, 250) + ', '
                + randomIntFromInterval(241, 156) + ', 1)';
            return baseColor;
        };

        function randomIntFromInterval(min, max) {
            return Math.floor(Math.random() * (max - min + 1) + min);
        }

        $scope.initTopExpenseChart = function () {
            var colors = [];
            $.each($scope.typeLabels, function (key, data) {
                colors[key] = $scope.getRandomColor();
            });
            var ctx4 = document.getElementById("topExpense").getContext("2d");
            var myDoughnutChart = new Chart(ctx4, {
                    type: 'pie',
                    data: {
                        datasets: [{
                            data: _.toArray($scope.typeData),
                            backgroundColor: colors
                        }],
                        labels: $scope.typeLabels
                    },
                    segmentShowStroke: true,
                    segmentStrokeColor: "#fff",
                    segmentStrokeWidth: 0,
                    animationSteps: 100,
                    tooltipCornerRadius: 2,
                    animationEasing: "easeOutBounce",
                    animateRotate: true,
                    animateScale: false,
                    responsive: true,
                    options: {
                        legend: {
                            display: false
                        }
                    }
                })
            ;
        };

                @if(isDirectorLevelStaff() || isAccountLevelStaff())
        var typeChartRoute = '{{ route('expense.type.chart') }}';
        $http.get(typeChartRoute + '?ajax=true').then(function (response) {
            $scope.typeData = response.data.datas;
            $scope.typeLabels = response.data.keys;
            $scope.initTopExpenseChart();
        });

        @endif
        /** END | Top Expenses */

        $scope.dailyStocks = [];
        var dailyStocksRoute = '{{ route('dashboard.daily.stocks') }}';
        $http.get(dailyStocksRoute).then(function (response) {
            $scope.dailyStocks = response.data;
        });

    }]);
</script>