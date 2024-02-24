@extends('layouts.master')
@section('title', 'Aging Summary')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Reports') !!}
@endsection
@section('content')
    <section ng-controller="purchaseProductController">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-b-0">
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Aging Summary</h3>
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
                                <a  href="@{{ getExportRoute() }}" class="btn btn-danger"><i
                                            class="fa fa-file-pdf-o"></i>
                                    Export to PDF</a>
                            </div>
                        </div>
                        <hr class="hr-dark">
                        <!-- heading section -->
                        <div class="heading-section">
                            <h2 class="text-center"><b>Aging Summary</b></h2>
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
                                        <th width="15%">SUPPLIER</th>
                                        <th width="15%" class="text-right">1-30 DAYS</th>
                                        <th width="15%" class="text-right">31-60 DAYS</th>
                                        <th width="15%" class="text-right">61-90 DAYS</th>
                                        <th width="15%" class="text-right"> >90 DAYS</th>
                                        <th width="15%" class="text-right"> TOTAL</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="(key, bill) in bills" ng-show="lenght">
                                        <td width="15%">
                                            <a target="_blank"
                                               href="{{ url('/') }}/purchase/supplier/@{{ supplier.id }}">
                                                @{{ getSupplier(key).display_name }}
                                            </a>
                                        </td>
                                        <td width="15%" class="text-right ">@{{ getData('1-30', key) |
                                            number:2 }}
                                        </td>

                                        <td width="15%" class="text-right ">@{{ getData('31-60', key) |
                                            number:2 }}
                                        </td>

                                        <td width="15" class="text-right ">@{{ getData('61-90', key) |
                                            number:2 }}
                                        </td>

                                        <td width="15%" class="text-right ">@{{ getData('91', key) |
                                            number:2 }}
                                        </td>
                                        <td width="15%" class="text-right ">@{{ getTotal(key) |
                                            number:2 }}
                                        </td>
                                    </tr>
                                    <tr ng-show="lenght">
                                        <td class="text-right td-bg-info"><b>TOTAL</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ getFullTotal('1-30') |
                                                number:2 }}</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ getFullTotal('31-60') |
                                                number:2
                                                }}</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ getFullTotal('61-90') |
                                                number:2
                                                }}</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ getFullTotal('91') |
                                                number:2
                                                }}</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ getFinalTotal()|
                                                number:2
                                                }}</b></td>
                                    </tr>
                                    <tr ng-show="!lenght">
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
            $scope.lenght = 0;
            $scope.generate = function () {
                $scope.endDate = $scope.date;
                $scope.loading = true;
                var orderRoute = '{{ route('report.purchase.aging.summary') }}';
                $http.get(orderRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.bills = response.data.data;
                    $scope.suppliers = response.data.suppliers;
                    $scope.po_total = response.data.po_total;
                    $scope.bill_total = response.data.bill_total;
                    $scope.payment_total = response.data.payment_total;
                    $scope.balance = response.data.balance;
                    $scope.lenght = _.toArray($scope.bills).length;
                    $scope.loading = false;
                });
            };
            $scope.generate();

            $scope.sum = function (array) {
                return _.reduce(array, function (memo, num) {
                    return memo + num;
                }, 0);
            };


            $scope.getData = function (key, supplier) {
                if ($scope.bills.hasOwnProperty(supplier)) {
                    $scope.amount = $scope.bills[supplier][key];
                    return $scope.sum($scope.amount);
                }
            };

            $scope.getTotal = function (supplier) {
                var amount = 0;
                $.each($scope.bills[supplier], function (key, value) {
                    amount += $scope.sum(value)
                });

                return amount;
            };

            $scope.getSupplier = function (id) {
                var test = _.find($scope.suppliers, function (value, key) {
                    if (value.id === parseInt(id)) return value;
                });
                return test;
            };

            $scope.getFullTotal = function (key) {
                var array = _.pluck($scope.bills, key);
                var total = 0;
                $.each(array, function (key, value) {
                    total += $scope.sum(value)
                });
                return total;
            };

            $scope.getFinalTotal = function () {
                return $scope.getFullTotal('1-30') + $scope.getFullTotal('31-60') + $scope.getFullTotal('61-90') + $scope.getFullTotal('91');
            };

            $scope.resetFilters = function () {
                dateRangeDropDown($scope);
                $scope.generate();
            };

            $scope.getExportRoute = function () {
                var route = '{{ route('report.purchase.aging.summary.export') }}';
                return route + '?' + $.param($scope.query);
            }
        }]);
    </script>
@endsection
