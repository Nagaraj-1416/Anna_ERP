@extends('layouts.master')
@section('title', 'Sales by Route')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Reports') !!}
@endsection
@section('content')
    <section ng-controller="SalesByRouteController">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-b-0">
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Sales by Route</h3>
                    </div>
                    <hr>
                    <div class="card-body">
                        <!-- from to filter -->
                        <div class="form-filter">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group {{ $errors->has('rep_id') ? 'has-danger' : '' }}">
                                        <label class="control-label">Route</label>
                                        <div class="ui fluid  search selection dropdown route-drop-down {{ $errors->has('route_id') ? 'error' : '' }}">
                                            <input type="hidden" name="route_id">
                                            <i class="dropdown icon"></i>
                                            <div class="default text">choose a route</div>
                                            <div class="menu"></div>
                                        </div>
                                        <p class="form-control-feedback">{{ $errors->first('route_id') }}</p>
                                    </div>
                                </div>
                            </div>
                            @include('report.general.date-range')
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
                            <div class="pull-right"></div>
                        </div>
                        <hr class="hr-dark">
                        <!-- heading section -->
                        <div class="heading-section">
                            <h2 class="text-center"><b>Sales by Route</b></h2>
                            <p class="text-center text-muted"><b>From</b> @{{ fromDate | date}}
                                <b>To</b> @{{ toDate | date}}</p>
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
                                        <th width="12%">ORDER#</th>
                                        <th width="15%">ORDER DATE & TIME</th>
                                        <th>CUSTOMER</th>
                                        <th width="10%" class="text-center">CASH/CREDIT</th>
                                        <th width="10%" class="text-center">STATUS</th>
                                        <th width="10%" class="text-right">AMOUNT</th>
                                        <th width="10%" class="text-right">RECEIVED</th>
                                        <th width="10%" class="text-right">BALANCE</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="(key, datas) in orders" ng-show="length">
                                        <td colspan="8" class="no-padding-tbl-cel">
                                            <a ng-show="getRoute(key)" class="m-l-10" target="_blank"
                                               href="{{ url('/') }}/setting/route/@{{ getRoute(key).id }}"><b>@{{
                                                    getRoute(key) ? getRoute(key).name : ''  }}</b></a>

                                            <a ng-hide="getRoute(key)" class="m-l-10" target="_blank"><b>@{{ 'Others' }}</b></a>
                                            <hr>
                                            <table class="table no-border">
                                                <tbody>
                                                <tr ng-repeat="order in datas" ng-show="length">
                                                    <td width="12%">
                                                        <a target="_blank"
                                                           href="{{ url('/') }}/sales/order/@{{ order.id }}">
                                                            @{{ order.ref }}
                                                        </a>
                                                    </td>
                                                    <td width="15%">@{{ order.createdAt }}</td>
                                                    <td>
                                                        <a target="_blank" href="/sales/customer/@{{ order.customer.id }}">@{{ order.customer.display_name }}</a>
                                                    </td>
                                                    <td width="10%" class="text-center">
                                                        <span ng-if="order.is_credit_sales == 'Yes'" class="text-danger">Credit</span>
                                                        <span ng-if="order.is_credit_sales == 'No' && order.status == 'Closed'" class="text-green">Cash</span>
                                                    </td>
                                                    <td width="10%" class="text-center">
                                                        <span ng-if="order.status == 'Open'" class="text-warning">@{{ order.status }}</span>
                                                        <span ng-if="order.status == 'Closed'" class="text-green">@{{ order.status }}</span>
                                                    </td>
                                                    <td width="10%" class="text-right ">@{{ order.total | number:2 }}</td>
                                                    <td width="10%" class="text-right ">@{{ getTotal(order) | number:2 }}</td>
                                                    <td width="10%" class="text-right ">@{{ getBalanced(order) | number:2 }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr ng-show="length">
                                        <td colspan="5" class="text-right td-bg-info"><b>GRAND TOTAL</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ order_total |
                                                number:2 }}</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ payment_total | number:2
                                                }}</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ balance | number:2
                                                }}</b></td>
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
    @include('report.general.date-range-script')
    <script>
        app.controller('SalesByRouteController', ['$scope', '$http', function ($scope, $http) {
            $scope.query = {
                fromDate: '',
                toDate: '',
                route: ''
            };
            $scope.loading = true;
            $scope.orders = [];
            $scope.daterangeDD = $('.date-range');
            $scope.daterangeValue = '';
            $scope.dropdowns = {
                route: $('.route-drop-down')
            };

            // Route Drop Down
            $scope.dropdowns.route.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: '{{ route('setting.route.search') }}' + '/{query}',
                    cash: false
                },
                onChange: function (val, name) {
                    $scope.query.route = val;
                }
            });

            //Initiate Date Range Drop down
            dateRangeDropDown($scope);

            $scope.length = 0;
            $scope.invoice_total = 0;
            $scope.payment_total = 0;
            $scope.balance = 0;

            // Generate Data using filters
            $scope.generate = function () {
                $scope.fromDate = $scope.query.fromDate;
                $scope.toDate = $scope.query.toDate;
                $scope.loading = true;
                var orderRoute = '{{ route('report.sales.by.route') }}';
                $http.get(orderRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.orders = response.data.orders;
                    $scope.order_total = response.data.order_total;
                    $scope.invoice_total = response.data.invoice_total;
                    $scope.payment_total = response.data.payment_total;
                    $scope.balance = response.data.balance;
                    $scope.route = response.data.route;
                    $scope.loading = false;
                    $scope.length = _.toArray($scope.orders).length;
                })
            };
            $scope.generate();
            // Reset Filters
            $scope.resetFilters = function () {
                $scope.query = {
                    fromDate: '',
                    toDate: '',
                    route: ''
                };
                $scope.daterangeDD.dropdown('clear');
                $scope.dropdowns.route.dropdown('clear');
                $scope.daterangeDD.dropdown('set text', 'Today').dropdown('set value', 'today');
                $scope.generate();
            };

            $scope.sum = function (array) {
                var sum = _.reduce(array, function (memo, num) {
                    return memo + num;
                }, 0);
                return sum;
            };

            // Get Sales Order Balance Amount
            $scope.getBalanced = function (order) {
                return order.total - $scope.getTotal(order);
            };

            //Get Sales Order Payments Total
            $scope.getTotal = function (order) {
                var payments = order.payments;
                var amounts = _.pluck(payments, 'payment');
                var paid = $scope.sum(amounts);
                return paid;
            };

            // Get Order Bill Amount
            $scope.getBillTotal = function (order) {
                var invoices = order.invoices;
                var amounts = _.pluck(invoices, 'amount');
                var paid = $scope.sum(amounts);
                return paid;
            };

            $scope.getOrderTotal = function () {
                var amounts = _.pluck($scope.orders, 'total');
                var amount = $scope.sum(amounts);
                return amount;
            };

            $scope.getRoute = function (id) {
                return _.find($scope.route, function (value, key) {
                    if (value.id === parseInt(id)) return value;
                });
            };

        }]);
    </script>
@endsection