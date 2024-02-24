@extends('layouts.master')
@section('title', 'Unsubmitted Expenses')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Reports') !!}
@endsection
@section('content')
    <section ng-controller="ExpenseController">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-b-0">
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Unsubmitted Expenses</h3>
                    </div>
                    <hr>
                    <div class="card-body">
                        <!-- from to filter -->
                        <div class="form-filter">
                            <div class="row">
                                <div class="col-md-3">

                                    {{--business type dropdown--}}

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
                                </div>

                                {{--Customer DropDown--}}

                                {{--<div class="col-md-3">--}}
                                {{--<div class="form-group {{ $errors->has('customer') ? 'has-danger' : '' }}">--}}
                                {{--<label class="control-label">Customer</label>--}}
                                {{--<div class="ui fluid  search selection dropdown customer-drop-down {{ $errors->has('customer') ? 'error' : '' }}">--}}
                                {{--<input type="hidden" name="customer">--}}
                                {{--<i class="dropdown icon"></i>--}}
                                {{--<div class="default text">choose a customer</div>--}}
                                {{--<div class="menu">--}}
                                {{--@foreach(customerDropDown() as $key => $type)--}}
                                {{--<div class="item" data-value="{{ $key }}">{{ $type }}</div>--}}
                                {{--@endforeach--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<p class="form-control-feedback">{{ $errors->first('customer') }}</p>--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--Supplier DropDown--}}

                                {{--<div class="col-md-3">--}}
                                {{--<div class="form-group {{ $errors->has('supplier') ? 'has-danger' : '' }}">--}}
                                {{--<label class="control-label">Supplier</label>--}}
                                {{--<div class="ui fluid  search selection dropdown supplier-drop-down {{ $errors->has('supplier') ? 'error' : '' }}">--}}
                                {{--<input type="hidden" name="supplier">--}}
                                {{--<i class="dropdown icon"></i>--}}
                                {{--<div class="default text">choose a supplier</div>--}}
                                {{--<div class="menu">--}}
                                {{--@foreach(supplierDropDown() as $key => $type)--}}
                                {{--<div class="item" data-value="{{ $key }}">{{ $type }}</div>--}}
                                {{--@endforeach--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<p class="form-control-feedback">{{ $errors->first('supplier') }}</p>--}}
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
                                <button ng-click="resetFilters()" class="btn btn-black"><i class="ti-eraser"></i>
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
                            <h2 class="text-center"><b>Unsubmitted Expenses</b></h2>
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
                                    <tr ng-repeat="expense in expenses" ng-show="expenses.length">

                                        <td width="10%">
                                            <a target="_blank"
                                               href="{{ url('/') }}/expense/receipts/@{{ expense.id }}">
                                                @{{ expense.expense_no }}
                                            </a>
                                        </td>
                                        <td width="15%">@{{ expense.expense_date }}</td>
                                        <td width="15%">@{{ expense.expense_type }}</td>
                                        <td width="10%">
                                            <a target="_blank" ng-show="expense.customer"
                                               href="{{ url('/') }}/sales/customer/@{{ expense.customer.id }}">
                                                @{{ expense.customer.display_name }}
                                            </a>
                                            <p ng-show="!expense.customer">None</p>
                                        </td>
                                        <td width="10%">
                                            <a target="_blank" ng-show="expense.supplier"
                                               href="{{ url('/') }}/purchase/supplier/@{{ expense.supplier.id }}">
                                                @{{ expense.supplier.display_name }}
                                            </a>
                                            <p ng-show="!expense.supplier">None</p>
                                        </td>
                                        <td width="15%">@{{ expense.status }}</td>
                                        <td width="10%" class="text-right">@{{ expense.amount | number:2 }}</td>
                                    </tr>
                                    <tr ng-show="expenses.length">
                                        <td colspan="6" class="text-right td-bg-info"><b>TOTAL</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ total | number:2 }}</b>
                                        </td>
                                    </tr>
                                    <tr ng-show="!expenses.length">
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
        app.controller('ExpenseController', ['$scope', '$http', function ($scope, $http) {
            //queries
            $scope.loading = true;
            $scope.expenses = [];
            $scope.total = 0;
            $scope.query = {
                fromDate: '{{ carbon()->toDateString() }}',
                toDate: '{{ carbon()->toDateString() }}',
                customer: null,
                supplier: null,
                type: null,
                status: 'Unsubmitted'
            };
            //dropdowns
            $scope.dropdowns = {
                type: $('.type-drop-down'),
                customer: $('.customer-drop-down'),
                supplier: $('.supplier-drop-down'),
            };
            //Business Type Dropdown
            $scope.dropdowns.type.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    $scope.query.type = val;
                }
            });
            // $scope.dropdowns.customer.dropdown();
            // $scope.dropdowns.supplier.dropdown();

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
                $scope.dropdowns.type.dropdown('clear');
                dateRangeDropDown($scope);
                $scope.generate();
            };

            $scope.getExportRoute = function () {
                var route = '{{ route('report.expense.un.submitted.export') }}';
                return route + '?' + $.param($scope.query);
            }
        }]);
    </script>
@endsection