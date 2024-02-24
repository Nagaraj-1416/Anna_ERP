@extends('layouts.master')
@section('title', 'Aging Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Reports') !!}
@endsection
@section('content')
    <section ng-controller="purchaseProductController">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-b-0">
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Aging Details</h3>
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
                            <h2 class="text-center"><b>Aging Details</b></h2>
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
                                        <th width="10%">BILL#</th>
                                        <th width="15%">SUPPLIER</th>
                                        <th width="15%">BILL DATE</th>
                                        <th width="15%">DUE DATE</th>
                                        <th width="15%">AGE</th>
                                        <th>STATUS</th>
                                        <th width="10%" class="text-right">AMOUNT</th>
                                        <th width="10%" class="text-right">BALANCE</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="(key, datas) in bills" ng-show="length">
                                        <td colspan="8" class="no-padding-tbl-cel" ng-show="getLength(datas)">
                                            <P class="m-l-10"><b>@{{ key}}</b></P>
                                            <hr>
                                            <table class="table no-border">
                                                <tbody>
                                                <tr ng-repeat="bill in datas" ng-show="length">
                                                    <td width="10%">
                                                        <a target="_blank"
                                                           href="{{ url('/') }}/purchase/bill/@{{ bill.id }}">
                                                            @{{ bill.bill_no }}
                                                        </a>
                                                    </td>
                                                    <td width="15%">@{{ bill.supplier.display_name }}</td>
                                                    <td width="15%">@{{ bill.bill_date }}</td>
                                                    <td width="15%">@{{ bill.due_date }}</td>
                                                    <td width="15%">@{{ bill.age }}</td>
                                                    <td>@{{ bill.status }}</td>
                                                    <td width="10%" class="text-right ">@{{ bill.amount | number:2 }}
                                                    </td>
                                                    <td width="10%" class="text-right ">@{{ balance(bill) |
                                                        number:2 }}
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr ng-show="length">

                                    </tr>
                                    <tr ng-show="!length">
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
            $scope.bills = [];
            $scope.daterangeValue = '';
            $scope.length = 0;

            dateRangeDropDown($scope);

            $scope.generate = function () {
                $scope.endDate = $scope.date;
                $scope.loading = true;
                var orderRoute = '{{ route('report.purchase.aging.details') }}';
                $http.get(orderRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.bills = response.data.data;
                    $scope.length = 1;
                    $scope.loading = false;
                });
            };
            $scope.generate();

            $scope.sum = function (array) {
                return _.reduce(array, function (memo, num) {
                    return memo + num;
                }, 0);
            };

            $scope.balance = function (bill) {
                var payments = bill.payments;
                var amounts = _.pluck(payments, 'payment');
                return (bill.amount - $scope.sum(amounts));
            };

            $scope.getLength = function (object) {
                return _.toArray(object).length
            };

            $scope.resetFilters = function () {
                dateRangeDropDown($scope);
                $scope.generate();
            };

            $scope.getExportRoute = function () {
                var route = '{{ route('report.purchase.aging.details.export') }}';
                return route + '?' + $.param($scope.query);
            }

        }]);
    </script>
@endsection