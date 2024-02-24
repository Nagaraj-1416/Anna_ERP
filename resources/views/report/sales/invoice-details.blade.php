@extends('layouts.master')
@section('title', 'Sales Invoice Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Reports') !!}
@endsection
@section('content')
    <section ng-controller="salesProductController">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-b-0">
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Sales Invoice Details</h3>
                    </div>
                    <hr>
                    <div class="card-body">
                        <!-- from to filter -->
                        <div class="form-filter">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group {{ $errors->has('balance_type') ? 'has-danger' : '' }}">
                                        <label class="control-label">With Balance / Zero Balance / Both</label>
                                        <div class="ui fluid  selection dropdown balance-type-drop-down {{ $errors->has('balance_type') ? 'error' : '' }}">
                                            <input type="hidden" name="balance_type" value="All">
                                            <i class="dropdown icon"></i>
                                            <div class="default text">choose a balance type</div>
                                            <div class="menu">
                                                @foreach(balanceTypeDropDown() as $key => $type)
                                                    <div class="item" data-value="{{ $key }}">{{ $type }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <p class="form-control-feedback">{{ $errors->first('balance_type') }}</p>
                                    </div>
                                </div>
                            </div>
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
                                <div class="col-md-3">
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
                            <h2 class="text-center"><b>Sales Invoice Details</b></h2>
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
                                        <th width="10%">ORDER#</th>
                                        <th width="8%">ORDER DATE</th>
                                        <th width="15%">REP</th>
                                        <th width="20%">CUSTOMER</th>
                                        {{--<th width="10%">INVOICE#</th>--}}
                                        <th width="10%">STATUS</th>
                                        <th width="10%" class="text-right">AMOUNT</th>
                                        <th width="10%" class="text-right">BALANCE</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="invoice in invoices" ng-show="length">
                                        <td width="10%">
                                            <a target="_blank"
                                               href="{{ url('/') }}/sales/order/@{{ invoice.sales_order_id }}">
                                                @{{ invoice.order.ref }}
                                            </a>
                                        </td>
                                        <td width="8%">@{{ invoice.invoice_date }}</td>
                                        <td width="15%">@{{ invoice.order.sales_rep.name }}</td>
                                        <td width="20%">
                                            <a target="_blank"
                                               href="{{ url('/') }}/sales/customer/@{{ invoice.customer.id }}">
                                                @{{ invoice.customer.display_name }}
                                            </a>
                                        </td>
                                        {{--<td width="10%">
                                            <a target="_blank"
                                               href="{{ url('/') }}/sales/invoice/@{{ invoice.id }}">
                                                @{{ invoice.ref }}
                                            </a>
                                        </td>--}}
                                        <td width="10%">@{{ invoice.status }}</td>
                                        <td width="10%" class="text-right ">@{{ invoice.amount | number:2 }}</td>
                                        <td width="10%" class="text-right ">@{{ getBalance(invoice) | number:2 }}</td>
                                    </tr>
                                    <tr ng-show="length">
                                        <td colspan="5" class="text-right td-bg-info"><b>GRAND TOTAL</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ invoice_total | number:2 }}</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ balance | number:2 }}</b></td>
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
        app.controller('salesProductController', ['$scope', '$http', function ($scope, $http) {
            $scope.query = {
                fromDate: '',
                toDate: '',
                company: '',
                route: '',
                rep: '',
                balanceType: ''
            };
            $scope.loading = true;
            $scope.orders = [];
            $scope.daterangeDD = $('.date-range');
            $scope.daterangeValue = '';
            $scope.dropdowns = {
                company: $('.company-drop-down'),
                route: $('.route-drop-down'),
                rep: $('.rep-drop-down'),
                balanceType: $('.balance-type-drop-down')
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

            $scope.dropdowns.balanceType.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val, name) {
                    $scope.query.balanceType = val;
                }
            });

            //Initiate Date Range Drop down
            dateRangeDropDown($scope);

            $scope.length = 0;
            $scope.invoice_total = 0;
            $scope.balance = 0;
            $scope.balType = null;

            // Generate Data using filters
            $scope.generate = function () {
                $scope.fromDate = $scope.query.fromDate;
                $scope.toDate = $scope.query.toDate;
                $scope.balType = $scope.query.balanceType;

                $scope.loading = true;
                var orderRoute = '{{ route('report.invoice.details') }}';
                $http.get(orderRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.invoices = response.data.invoices;
                    $scope.invoice_total = response.data.invoice_total;
                    $scope.balance = response.data.balance;
                    $scope.balType = response.data.balanceType;
                    $scope.loading = false;
                    $scope.length = _.toArray($scope.invoices).length;
                })
            };
            $scope.generate();

            // Reset Filters
            $scope.resetFilters = function () {
                $scope.query = {
                    fromDate: '',
                    toDate: ''
                };
                dateRangeDropDown($scope);
                $scope.dropdowns.company.dropdown('clear');
                $scope.dropdowns.route.dropdown('clear');
                $scope.dropdowns.rep.dropdown('clear');
                $scope.dropdowns.balanceType.dropdown('All');
                $scope.generate();
            };

            $scope.sum = function (array) {
                return _.reduce(array, function (memo, num) {
                    return memo + num;
                }, 0);
            };

            $scope.getBalance = function (invoice) {
                var payments = invoice.payments;
                var paymentsTotal = $scope.getPaymentTotal(payments);
                return (invoice.amount - paymentsTotal);
            };

            $scope.getPaymentTotal = function (payments) {
                var amounts = _.pluck(payments, 'payment');
                return $scope.sum(amounts);
            };

            $scope.getExportRoute = function () {
                var route = '{{ route('report.invoice.details.export') }}';
                return route + '?' + $.param($scope.query);
            };
        }]);
    </script>
@endsection