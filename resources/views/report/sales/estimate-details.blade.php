@extends('layouts.master')
@section('title', 'Sales Estimate Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Reports') !!}
@endsection
@section('content')
    <section ng-controller="salesProductController">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-b-0">
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Sales Estimate Details</h3>
                    </div>
                    <hr>
                    <div class="card-body">
                        <!-- from to filter -->
                        <div class="form-filter">
                            <div class="row">
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
                                <a href="@{{ getExportRoute() }}" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i>
                                    Export to PDF</a>
                            </div>
                        </div>
                        <hr class="hr-dark">
                        <!-- heading section -->
                        <div class="heading-section">
                            <h2 class="text-center"><b>Sales Estimate Details</b></h2>
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
                                        <th width="10%">CUSTOMER</th>
                                        <th width="10%">ESTIMATE#</th>
                                        <th width="15%">DATE</th>
                                        <th width="15%">EXPIRY DATE</th>
                                        <th width="15%">STATUS</th>
                                        <th width="10%" class="text-right">AMOUNT</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="estimate in estimates" ng-show="length">
                                        <td width="10%">
                                            <a target="_blank"
                                               href="{{ url('/') }}/sales/customer/@{{ estimate.customer.id }}">
                                                @{{ estimate.customer.display_name }}
                                            </a>
                                        </td>
                                        <td width="10%">
                                            <a target="_blank"
                                               href="{{ url('/') }}/sales/estimate/@{{ estimate.id }}">
                                                @{{ estimate.estimate_no }}
                                            </a>
                                        </td>
                                        <td width="15%">@{{ estimate.estimate_date }}</td>
                                        <td width="15%">@{{ estimate.expiry_date }}</td>
                                        <td width="15%">@{{ estimate.status }}</td>
                                        <td width="10%" class="text-right ">@{{ estimate.total | number:2 }}</td>

                                    </tr>
                                    <tr ng-show="length">
                                        <td colspan="5" class="text-right td-bg-info"><b>GRAND TOTAL</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ estimate_total |
                                                number:2 }}</b></td>
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
                businessType: null,
            };
            $scope.loading = true;
            $scope.orders = [];
            $scope.daterangeDD = $('.date-range');
            $scope.daterangeValue = '';
            $scope.dropdowns = {
                type: $('.type-drop-down'),
                product: $('.product-drop-down')
            };

            // Business Type Drop Down
            $scope.dropdowns.type.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val, name) {
                    $scope.query.businessType = val;
                    $scope.businessTypeName = name;
                }
            });

            // Sales Rep Drop Down
            $scope.dropdowns.product.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: '{{ route('setting.product.search', ['type' => 'Finished Good']) }}' + '/{query}',
                    cash: false,
                },
                onChange: function (val, name) {
                    $scope.query.product = val;
                    $scope.productName = name;
                }
            });
            //Initiate Date Range Drop down
            dateRangeDropDown($scope);
            $scope.length = 0;
            $scope.estimate_total = 0;
            $scope.balance = 0;
            // Generate Data using filters
            $scope.generate = function () {
                $scope.fromDate = $scope.query.fromDate;
                $scope.toDate = $scope.query.toDate;
                $scope.loading = true;
                var orderRoute = '{{ route('report.estimate.details') }}';
                $http.get(orderRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.estimates = response.data.estimates;
                    $scope.estimate_total = response.data.estimate_total;
                    $scope.loading = false;
                    $scope.length = _.toArray($scope.estimates).length;
                })
            };
            $scope.generate();
            // Reset Filters
            $scope.resetFilters = function () {
                $scope.query = {
                    fromDate: '',
                    toDate: '',
                    businessType: null,
                };
                dateRangeDropDown($scope);
                $scope.dropdowns.type.dropdown('clear');
                $scope.generate();
            };

            $scope.sum = function (array) {
                return _.reduce(array, function (memo, num) {
                    return memo + num;
                }, 0);
            };
            $scope.getExportRoute = function () {
                var route = '{{ route('report.estimate.details.export') }}';
                return route + '?' + $.param($scope.query);
            };

        }]);
    </script>
@endsection