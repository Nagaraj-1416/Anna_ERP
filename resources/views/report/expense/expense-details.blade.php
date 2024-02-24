@extends('layouts.master')
@section('title', 'Expense Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Reports') !!}
@endsection
@section('content')
    <section ng-controller="ExpenseController">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-b-0">
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Expense by Company</h3>
                    </div>
                    <hr>
                    <div class="card-body">
                        <!-- from to filter -->
                        <div class="form-filter">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group required @{{ hasError('company') ? 'has-danger' : '' }}">
                                        <label class="control-label">Company</label>
                                        <div class="ui fluid search selection dropdown company-drop-down @{{ hasError('company') ? 'error' : '' }}">
                                            <input type="hidden" name="company">
                                            <i class="dropdown icon"></i>
                                            <div class="default text">choose a company</div>
                                            <div class="menu">
                                                @foreach(companyDropDown() as $key => $company)
                                                    <div class="item" data-value="{{ $key }}">{{ $company }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <p class="form-control-feedback">
                                            @{{ hasError('company') ? hasError('company') : ''}}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group required @{{ hasError('type') ? 'has-danger' : '' }}">
                                        <label class="control-label">Expense type</label>
                                        <div class="ui fluid search selection dropdown exp-type-drop-down @{{ hasError('type') ? 'error' : '' }}">
                                            <input type="hidden" name="type">
                                            <i class="dropdown icon"></i>
                                            <div class="default text">choose an expense type</div>
                                            <div class="menu">
                                                @foreach(expenseTypesDropDown() as $key => $expType)
                                                    <div class="item" data-value="{{ $key }}">{{ $expType }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <p class="form-control-feedback">
                                            @{{ hasError('company') ? hasError('type') : ''}}
                                        </p>
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
                            <h2 class="text-center"><b>Expense Details</b></h2>
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
                                        <th width="10%">EXPENSE#</th>
                                        <th width="12%">DATE</th>
                                        <th>OTHER DETAILS</th>
                                        <th width="12%">TYPE</th>
                                        <th width="10%" class="text-right">AMOUNT</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="expense in expenses" ng-show="expenses.length">

                                        <td width="10%">
                                            <a target="_blank"
                                               href="{{ url('/') }}/expense/receipts/@{{ expense.id }}">
                                                @{{ expense.expense_no }}
                                            </a>
                                        </td>
                                        <td width="15%">
                                            @{{ expense.expense_date }}<br />
                                            <b>Note: </b>@{{ expense.notes }}
                                        </td>
                                        <td>
                                            <div ng-if="expense.expense_category == 'Van'">
                                                <u>Allocation details</u><br />
                                                <b>Code: </b>
                                                <a href="{{ url('/') }}/sales/allocation/@{{ expense.sales_expense.daily_sale.id }}" target="_blank">
                                                    @{{ expense.sales_expense.daily_sale.code }}
                                                </a>
                                                <br />
                                                <b>Route: </b>@{{ expense.sales_expense.daily_sale.route.name }}<br />
                                                <b>Rep: </b>@{{ expense.sales_expense.daily_sale.rep.name }}
                                            </div>
                                        </td>
                                        <td width="15%">
                                            @{{ expense.type.name }}<br />
                                            <div ng-if="expense.type_id == '2'">
                                                <b>Ltr:</b> @{{ expense.liter }}<br />
                                                <b>ODO Reading: </b>@{{ expense.odometer }}
                                            </div>
                                        </td>
                                        <td width="10%" class="text-right">@{{ expense.amount | number:2 }}</td>
                                    </tr>
                                    <tr ng-show="expenses.length">
                                        <td colspan="4" class="text-right td-bg-info"><b>TOTAL</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ total | number:2 }}</b>
                                        </td>
                                    </tr>
                                    <tr ng-show="!expenses.length">
                                        <td colspan="5">No data to display...</td>
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
        app.controller('ExpenseController', ['$scope', '$http', function ($scope, $http) {
            //queries
            $scope.loading = true;
            $scope.expenses = [];
            $scope.total = 0;
            $scope.query = {
                fromDate: '{{ carbon()->toDateString() }}',
                toDate: '{{ carbon()->toDateString() }}',
                company: '',
                type: ''
            };

            //dropdowns
            $scope.dropdowns = {
                company: $('.company-drop-down'),
                type: $('.exp-type-drop-down')
            };

            $scope.dropdowns.company.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val, name) {
                    $scope.query.company = val;
                }
            });

            $scope.dropdowns.type.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val, name) {
                    $scope.query.type = val;
                }
            });

            //Date range section
            dateRangeDropDown($scope);

            //Generate Data
            $scope.generate = function () {
                $scope.fromDate = $scope.query.fromDate;
                $scope.toDate = $scope.query.toDate;
                $scope.loading = true;
                var expenseRoute = '{{ route('report.expense.details') }}';
                $http.get(expenseRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.expenses = response.data.expenses;
                    $scope.total = response.data.total;
                    $scope.loading = false;
                })
            };
            $scope.generate();

            //Reset filters
            $scope.resetFilters = function () {
                $scope.dropdowns.company.dropdown('clear');
                $scope.dropdowns.type.dropdown('clear');
                dateRangeDropDown($scope);
                $scope.generate();
            };

            $scope.getExportRoute = function () {
                var route = '{{ route('report.expense.details.export') }}';
                return route + '?' + $.param($scope.query);
            }
        }]);
    </script>
@endsection
