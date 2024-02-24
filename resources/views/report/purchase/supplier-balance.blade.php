@extends('layouts.master')
@section('title', 'Supplier Balances')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Reports') !!}
@endsection
@section('content')
    <section ng-controller="purchaseProductController">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-b-0">
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Supplier Balances</h3>
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
                                <a href="@{{ getExportRoute() }}" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i>
                                    Export to PDF</a>
                            </div>
                        </div>
                        <hr class="hr-dark">
                        <!-- heading section -->
                        <div class="heading-section">
                            <h2 class="text-center"><b>Supplier Balances</b></h2>
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
                                        <th width="20%">SUPPLIER</th>
                                        <th width="20%" class="text-right">PO AMOUNT</th>
                                        <th width="20%" class="text-right">BILLED</th>
                                        <th width="20%" class="text-right">PAYMENT MADE</th>
                                        <th width="20%" class="text-right">BALANCE</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="supplier in suppliers" ng-show="suppliers.length">
                                        <td width="20%">
                                            <a target="_blank"
                                               href="{{ url('/') }}/purchase/supplier/@{{ supplier.id }}">
                                                @{{ supplier.display_name }}
                                            </a>
                                        </td>
                                        <td width="20%" class="text-right ">@{{ getPoTotalAmount(supplier) |
                                            number:2 }}
                                        </td>

                                        <td width="20%" class="text-right ">@{{ getBilledTotalAmount(supplier) |
                                            number:2 }}
                                        </td>

                                        <td width="20%" class="text-right ">@{{ getPaymentTotalAmount(supplier) |
                                            number:2 }}
                                        </td>

                                        <td width="20%" class="text-right ">@{{ getBalancedTotalAmount(supplier) |
                                            number:2 }}
                                        </td>
                                    </tr>
                                    <tr ng-show="suppliers.length">
                                        <td colspan="" class="text-right td-bg-info"><b>TOTAL</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ po_total |
                                                number:2 }}</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ bill_total | number:2
                                                }}</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ payment_total | number:2
                                                }}</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ balance | number:2
                                                }}</b></td>
                                    </tr>
                                    <tr ng-show="!suppliers.length">
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
        app.controller('purchaseProductController', ['$scope', '$http', function ($scope, $http) {
            $scope.query = {
                date: new Date()
            };

            $scope.loading = true;
            $scope.suppliers = [];
            $scope.daterangeDD = $('.date-range');
            $scope.daterangeValue = '';
            $scope.length = 0;

            dateRangeDropDown($scope);
            $scope.po_total = 0;
            $scope.bill_total = 0;
            $scope.payment_total = 0;
            $scope.balance = 0;
            $scope.generate = function () {
                $scope.endDate = $scope.date;
                $scope.loading = true;
                var orderRoute = '{{ route('report.supplier.balance') }}';
                $http.get(orderRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.suppliers = response.data.suppliers;
                    $scope.po_total = response.data.po_total;
                    $scope.bill_total = response.data.bill_total;
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

            $scope.getPoTotalAmount = function (supplier) {
                var orders = supplier.orders;
                var amounts = _.pluck(orders, 'total');
                return $scope.sum(amounts);
            };

            $scope.getPaymentTotalAmount = function (supplier) {
                var payments = supplier.payments;
                var amounts = _.pluck(payments, 'payment');
                return $scope.sum(amounts);
            };

            $scope.getBalancedTotalAmount = function (supplier) {
                var amount = $scope.getPoTotalAmount(supplier) - $scope.getPaymentTotalAmount(supplier);
                return amount;

            };

            $scope.getBilledTotalAmount = function (supplier) {
                var bills = supplier.bills;
                var amounts = _.pluck(bills, 'amount');
                return $scope.sum(amounts);
            };

            $scope.resetFilters = function () {
                dateRangeDropDown($scope);
                $scope.generate();
            };

            $scope.getExportRoute = function () {
                var route = '{{ route('report.supplier.balance.export') }}';
                return route + '?' + $.param($scope.query);
            }
        }]);
    </script>
@endsection
