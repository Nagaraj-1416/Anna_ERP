<script>
    app.controller('SalesController', ['$scope', '$http', function ($scope, $http) {

        /** get customer count */
        var cusRoute = '{{ route('sales.summary.index', ['model' => 'Customer']) }}';
        $http.get(cusRoute + '?ajax=true').then(function (response) {
            $scope.cusCount = response.data ? response.data.count : 0;
        });

        /** get all sales order count */
        var orderRoute = '{{ route('sales.summary.index', ['model' => 'SalesOrder']) }}';
        $http.get(orderRoute + '?ajax=true').then(function (response) {
            $scope.orderCount = response.data ? response.data.count : 0;
        });

        /** get drafted sales order count */
        var draftOrderRoute = '{{ route('sales.summary.index', [
                'model' => 'SalesOrder', 'take' => 'null', 'with' => 'null', 'where' => 'Draft', 'field' => 'status']) }}';
        $http.get(draftOrderRoute + '?ajax=true').then(function (response) {
            $scope.draftOrderCount = response.data ? response.data.count : 0;
        });

        /** get closed sales order count */
        var closedOrderRoute = '{{ route('sales.summary.index', [
                'model' => 'SalesOrder', 'take' => 'null', 'with' => 'null', 'where' => 'Closed', 'field' => 'status']) }}';
        $http.get(closedOrderRoute + '?ajax=true').then(function (response) {
            $scope.closedOrderCount = response.data ? response.data.count : 0;
        });

        /** get canceled sales order count */
        var canceledOrderRoute = '{{ route('sales.summary.index', [
                'model' => 'SalesOrder', 'take' => 'null', 'with' => 'null', 'where' => 'Canceled', 'field' => 'status']) }}';
        $http.get(canceledOrderRoute + '?ajax=true').then(function (response) {
            $scope.canceledOrderCount = response.data ? response.data.count : 0;
        });

        /** get all sales invoices count */
        var invoiceRoute = '{{ route('sales.summary.index', ['model' => 'Invoice']) }}';
        $http.get(invoiceRoute + '?ajax=true').then(function (response) {
            $scope.invoiceCount = response.data ? response.data.count : 0;
        });

        /** get paid invoice count */
        var paidInvRoute = '{{ route('sales.summary.index', [
                'model' => 'Invoice', 'take' => 'null', 'with' => 'null', 'where' => 'Paid', 'field' => 'status']) }}';
        $http.get(paidInvRoute + '?ajax=true').then(function (response) {
            $scope.paidInvCount = response.data ? response.data.count : 0;
        });

        $scope.soSummary = @json(getSoSummary());
        $scope.invSummary = @json(getInvSummary());


        $scope.fetchData = function (url, successCallBack, errorCallBack) {
            $http.get(url).then(function (response) {
                if (typeof successCallBack === 'function') successCallBack(response)
            }).catch(function (error) {
                if (typeof errorCallBack === 'function') errorCallBack(error)
            })
        };
        var settlementDueRoute = '{{ route('sales.summary.settlement.due') }}';
        $scope.settlementDueData = [];
        $scope.fetchData(settlementDueRoute, function (response) {
            $scope.settlementDueData = response.data;
        });

        var topTenCustomerRoute = '{{ route('sales.summary.top.customer') }}';
        $scope.topCustomer = [];
        $scope.fetchData(topTenCustomerRoute, function (response) {
            $scope.topCustomer = response.data;
        });

        var topTenProductRoute = '{{ route('sales.summary.top.products') }}';
        $scope.topProduct = [];
        $scope.fetchData(topTenProductRoute, function (response) {
            $scope.topProduct = response.data;
        });

        var topTenSalesRepRoute = '{{ route('sales.summary.top.sales.rep') }}';
        $scope.topSalesReps = [];
        $scope.fetchData(topTenSalesRepRoute, function (response) {
            $scope.topSalesReps = response.data;
        });

        $scope.getLabelColor = function ($status) {
            var $color = 'label';
            if ($status === 'Draft') {
                $color = 'label-info';
            } else if ($status === 'Open') {
                $color = 'label-info';
            } else if ($status === 'Awaiting Approval') {
                $color = 'label-warning';
            } else if ($status === 'Overdue') {
                $color = 'label-danger';
            } else if ($status === 'Partially Paid') {
                $color = 'label-warning';
            } else if ($status === 'Paid') {
                $color = 'label-success';
            } else if ($status === 'Canceled') {
                $color = 'label-danger';
            } else if ($status === 'Refunded') {
                $color = 'label-danger';
            } else if ($status === 'Scheduled') {
                $color = 'label-info';
            } else if ($status === 'Closed') {
                $color = 'label-success';
            }
            return $color;
        };


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
                                labelString: 'Sales'
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


        var yearChart = '{{ route('sales.year.chart') }}';
        $http.get(yearChart + '?ajax=true').then(function (response) {
            $scope.data = response.data.datas;
            $scope.labels = response.data.keys;
            $scope.initYearChart();
        });
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
                        borderWidth: 1,

                    }]
                },
                options: {
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Sales'
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
        var monthChartRoute = '{{ route('sales.month.chart') }}';
        $http.get(monthChartRoute + '?ajax=true').then(function (response) {
            $scope.monthData = response.data.datas;
            $scope.monthLabels = response.data.keys;
            $scope.monthChartInit();
        });
    }]);
</script>