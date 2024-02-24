@extends('layouts.master')
@section('title', 'Aging Summary')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Reports') !!}
@endsection
@section('content')
    <section ng-controller="salesProductController">
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
                            <div class="row m-b-15">
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
                                        <th width="15%">CUSTOMER</th>
                                        <th width="15%" class="text-right">1-30 DAYS</th>
                                        <th width="15%" class="text-right">31-60 DAYS</th>
                                        <th width="15%" class="text-right">61-90 DAYS</th>
                                        <th width="15%" class="text-right"> >90 DAYS</th>
                                        <th width="15%" class="text-right">TOTAL</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="(key, invoice) in invoices" ng-show="lenght">
                                        <td width="15%">
                                            <a target="_blank"
                                               href="{{ url('/') }}/sales/customer/@{{ customer.id }}">
                                                @{{ getCustomer(key).display_name }}
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
                                        <td class="text-right td-bg-info"><b>GRAND TOTAL</b></td>
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
        app.controller('salesProductController', ['$scope', '$http', function ($scope, $http) {
            $scope.query = {
                //date: new Date(),
                date: '',
                company: '',
                route: '',
                rep: ''
            };

            $scope.customers = [];
            $scope.daterangeDD = $('.date-range');
            $scope.daterangeValue = '';
            $scope.length = 0;
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

            dateRangeDropDown($scope);
            $scope.so_total = 0;
            $scope.invoice_total = 0;
            $scope.payment_total = 0;
            $scope.balance = 0;
            $scope.lenght = 0;

            $scope.generate = function () {
                $scope.endDate = $scope.date;
                $scope.loading = true;
                var orderRoute = '{{ route('report.aging.summary') }}';
                $http.get(orderRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.invoices = response.data.data;
                    $scope.customers = response.data.customers;
                    $scope.so_total = response.data.so_total;
                    $scope.invoice_total = response.data.invoice_total;
                    $scope.payment_total = response.data.payment_total;
                    $scope.balance = response.data.balance;
                    $scope.lenght = _.toArray($scope.invoices).length;
                    $scope.loading = false;
                });
            };
            //$scope.generate();

            $scope.sum = function (array) {
                return _.reduce(array, function (memo, num) {
                    return memo + num;
                }, 0);
            };

            $scope.getData = function (key, customer) {
                if ($scope.invoices.hasOwnProperty(customer)) {
                    $scope.amount = $scope.invoices[customer][key];
                    return $scope.sum($scope.amount);
                }
            };

            $scope.getTotal = function (customer) {
                var amount = 0;
                $.each($scope.invoices[customer], function (key, value) {
                    amount += $scope.sum(value)
                });
                return amount;
            };

            $scope.getCustomer = function (id) {
                var test = _.find($scope.customers, function (value, key) {
                    if (value.id === parseInt(id)) return value;
                });
                return test;
            };

            $scope.getFullTotal = function (key) {
                var array = _.pluck($scope.invoices, key);
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
                $scope.dropdowns.company.dropdown('clear');
                $scope.dropdowns.route.dropdown('clear');
                $scope.dropdowns.rep.dropdown('clear');
                $scope.generate();
            };

            $scope.getExportRoute = function () {
                var route = '{{ route('report.aging.summary.export') }}';
                return route + '?' + $.param($scope.query);
            };
        }]);
    </script>
@endsection