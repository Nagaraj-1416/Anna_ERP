@extends('layouts.master')
@section('title', 'Expenses by Supplier')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Reports') !!}
@endsection
@section('content')
    <section ng-controller="SalesRepController">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-b-0">
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Expenses by Supplier</h3>
                    </div>
                    <hr>
                    <div class="card-body">
                        <!-- from to filter -->
                        <div class="form-filter">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group {{ $errors->has('supplier') ? 'has-danger' : '' }}">
                                        <label class="control-label">Expenses Supplier</label>
                                        <div class="ui fluid  search selection dropdown supplier-drop-down {{ $errors->has('supplier') ? 'error' : '' }}">
                                            <input type="hidden" name="supplier">
                                            <i class="dropdown icon"></i>
                                            <div class="default text">choose a supplier</div>
                                            <div class="menu">
                                                @foreach(supplierDropDown() as $key => $type)
                                                    <div class="item" data-value="{{ $key }}">{{ $type }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <p class="form-control-feedback">{{ $errors->first('supplier') }}</p>
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
                            <h2 class="text-center"><b>Expenses by Supplier</b></h2>
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
                                        <th width="12%">CATEGORY</th>
                                        <th width="12%">SUPPLIER</th>
                                        <th width="15%">STATUS</th>
                                        <th width="10%" class="text-right">AMOUNT</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="(key, datas) in expenses" ng-show="length">
                                        <td colspan="7" class="no-padding-tbl-cel">
                                            <a class="m-l-10" target="_blank"
                                               href="{{ url('/') }}/sales/supplier/@{{ getSupplier(key).id }}"><b> @{{
                                                    getSupplier(key).display_name
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
                                                        @{{ expense.category.name }}
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
            var suppliers = @json(\App\Supplier::all()->toArray());
            $scope.loading = true;
            $scope.expenses = [];
            $scope.total = 0;
            $scope.length = 0;
            $scope.query = {
                fromDate: '{{ carbon()->toDateString() }}',
                toDate: '{{ carbon()->toDateString() }}',
                supplier: null,
                type: null
            };
            //dropdowns
            $scope.dropdowns = {
                type: $('.type-drop-down'),
                supplier: $('.supplier-drop-down'),
            };

            // Business Type Drop Down
            $scope.dropdowns.type.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val, name) {
                    $scope.query.type = val;
                }
            });

            $scope.dropdowns.supplier.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    $scope.query.supplier = val;
                }
            });

            //Initiate Date Range Drop down
            dateRangeDropDown($scope);
            // Generate Data using filters
            $scope.generate = function () {
                $scope.fromDate = $scope.query.fromDate;
                $scope.toDate = $scope.query.toDate;
                $scope.loading = true;
                var orderRoute = '{{ route('report.expense.by.supplier') }}';
                $http.get(orderRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.expenses = response.data.expenses;
                    $scope.total = response.data.total;
                    $scope.length = _.toArray($scope.expenses).length;
                    $scope.loading = false;
                })
            };
            $scope.generate();
            $scope.getSupplier = function (key) {
                var object = _.find(suppliers, function (value, index) {
                    return value.id === parseInt(key);
                });
                return object;
            };

            $scope.getExportRoute = function () {
                var route = '{{ route('report.expense.by.supplier.export') }}';
                return route + '?' + $.param($scope.query);
            };
        }]);
    </script>
@endsection