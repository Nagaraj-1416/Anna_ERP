@extends('layouts.master')
@section('title', 'Credit Orders')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <section ng-controller="salesProductController">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-b-0">
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Credit Orders</h3>
                    </div>
                    <hr>
                    <div class="card-body">
                        <!-- from to filter -->
                        <div class="form-filter">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group {{ $errors->has('company_id') ? 'has-danger' : '' }}">
                                        <label class="control-label">Company</label>
                                        <div class="ui fluid  search selection dropdown company-drop-down {{ $errors->has('company_id') ? 'error' : '' }}">
                                            <input type="hidden" name="company_id">
                                            <i class="dropdown icon"></i>
                                            <div class="default text">choose a company</div>
                                            <div class="menu">
                                                @foreach(companyDropDown() as $key => $company)
                                                    <div class="item" data-value="{{ $key }}">{{ $company }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <p class="form-control-feedback">{{ $errors->first('company_id') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group {{ $errors->has('route_id') ? 'has-danger' : '' }}">
                                        <label class="control-label">Route</label>
                                        <div class="ui fluid  search selection dropdown route-drop-down {{ $errors->has('route_id') ? 'error' : '' }}">
                                            <input type="hidden" name="route_id">
                                            <i class="dropdown icon"></i>
                                            <div class="default text">choose a route</div>
                                            <div class="menu">
                                                @foreach(routeDropDown() as $key => $route)
                                                    <div class="item" data-value="{{ $key }}">{{ $route }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <p class="form-control-feedback">{{ $errors->first('route_id') }}</p>
                                    </div>
                                </div>
                                {{--<div class="col-md-3">
                                    <div class="form-group {{ $errors->has('rep_id') ? 'has-danger' : '' }}">
                                        <label class="control-label">Rep</label>
                                        <div class="ui fluid  search selection dropdown rep-drop-down {{ $errors->has('rep_id') ? 'error' : '' }}">
                                            <input type="hidden" name="rep_id">
                                            <i class="dropdown icon"></i>
                                            <div class="default text">choose a rep</div>
                                            <div class="menu">
                                                @foreach(repDropDown() as $key => $rep)
                                                    <div class="item" data-value="{{ $key }}">{{ $rep }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <p class="form-control-feedback">{{ $errors->first('rep_id') }}</p>
                                    </div>
                                </div>--}}
                            </div>
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
                                <a href="@{{ getExportRoute() }}" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i>
                                    Export to PDF
                                </a>
                            </div>
                        </div>
                        <hr class="hr-dark">
                        <!-- heading section -->
                        <div class="heading-section">
                            <h2 class="text-center"><b>Credit Orders</b></h2>
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
                                        <th width="10%">ORDER DATE</th>
                                        <th>CUSTOMER</th>
                                        <th width="10%" class="text-right">AMOUNT</th>
                                        <th width="10%" class="text-right">PAID</th>
                                        <th width="10%" class="text-right">BALANCE</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="order in orders" ng-show="length">
                                        <td width="12%">
                                            <a target="_blank"
                                               href="{{ url('/') }}/sales/order/@{{ invoice.sales_order_id }}">
                                                @{{ order.ref }}
                                            </a>
                                        </td>
                                        <td width="10%">@{{ order.order_date }}</td>
                                        <td>
                                            <a target="_blank"
                                               href="{{ url('/') }}/sales/customer/@{{ order.customer.id }}">
                                                @{{ order.customer.display_name }}
                                            </a>
                                        </td>
                                        <td width="10%" class="text-right ">@{{ order.total | number:2 }}</td>
                                        <td width="10%" class="text-right ">@{{ getPaid(order) | number:2 }}</td>
                                        <td width="10%" class="text-right ">@{{ getBalance(order) | number:2 }}</td>
                                    </tr>
                                    <tr ng-show="length">
                                        <td colspan="3" class="text-right td-bg-info"><b>GRAND TOTAL</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ order_total | number:2 }}</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ payment_total | number:2 }}</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ balance | number:2 }}</b></td>
                                    </tr>
                                    <tr ng-show="!length">
                                        <td colspan="7">No data to display...</td>
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
        app.controller('salesProductController', ['$scope', '$http', function ($scope, $http) {
            $scope.query = {
                company: '',
                route: '',
                rep: '',
            };

            $scope.orders = [];
            $scope.dropdowns = {
                company: $('.company-drop-down'),
                route: $('.route-drop-down'),
                rep: $('.rep-drop-down')
            };

            $scope.dropdowns.company.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val, name) {
                    $scope.query.company = val;
                    $scope.dropdowns.route.dropdown('clear');
                    $scope.dropdowns.rep.dropdown('clear');
                    routeDropDown(val);
                    repDropDown(val);
                }
            });

            function routeDropDown(company) {
                var url = '{{ route('setting.route.by.company.search', ['companyId']) }}';
                url = url.replace('companyId', company);
                $scope.dropdowns.route.dropdown('setting', {
                    forceSelection: false,
                    apiSettings: {
                        url: url + '/{query}',
                        cache:false,
                    },
                    saveRemoteData:false,
                    onChange: function(val, name){
                        $scope.query.route = val;
                    }
                });
            }

            function repDropDown(company) {
                var url = '{{ route('setting.rep.by.company.search', ['repId']) }}';
                url = url.replace('repId', company);
                $scope.dropdowns.rep.dropdown('setting', {
                    forceSelection: false,
                    apiSettings: {
                        url: url + '/{query}',
                        cache:false,
                    },
                    saveRemoteData:false,
                    onChange: function(val, name){
                        $scope.query.rep = val;
                    }
                });
            }

            $scope.length = 0;
            $scope.order_total = 0;
            $scope.payment_total = 0;
            $scope.balance = 0;

            // Generate Data using filters
            $scope.generate = function () {
                $scope.loading = true;
                var orderRoute = '{{ route('sales.credit.orders') }}';
                $http.get(orderRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.orders = response.data.orders;
                    $scope.order_total = response.data.order_total;
                    $scope.payment_total = response.data.payment_total;
                    $scope.balance = response.data.balance;
                    $scope.loading = false;
                    $scope.length = _.toArray($scope.orders).length;
                })
            };

            // Reset Filters
            $scope.resetFilters = function () {
                $scope.dropdowns.company.dropdown('clear');
                $scope.dropdowns.route.dropdown('clear');
                $scope.dropdowns.rep.dropdown('clear');
                $scope.generate();
            };

            $scope.sum = function (array) {
                return _.reduce(array, function (memo, num) {
                    return memo + num;
                }, 0);
            };

            $scope.getBalance = function (order) {
                var payments = order.payments;
                var paymentsTotal = $scope.getPaymentTotal(payments);
                return (order.total - paymentsTotal);
            };

            $scope.getPaid = function (order) {
                var payments = order.payments;
                var paymentsTotal = $scope.getPaymentTotal(payments);
                return paymentsTotal
            };

            $scope.getPaymentTotal = function (payments) {
                var amounts = _.pluck(payments, 'payment');
                return $scope.sum(amounts);
            };

            $scope.getExportRoute = function () {
                var route = '{{ route('sales.credit.orders.export') }}';
                return route + '?' + $.param($scope.query);
            };
        }]);
    </script>
@endsection