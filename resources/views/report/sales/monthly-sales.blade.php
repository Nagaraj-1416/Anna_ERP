@extends('layouts.master')
@section('title', 'Monthly Sales')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Reports') !!}
@endsection
@section('content')
    <section ng-controller="salesCustomerController">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-b-0">
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Monthly Sales</h3>
                    </div>
                    <hr>
                    <div class="card-body">
                        <!-- from to filter -->
                        <div class="form-filter">
                            <div class="form-filter">
                                <div class="row">
                                    <div class="col-md-6">
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
                                </div>
                            @include('report.general.month.range')
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
                            <h2 class="text-center"><b>Monthly Sales</b></h2>
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
                                        <th>Customer</th>
                                        <th class="text-right" ng-repeat="(key, date) in dates">@{{ date }}</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="(customer, amount) in data" ng-show="length">
                                        <td>
                                            <a class="m-l-10" target="_blank"
                                               href="{{ url('/') }}/sales/customer/@{{ getCustomer(customer).id }}"><b>@{{
                                                    getCustomer(customer).display_name }}</b></a>
                                        </td>
                                        <td class="text-right" ng-repeat="value in amount">@{{ value | number:2 }}</td>
                                        <td class="text-right">@{{ sum(amount) | number:2 }}</td>
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
    @include('report.general.month.script')
    <script>
        app.controller('salesCustomerController', ['$scope', '$http', function ($scope, $http) {
            $scope.query = {
                year: new Date().getFullYear(),
                fromMonth: moment(new Date()).format('MMM'),
                toMonth: moment(new Date()).format('MMM'),
                company: ''
            };

            $scope.loading = true;
            $scope.orders = [];
            $scope.daterangeDD = $('.date-range');
            $scope.daterangeValue = '';
            $scope.dropdowns = {
                company: $('.company-drop-down')
            };

            $scope.dropdowns.company.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val, name) {
                    $scope.query.company = val;
                }
            });

            $scope.handleChange = function () {
                initDatePickers($scope);
            };
            initDatePickers($scope);

            $scope.handleFromDateChange = function () {
                initDatePickers($scope, true);
            };

            $scope.length = 0;
            $scope.bill_total = 0;
            $scope.payment_total = 0;
            $scope.balance = 0;
            // Generate Data using filters
            $scope.generate = function () {
                $scope.fromDate = new Date(moment().set('year', $scope.query.year).month($scope.query.fromMonth).startOf('month'));
                $scope.toDate = new Date(moment().set('year', $scope.query.year).month($scope.query.toMonth).endOf('month'));
                $scope.loading = true;
                var orderRoute = '{{ route('report.monthly.sales') }}';
                $http.get(orderRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.dates = response.data.dates;
                    $scope.data = response.data.data;
                    $scope.customer = response.data.customer;
                    $scope.dates = response.data.dates;
                    $scope.length = _.toArray($scope.data).length;
                    $scope.loading = false;
                })
            };
            $scope.generate();
            // Reset Filters
            $scope.resetFilters = function () {
                $scope.query = {
                    year: new Date().getFullYear(),
                    fromMonth: moment(new Date()).format('MMM'),
                    toMonth: moment(new Date()).format('MMM'),
                    customer: '',
                };
                $scope.dropdowns.company.dropdown('clear');
                $scope.generate();
            };

            $scope.sum = function (array) {
                var sum = _.reduce(array, function (memo, num) {
                    return memo + num;
                }, 0);
                return sum;
            };

            $scope.getCustomer = function (id) {
                if ($scope.customer.hasOwnProperty(id)) return $scope.customer[id];
                return [];
            };

            $scope.getExportRoute = function () {
                var route = '{{ route('report.monthly.sales.export') }}';
                return route + '?' + $.param($scope.query);
            };
        }]);
    </script>
@endsection