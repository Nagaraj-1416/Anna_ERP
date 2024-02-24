@extends('layouts.master')
@section('title', 'Reimbursements')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Reports') !!}
@endsection
@section('content')
    <section ng-controller="SalesRepController">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline-inverse">
                    <div class="card-body p-b-0">
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Reimbursements by Employee</h3>
                    </div>
                    <hr>
                    <div class="card-body">
                        <!-- from to filter -->
                        <div class="form-filter">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group {{ $errors->has('staff') ? 'has-danger' : '' }}">
                                        <label class="control-label">Expenses Employee</label>
                                        <div class="ui fluid  search selection dropdown staff-drop-down {{ $errors->has('staff') ? 'error' : '' }}">
                                            <input type="hidden" name="staff">
                                            <i class="dropdown icon"></i>
                                            <div class="default text">choose a staff</div>
                                            <div class="menu">
                                                @foreach(staffsDropdown() as $key => $type)
                                                    <div class="item" data-value="{{ $key }}">{{ $type }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <p class="form-control-feedback">{{ $errors->first('staff') }}</p>
                                    </div>
                                </div>
                                {{--<div class="col-md-3">--}}
                                    {{--<div class="form-group {{ $errors->has('business_type_id') ? 'has-danger' : '' }}">--}}
                                        {{--<label class="control-label">Business type</label>--}}
                                        {{--<div class="ui fluid  search selection dropdown type-drop-down {{ $errors->has('business_type_id') ? 'error' : '' }}">--}}
                                            {{--<input type="hidden" name="business_type_id">--}}
                                            {{--<i class="dropdown icon"></i>--}}
                                            {{--<div class="default text">choose a business type</div>--}}
                                            {{--<div class="menu">--}}
                                                {{--@foreach(businessTypeDropDown() as $key => $type)--}}
                                                    {{--<div class="item" data-value="{{ $key }}">{{ $type }}</div>--}}
                                                {{--@endforeach--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<p class="form-control-feedback">{{ $errors->first('business_type_id') }}</p>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
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
                                <a href="@{{ getExportRoute() }}" class="btn btn-danger"><i
                                            class="fa fa-file-pdf-o"></i>
                                    Export to PDF</a>
                            </div>
                        </div>
                        <hr class="hr-dark">
                        <!-- heading section -->
                        <div class="heading-section">
                            <h2 class="text-center"><b>Reimbursements</b></h2>
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
                                        <th width="10%">REPORT#</th>
                                        <th width="15%">FROM</th>
                                        <th width="15%">TO</th>
                                        <th width="15%">STATUS</th>
                                        <th width="15%">REIMBURSE DATE</th>
                                        <th width="10%" class="text-right">AMOUNT</th>
                                        <th width="10%" class="text-right">AMOUNT</th>
                                        <th width="10%" class="text-right">BALANCE</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="(key, datas) in reimburses" ng-show="length">
                                        <td colspan="8" class="no-padding-tbl-cel">
                                            <a class="m-l-10" target="_blank"
                                               href="{{ url('/') }}/setting/staff/@{{ getEmployee(key).id }}"><b> @{{
                                                    getEmployee(key).name
                                                    }}</b></a>

                                            <hr>
                                            <table class="table no-border">
                                                <tbody>
                                                <tr ng-repeat="reimburse in datas" ng-show="length">
                                                    <td width="10%">
                                                        <a target="_blank"
                                                           href="{{ url('/') }}/expense/reports/@{{ reimburse.report.id }}">
                                                            @{{ reimburse.report.report_no }}
                                                        </a>
                                                    </td>
                                                    <td width="15%">@{{ reimburse.report.report_from }}</td>
                                                    <td width="15%">@{{ reimburse.report.report_to }}</td>
                                                    <td width="15%">@{{ reimburse.report.status }}</td>
                                                    <td width="15%">@{{ reimburse.reimbursed_on }}</td>
                                                    <td width="10%" class="text-right">@{{ reimburse.report.amount |
                                                        number:2
                                                        }}
                                                    </td>
                                                    <td width="10%" class="text-right">@{{ reimburse.amount | number:2
                                                        }}
                                                    </td>
                                                    <td width="10%" class="text-right">@{{ getBalance(reimburse) |
                                                        number:2
                                                        }}
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr ng-show="length">
                                        <td colspan="5" class="text-right td-bg-info"><b>TOTAL</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ reportTotal | number:2
                                                }}</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ total | number:2
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
        app.controller('SalesRepController', ['$scope', '$http', function ($scope, $http) {
            var employee = @json(\App\User::all()->toArray());
            $scope.loading = true;
            $scope.reimburses = [];
            $scope.total = 0;
            $scope.length = 0;
            $scope.reportTotal = 0;
            $scope.balance = 0;
            $scope.query = {
                fromDate: '{{ carbon()->toDateString() }}',
                toDate: '{{ carbon()->toDateString() }}',
                staff: null,
                type: null
            };
            //dropdowns
            $scope.dropdowns = {
                type: $('.type-drop-down'),
                staff: $('.staff-drop-down'),
            };

            // Business Type Drop Down
            $scope.dropdowns.type.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val, name) {
                    $scope.query.type = val;
                }
            });

            $scope.dropdowns.staff.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    $scope.query.staff = val;
                }
            });

            //Initiate Date Range Drop down
            dateRangeDropDown($scope);
            // Generate Data using filters
            $scope.generate = function () {
                $scope.fromDate = $scope.query.fromDate;
                $scope.toDate = $scope.query.toDate;
                $scope.loading = true;
                var orderRoute = '{{ route('report.expense.reimbursements') }}';
                $http.get(orderRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.reimburses = response.data.reimburses;
                    $scope.total = response.data.reimburses_total;
                    $scope.reportTotal = response.data.report_total;
                    $scope.balance = response.data.balance;
                    $scope.length = _.toArray($scope.reimburses).length;
                    $scope.loading = false;
                })
            };
            $scope.generate();
            $scope.getEmployee = function (key) {
                var object = _.find(employee, function (value, index) {
                    return value.id === parseInt(key);
                });
                return object;
            };

            $scope.resetFilters = function () {
                $scope.dropdowns.type.dropdown('clear');
                $scope.dropdowns.staff.dropdown('clear');
                dateRangeDropDown($scope);
                $scope.generate();
            };

            $scope.getBalance = function (reimburse) {
                return (parseInt(reimburse.report.amount) - parseInt(reimburse.amount));
            };

            $scope.getExportRoute = function () {
                var route = '{{ route('report.expense.reimbursements.export') }}';
                return route + '?' + $.param($scope.query);
            }

        }]);
    </script>
@endsection