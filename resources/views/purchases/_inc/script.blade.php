<script>
    app.controller('purchaseController', ['$scope', '$http', function ($scope, $http) {
        //Get supplier count
        var supplierRoute = '{{ route('purchase.summary.index', ['model' => 'Supplier']) }}';
        $http.get(supplierRoute + '?ajax=true').then(function (response) {
            $scope.supplierCount = response.data ? response.data.count : 0;
        });

        //Get Total order
        var totalOrder = '{{ route('purchase.summary.order') }}';
        $http.get(totalOrder + '?ajax=true').then(function (response) {
            $scope.totalOrderCount = response.data ? response.data.count : 0;
        });

        //Get Scheduled order
        var scheduledOrder = '{{ route('purchase.summary.order', ['status' => 'Scheduled']) }}';
        $http.get(scheduledOrder + '?ajax=true').then(function (response) {
            $scope.scheduledOrder = response.data ? response.data.count : 0;
        });


        //Get Delivered order
        var deliveredOrder = '{{ route('purchase.summary.order', ['status' => 'Delivered']) }}';
        $http.get(deliveredOrder + '?ajax=true').then(function (response) {
            $scope.deliveredOrder = response.data ? response.data.count : 0;
        });

        //Get Canceled order
        var canceledOrder = '{{ route('purchase.summary.order', ['status' => 'Canceled']) }}';
        $http.get(canceledOrder + '?ajax=true').then(function (response) {
            $scope.canceledOrder = response.data ? response.data.count : 0;
        });

        //Get total bills count
        var billRoute = '{{ route('purchase.summary.index', ['model' => 'Bill']) }}';
        $http.get(billRoute + '?ajax=true').then(function (response) {
            $scope.billCount = response.data ? response.data.count : 0;
        });

        //Get total bills count
        var paidBill = '{{ route('purchase.summary.index', ['model' => 'Bill', 'take' => 'null', 'with' => 'null', 'where' => 'Paid', 'field' => 'status']) }}';
        $http.get(paidBill + '?ajax=true').then(function (response) {
            $scope.paidBillCount = response.data ? response.data.count : 0;
        });

        //Get Delivery Due order
        var orders = '{{ route('purchase.summary.order', ['status' => 'Delivery Due']) }}';
        $http.get(orders + '?ajax=true').then(function (response) {
            $scope.orders = response.data ? response.data.data : 0;
        });

        //Get Delivery Due bills
        var bills = '{{ route('purchase.summary.bill', ['status' => 'Delivery Due']) }}';
        $scope.bills = [];
        $http.get(bills + '?ajax=true').then(function (response) {
            $scope.bills = response.data ? response.data : [];
        });

        $scope.poSummary = @json(getPoSummary());
        $scope.billSummary = @json(getBillSummary());


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

        var topFiveProducts = '{{ route('purchase.top.products') }}';
        $scope.topProducts = [];
        $http.get(topFiveProducts + '?ajax=true').then(function (response) {
            $scope.topProducts = response.data;
        });

        var topFiveSuppliers = '{{ route('purchase.top.suppliers') }}';
        $scope.topSuppliers = [];
        $http.get(topFiveSuppliers + '?ajax=true').then(function (response) {
            $scope.topSuppliers = response.data;
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
                                labelString: 'Purchase'
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


        var yearChart = '{{ route('purchase.year.chart') }}';
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
        var monthChartRoute = '{{ route('purchase.month.chart') }}';
        $http.get(monthChartRoute + '?ajax=true').then(function (response) {
            $scope.monthData = response.data.datas;
            $scope.monthLabels = response.data.keys;
            $scope.monthChartInit();
        });
    }]);
</script>