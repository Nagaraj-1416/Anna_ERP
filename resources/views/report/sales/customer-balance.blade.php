@extends('layouts.master')
@section('title', 'Customer Balances')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Reports') !!}
@endsection
@section('content')
    <section ng-controller="salesProductController">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-b-0">
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Customer Balances</h3>
                    </div>
                    <hr>
                    <div class="card-body">
                        <!-- from to filter -->
                        <div class="form-filter">
                            @include('report.general.date.index')
                        </div>
                        <div class="clearfix m-t-10">
                            <div class="pull-left">
                                <button ng-click="generate()" class="btn btn-info"><i class="ti-filter"></i>
                                    Generate
                                </button>
                                <button ng-click="resetFilters()" class="btn btn-inverse"><i class="ti-eraser"></i>
                                    Reset
                                </button>
                            </div>
                            <div class="pull-right">
                                {{--<a target="_blank" href="#" class="btn btn-warning"><i class="fa fa-file-excel-o"></i>--}}
                                {{--Export to Excel</a>--}}
                                <a href="@{{ getExportRoute() }}" class="btn btn-danger"><i
                                            class="fa fa-file-pdf-o"></i>
                                    Export to PDF</a>
                            </div>
                        </div>
                        <hr class="hr-dark">
                        <!-- heading section -->
                        <div class="heading-section">
                            <h2 class="text-center"><b>Customer Balances</b></h2>
                            <p class="text-center text-muted"><b>As of </b> @{{ endDate | date}}
                        </div>

                        {{--data--}}
                        <div class="row">
                            <div class="loading" ng-show="loading">
                                <div class="loading-dot"></div>
                                <div class="loading-dot"></div>
                                <div class="loading-dot"></div>
                                <div class="loading-dot"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="loading" ng-show="loading">
                                <p>Please wait report is generating</p>
                            </div>
                        </div>
                        <div class="orders-list" ng-hide="loading">
                            <div class="table-responsive">
                                <table class="table color-table muted-table">
                                    <thead>
                                    <tr>
                                        <th width="20%">CUSTOMER</th>
                                        <th width="20%" class="text-right">AMOUNT</th>
                                        <th width="20%" class="text-right">INVOICED</th>
                                        <th width="20%" class="text-right">PAYMENT RECEIVED</th>
                                        <th width="20%" class="text-right">BALANCE</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="customer in customers" ng-show="customers.length">
                                        <td width="20%">
                                            <a target="_blank"
                                               href="{{ url('/') }}/sales/customer/@{{ customer.id }}">
                                                @{{ customer.display_name }}
                                            </a>
                                        </td>
                                        <td width="20%" class="text-right ">@{{ getSoTotalAmount(customer) |
                                            number:2 }}
                                        </td>

                                        <td width="20%" class="text-right ">@{{ getInvoiceedTotalAmount(customer) |
                                            number:2 }}
                                        </td>

                                        <td width="20%" class="text-right ">@{{ getPaymentTotalAmount(customer) |
                                            number:2 }}
                                        </td>

                                        <td width="20%" class="text-right ">@{{ getBalancedTotalAmount(customer) |
                                            number:2 }}
                                        </td>
                                    </tr>
                                    <tr ng-show="customers.length">
                                        <td colspan="" class="text-right td-bg-info"><b>GRAND TOTAL</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ so_total |
                                                number:2 }}</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ invoice_total | number:2
                                                }}</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ payment_total | number:2
                                                }}</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ balance | number:2
                                                }}</b></td>
                                    </tr>
                                    <tr ng-show="!customers.length">
                                        <td>No data to display...</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection
@section('script')
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script src="{{ asset('js/vendor/object-helper.js') }}"></script>
    @include('report.general.date.script')
    <script>
        app.controller('salesProductController', ['$scope', '$http', function ($scope, $http) {
            $scope.query = {
                date: new Date()
            };

            $scope.loading = true;
            $scope.customers = [];
            $scope.daterangeDD = $('.date-range');
            $scope.daterangeValue = '';
            $scope.length = 0;

            dateRangeDropDown($scope);

            $scope.so_total = 0;
            $scope.invoice_total = 0;
            $scope.payment_total = 0;
            $scope.balance = 0;
            $scope.generate = function () {
                $scope.endDate = $scope.date;
                $scope.loading = true;
                var orderRoute = '{{ route('report.customer.balance') }}';
                $http.get(orderRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.customers = response.data.customers;
                    $scope.so_total = response.data.so_total;
                    $scope.invoice_total = response.data.invoice_total;
                    $scope.payment_total = response.data.payment_total;
                    $scope.balance = response.data.balance;
                    $scope.loading = false;
                });
            };
            $scope.generate();

            $scope.sum = function (array) {
                return _.reduce(array, function (memo, num) {
                    return memo + num;
                }, 0);
            };

            $scope.getSoTotalAmount = function (customer) {
                var orders = customer.orders;
                var amounts = _.pluck(orders, 'total');
                return $scope.sum(amounts);
            };

            $scope.getPaymentTotalAmount = function (customer) {
                var payments = customer.payments;
                var amounts = _.pluck(payments, 'payment');
                return $scope.sum(amounts);
            };

            $scope.getBalancedTotalAmount = function (customer) {
                var amount = $scope.getSoTotalAmount(customer) - $scope.getPaymentTotalAmount(customer);
                return amount;

            };

            $scope.getInvoiceedTotalAmount = function (customer) {
                var invoices = customer.invoices;
                var amounts = _.pluck(invoices, 'amount');
                return $scope.sum(amounts);
            };

            $scope.resetFilters = function () {
                dateRangeDropDown($scope);
                $scope.generate();
            };

            $scope.getExportRoute = function () {
                var route = '{{ route('report.customer.balance.export') }}';
                return route + '?' + $.param($scope.query);
            };
        }]);
    </script>
@endsection
