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
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Sales Allocation Details</h3>
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
                                <div class="col-md-6">
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
                                <a href="@{{ getExportRoute() }}" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i>
                                    Export to PDF</a>
                            </div>
                        </div>
                        <hr class="hr-dark">
                        <!-- heading section -->
                        <div class="heading-section">
                            <h2 class="text-center"><b>Sales Allocation Details</b></h2>
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
                                        <th width="10%">Allocation#</th>
                                        <th>Route</th>
                                        <th width="10%" class="text-right">TOTAL SALES</th>
                                        <th width="10%" class="text-right">RECEIVED</th>
                                        <th width="10%" class="text-right">CASH RECEIVED</th>
                                        <th width="10%" class="text-right">CHEQUE RECEIVED</th>
                                        <th width="10%" class="text-right">BALANCE</th>
                                        <th width="10%" class="text-right">EXPENSES</th>
                                        <th width="10%" class="text-right">RETURNS</th>
                                        <th width="10%" class="text-right">COLLECTION</th>
                                        <th width="10%" class="text-right">CASH COLLECTION</th>
                                        <th width="10%" class="text-right">CHEQUE COLLECTION</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="allocation in allocations" ng-show="length">
                                        <td width="10%">
                                            <a target="_blank"
                                               href="{{ url('/') }}/sales/allocation/@{{ allocation.id }}/sales-sheet">
                                                @{{ allocation.code }} <br>
                                                @{{ allocation.from_date }}
                                            </a>
                                        </td>
                                        <td>@{{ allocation.route.name }}</td>
                                        <td width="10%" class="text-right">@{{ allocation.total_sales | number }}</td>
                                        <td width="10%" class="text-right">@{{ allocation.received | number }}</td>
                                        <td width="10%" class="text-right">@{{ allocation.cash_received | number }}</td>
                                        <td width="10%" class="text-right">@{{ allocation.cheque_received | number }}</td>
                                        <td width="10%" class="text-right">@{{ allocation.balance | number }}</td>
                                        <td width="10%" class="text-right">@{{ allocation.expenses | number }}</td>
                                        <td width="10%" class="text-right">@{{ allocation.returns | number }}</td>
                                        <td width="10%" class="text-right">@{{ allocation.old_received | number }}</td>
                                        <td width="10%" class="text-right">@{{ allocation.old_cash_received | number }}</td>
                                        <td width="10%" class="text-right">@{{ allocation.old_cheque_received | number }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-right"><b>TOTAL</b></td>
                                        <td width="10%" class="text-right"><b>@{{ allocation_total_sales | number }}</b></td>
                                        <td width="10%" class="text-right"><b>@{{ allocation_received | number }}</b></td>
                                        <td width="10%" class="text-right"><b>@{{ allocation_cash_received | number }}</b></td>
                                        <td width="10%" class="text-right"><b>@{{ allocation_cheque_received | number }}</b></td>
                                        <td width="10%" class="text-right"><b>@{{ allocation_balance | number }}</b></td>
                                        <td width="10%" class="text-right"><b>@{{ allocation_expenses | number }}</b></td>
                                        <td width="10%" class="text-right"><b>@{{ allocation_returns | number }}</b></td>
                                        <td width="10%" class="text-right"><b>@{{ allocation_old_received | number }}</b></td>
                                        <td width="10%" class="text-right"><b>@{{ allocation_old_cash_received | number }}</b></td>
                                        <td width="10%" class="text-right"><b>@{{ allocation_old_cheque_received | number }}</b></td>
                                    </tr>
                                    <tr ng-show="!length">
                                        <td colspan="11">No data to display...</td>
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
                rep: ''
            };
            $scope.loading = true;
            $scope.orders = [];
            $scope.daterangeDD = $('.date-range');
            $scope.daterangeValue = '';
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

            //Initiate Date Range Drop down
            dateRangeDropDown($scope);

            $scope.length = 0;

            // Generate Data using filters
            $scope.generate = function () {
                $scope.fromDate = $scope.query.fromDate;
                $scope.toDate = $scope.query.toDate;

                $scope.loading = true;
                var orderRoute = '{{ route('report.allocation.details') }}';
                $http.get(orderRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.allocations = response.data.allocations;
                    $scope.allocation_total_sales = response.data.allocation_total_sales;
                    $scope.allocation_received = response.data.allocation_received;
                    $scope.allocation_cash_received = response.data.allocation_cash_received;
                    $scope.allocation_cheque_received = response.data.allocation_cheque_received;
                    $scope.allocation_balance = response.data.allocation_balance;
                    $scope.allocation_expenses = response.data.allocation_expenses;
                    $scope.allocation_returns = response.data.allocation_returns;
                    $scope.allocation_old_received = response.data.allocation_old_received;
                    $scope.allocation_old_cash_received = response.data.allocation_old_cash_received;
                    $scope.allocation_old_cheque_received = response.data.allocation_old_cheque_received;
                    $scope.loading = false;
                    $scope.length = _.toArray($scope.allocations).length;
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
                var route = '{{ route('report.allocation.details.export') }}';
                return route + '?' + $.param($scope.query);
            };
        }]);
    </script>
@endsection