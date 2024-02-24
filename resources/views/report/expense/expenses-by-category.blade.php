@extends('layouts.master')
@section('title', 'Expenses by Category')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Reports') !!}
@endsection
@section('content')
    <section ng-controller="SalesRepController">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-b-0">
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Expenses by Category</h3>
                    </div>
                    <hr>
                    <div class="card-body">
                        <!-- from to filter -->
                        <div class="form-filter">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group {{ $errors->has('category') ? 'has-danger' : '' }}">
                                        <label class="control-label">Expenses Category</label>
                                        <div class="ui fluid  search selection dropdown category-drop-down {{ $errors->has('category') ? 'error' : '' }}">
                                            <input type="hidden" name="category">
                                            <i class="dropdown icon"></i>
                                            <div class="default text">choose a category</div>
                                            <div class="menu"></div>
                                        </div>
                                        <p class="form-control-feedback">{{ $errors->first('category') }}</p>
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
                            <h2 class="text-center"><b>Expenses by Category</b></h2>
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
                                        <th width="12%">TYPE</th>
                                        <th width="12%">CUSTOMER</th>
                                        <th width="12%">SUPPLIER</th>
                                        <th width="15%">STATUS</th>
                                        <th width="10%" class="text-right">AMOUNT</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="(key, datas) in expenses" ng-show="length">
                                        <td colspan="7" class="no-padding-tbl-cel">
                                            <a class="m-l-10" target="_blank" href=""><b> @{{ getCategory(key)
                                                    }}</b></a>

                                            <hr>
                                            <table class="table no-border">
                                                <tbody>
                                                <tr ng-repeat="expense in datas" ng-show="length">
                                                    <td width="15%">
                                                        <a target="_blank"
                                                           href="{{ url('/') }}/expense/receipts/@{{ expense.id }}">
                                                            @{{ expense.expense_no }}
                                                        </a>
                                                    </td>
                                                    <td width="15%">@{{ expense.expense_date }}</td>
                                                    <td width="15%">@{{ expense.expense_type }}</td>
                                                    <td width="15%">
                                                        <a target="_blank" ng-show="expense.customer"
                                                           href="{{ url('/') }}/sales/customer/@{{ expense.customer.id }}">
                                                            @{{ expense.customer.display_name }}
                                                        </a>
                                                        <p ng-show="!expense.customer">None</p>
                                                    </td>
                                                    <td width="15%">
                                                        <a target="_blank" ng-show="expense.supplier"
                                                           href="{{ url('/') }}/purchase/supplier/@{{ expense.supplier.id }}">
                                                            @{{ expense.supplier.display_name }}
                                                        </a>
                                                        <p ng-show="!expense.supplier">None</p>
                                                    </td>
                                                    <td width="15%">@{{ expense.status }}</td>
                                                    <td width="10%" class="text-right">@{{ expense.amount | number:2
                                                        }}
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr ng-show="length">
                                        <td colspan="6" class="text-right td-bg-info"><b>TOTAL</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ total | number:2
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
            $scope.loading = true;
            $scope.expenses = [];
            $scope.total = 0;
            $scope.length = 0;
            $scope.query = {
                fromDate: '{{ carbon()->toDateString() }}',
                toDate: '{{ carbon()->toDateString() }}',
                category: null,
                type: null
            };
            //dropdowns
            $scope.dropdowns = {
                type: $('.type-drop-down'),
                category: $('.category-drop-down'),
            };
            $scope.urls = {
                expenseCategory: '{{ route('expense.category.search') }}',
            };

            // Business Type Drop Down
            $scope.dropdowns.type.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val, name) {
                    $scope.query.type = val;
                }
            });

            $scope.dropdowns.category.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: $scope.urls.expenseCategory + '/{query}',
                    cache: false
                },
                onChange: function (val) {
                    $scope.query.category = val;
                }
            });

            //Initiate Date Range Drop down
            dateRangeDropDown($scope);
            // Generate Data using filters
            $scope.generate = function () {
                $scope.fromDate = $scope.query.fromDate;
                $scope.toDate = $scope.query.toDate;
                $scope.loading = true;
                var orderRoute = '{{ route('report.expense.by.category') }}';
                $http.get(orderRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.expenses = response.data.expenses;
                    $scope.total = response.data.total;
                    $scope.length = _.toArray($scope.expenses).length;
                    $scope.loading = false;
                })
            };
            $scope.generate();

            $scope.getCategory = function (key) {
                var categories = @json(\App\ExpenseCategory::all()->toArray());
                var object = _.find(categories, function (value, index) {
                    return value.id === parseInt(key);
                });
                return object.name;
            };

            $scope.getExportRoute = function () {
                var route = '{{ route('report.expense.by.category.export') }}';
                return route + '?' + $.param($scope.query);
            }
        }]);
    </script>
@endsection