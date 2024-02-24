<script src="{{ asset('js/vendor/object-helper.js') }}"></script>
<script>
    app.controller('ExpenseController', ['$scope', '$http', function ($scope, $http) {
        // Variables
        $scope.totalExpenses = '';
        $scope.totalNonReportExpense = '';
        $scope.totalReports = '';
        $scope.unSubmitted = '';
        $scope.submitted = '';
        $scope.approved = '';
        $scope.reimbursed = '';
        $scope.submittedReport = '';
        $scope.reimbursedReport = '';

        var expenseRoute = '{{ route('expense.index.summary', ['model' => 'Expense']) }}'
        $http.get(expenseRoute).then(function (response) {
            $scope.totalExpenses = response.data.count;
        });

        var expenseReportRoute = '{{ route('expense.index.summary', ['model' => 'ExpenseReport']) }}'
        $http.get(expenseReportRoute).then(function (response) {
            $scope.totalReports = response.data.count;
        });

        var expenseNonReportRoute = '{{ route('expense.index.summary', ['model' => 'Expense', 'where' => 'NRE']) }}'
        $http.get(expenseNonReportRoute).then(function (response) {
            $scope.totalNonReportExpense = response.data.count;
        });

        var unSubmittedRoute = '{{ route('expense.index.summary', ['model' => 'Expense', 'where' => 'Unsubmitted']) }}'
        $http.get(unSubmittedRoute).then(function (response) {
            $scope.unSubmitted = response.data.count;
        });

        var submittedRoute = '{{ route('expense.index.summary', ['model' => 'Expense', 'where' => 'Submitted']) }}'
        $http.get(submittedRoute).then(function (response) {
            $scope.submitted = response.data.count;
        });
        var approvedRoute = '{{ route('expense.index.summary', ['model' => 'Expense', 'where' => 'Approved']) }}'
        $http.get(approvedRoute).then(function (response) {
            $scope.approved = response.data.count;
        });

        var reimbursedRoute = '{{ route('expense.index.summary', ['model' => 'Expense', 'where' => 'Reimbursed']) }}'
        $http.get(reimbursedRoute).then(function (response) {
            $scope.reimbursed = response.data.count;
        });

        var submittedRouteExpense = '{{ route('expense.index.summary', ['model' => 'ExpenseReport', 'where' => 'Submitted']) }}'
        $http.get(submittedRouteExpense).then(function (response) {
            $scope.submittedReport = response.data.count;
        });

        var reimbursedRouteExpense = '{{ route('expense.index.summary', ['model' => 'ExpenseReport', 'where' => 'Reimbursed']) }}'
        $http.get(reimbursedRouteExpense).then(function (response) {
            $scope.reimbursedReport = response.data.count;
        });

        $scope.data = [];
        $scope.labels = [];

        $scope.initYearChart = function () {
            var yearChart = document.getElementById("yearChart").getContext('2d');

            var data2 = {
                labels: $scope.labels,
                datasets: [
                    {
                        label: 'Amount',
                        backgroundColor: "#00897b",
                        data: $scope.data
                    }
                ]
            };
            var yearChart = new Chart(yearChart, {
                type: 'bar',
                data: data2,
                responsive: true,
                options: {
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Expenses'
                            }
                        }],
                        xAxes: [{
                            scaleLabel: {
                                display: true,
                                labelString: 'Years'
                            },
                            barThickness: 40
                        }]
                    }
                }
            });
        };

        var yearChart = '{{ route('expense.year.chart') }}';
        $http.get(yearChart + '?ajax=true').then(function (response) {
            $scope.data = response.data.datas;
            $scope.labels = response.data.keys;
            $scope.initYearChart();
        });

        // Month Comparison Chart
        $scope.monthData = [];
        $scope.monthLabels = [];

        $scope.monthChartInit = function () {
            var monthChart = document.getElementById("monthChart").getContext('2d');
            var myCharts = new Chart(monthChart, {
                type: 'line',
                data: {
                    labels: $scope.monthLabels,
                    datasets: [{
                        label: '',
                        data: $scope.monthData,
                        backgroundColor: '#00b3a146',
                        borderColor: '#00897b',
                        borderWidth: 1

                    }]
                },
                options: {
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Purchase'
                            }
                        }],
                        xAxes: [{
                            scaleLabel: {
                                display: true,
                                labelString: 'Months'
                            }
                        }]
                    }
                }
            });
        };


        var monthChartRoute = '{{ route('expense.month.chart') }}';
        $http.get(monthChartRoute + '?ajax=true').then(function (response) {
            $scope.monthData = response.data.datas;
            $scope.monthLabels = response.data.keys;
            $scope.monthChartInit();
        });
        $scope.typeData = [];
        $scope.typeLabels = [];

        $scope.getRandomColor = function () {
            var baseColor = 'rgba(' + randomIntFromInterval(25, 50) + ', ' + randomIntFromInterval(120, 250) + ', '
                + randomIntFromInterval(241, 156) + ', 1)';
            return baseColor;
        };

        function randomIntFromInterval(min, max) {
            return Math.floor(Math.random() * (max - min + 1) + min);
        }

        $scope.initTypeChart = function () {
            var colors = [];
            $.each($scope.typeLabels, function (key, data) {
                colors[key] = $scope.getRandomColor();
            });
            var ctx4 = document.getElementById("chart4").getContext("2d");
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

        var typeChartRoute = '{{ route('expense.type.chart') }}';
        $http.get(typeChartRoute + '?ajax=true').then(function (response) {
            $scope.typeData = response.data.datas;
            $scope.typeLabels = response.data.keys;
            $scope.initTypeChart();
        });

        $scope.topReports = [];
        var topReportsRoute = '{{ route('expense.top.reports') }}';
        $http.get(topReportsRoute + '?ajax=true').then(function (response) {
            $scope.topReports = response.data;
        });
    }]);
</script>