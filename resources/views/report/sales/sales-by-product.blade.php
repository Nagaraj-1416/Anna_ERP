@extends('layouts.master')
@section('title', 'Sales by Product')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Reports') !!}
@endsection
@section('content')
    <section ng-controller="SalesProductController">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-b-0">
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Sales by Product</h3>
                    </div>
                    <hr>
                    <div class="card-body">
                        <!-- from to filter -->
                        <div class="form-filter">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group {{ $errors->has('product') ? 'has-danger' : '' }} required">
                                        <label class="control-label">Product</label>
                                        <div class="ui fluid  search selection dropdown product-drop-down {{ $errors->has('product') ? 'error' : '' }}">
                                            <input type="hidden" name="product">
                                            <i class="dropdown icon"></i>
                                            <div class="default text">choose a product</div>
                                            <div class="menu"></div>
                                        </div>
                                        <p class="form-control-feedback">{{ $errors->first('product') }}</p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group {{ $errors->has('rep_id') ? 'has-danger' : '' }}">
                                        <label class="control-label">Sales Rep</label>
                                        <div class="ui fluid  search selection dropdown rep-drop-down {{ $errors->has('rep_id') ? 'error' : '' }}">
                                            <input type="hidden" name="rep_id">
                                            <i class="dropdown icon"></i>
                                            <div class="default text">choose a sales rep</div>
                                            <div class="menu"></div>
                                        </div>
                                        <p class="form-control-feedback">{{ $errors->first('rep_id') }}</p>
                                    </div>
                                </div>
                            </div>
                            @include('report.general.date-range')
                        </div>
                        <div class="clearfix m-t-10">
                            <div class="pull-left">
                                <button ng-click="generate()"  class="btn btn-info"><i class="ti-filter"></i>
                                    Generate
                                </button>
                                <button ng-click="resetFilters()" class="btn btn-inverse"><i class="ti-eraser"></i>
                                    Reset
                                </button>
                            </div>
                            <div class="pull-right">
                                {{--<a href="@{{ getExportRoute() }}" class="btn btn-danger"><i
                                            class="fa fa-file-pdf-o"></i>
                                    Export to PDF</a>--}}
                            </div>
                        </div>
                        <hr class="hr-dark">
                        <!-- heading section -->
                        <div class="heading-section">
                            <h2 class="text-center"><b>Sales by Product</b></h2>
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
                                            <th>ORDER#</th>
                                            <th width="15%">ORDER DATE</th>
                                            <th width="10%" class="text-center">RATE</th>
                                            <th width="10%" class="text-center">SOLD QTY</th>
                                            <th width="15%" class="text-right">AMOUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="product in products" ng-show="length">
                                            <td colspan="5" class="no-padding-tbl-cel">
                                                <a class="m-l-10" target="_blank"
                                                   href="{{ url('/') }}/setting/product/@{{  product.id }}"><b>@{{
                                                        product.name }}</b>
                                                </a>
                                                <hr>
                                                <table class="table no-border">
                                                    <tbody>
                                                        <tr ng-repeat="order in product.orders" ng-show="length">
                                                            <td>
                                                                <a target="_blank"
                                                                   href="{{ url('/') }}/sales/order/@{{ order.id }}">
                                                                    @{{ order.ref }}
                                                                </a>
                                                            </td>
                                                            <td width="15%">@{{ order.order_date }}</td>
                                                            <td width="10%" class="text-center">@{{ order.pivot.rate }}</td>
                                                            <td width="10%" class="text-center">@{{ order.pivot.quantity }}</td>
                                                            <td width="15%" class="text-right">@{{ order.pivot.amount | number:2
                                                                }}
                                                            </td>
                                                        </tr>
                                                        <tr ng-show="length">
                                                            <td colspan="3" class="td-bg-info text-right">
                                                                <b>TOTAL</b>
                                                            </td>
                                                            <td class="td-bg-success text-center">
                                                                <b>@{{ getOrderQtyTotal(product) }}</b>
                                                            </td>
                                                            <td class="td-bg-success text-right">
                                                                <b>@{{ getOrderTotal(product) | number:2 }}</b>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
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
        app.controller('SalesProductController', ['$scope', '$http', function ($scope, $http) {
            $scope.query = {
                fromDate: '',
                toDate: '',
                product: '',
                rep: ''
            };

            $scope.loading = true;
            $scope.products = [];
            $scope.daterangeDD = $('.date-range');
            $scope.daterangeValue = '';
            $scope.dropdowns = {
                product: $('.product-drop-down'),
                rep: $('.rep-drop-down')
            };
            $scope.repName = '';

            $scope.dropdowns.product.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: '{{ route('setting.product.search', ['type' => 'Finished Good']) }}' + '/{query}',
                    cash: false
                },
                onChange: function (val, name) {
                    $scope.query.product = val;
                    $scope.productName = name;
                }
            });

            $scope.dropdowns.rep.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: '{{ route('setting.rep.search') }}' + '/{query}',
                    cash: false
                },
                onChange: function (val, name) {
                    $scope.query.rep = val;
                    $scope.repName = name;
                }
            });

            //Initiate Date Range Drop down
            dateRangeDropDown($scope);

            $scope.length = 0;

            // Generate Data using filters
            $scope.generate = function () {
                $scope.fromDate = $scope.query.fromDate;
                $scope.toDate = $scope.query.toDate;
                $scope.loading = true;
                var orderRoute = '{{ route('report.sales.by.product') }}';
                $http.get(orderRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.products = response.data.products;
                    $scope.loading = false;
                    $scope.length = _.toArray($scope.products).length;
                })
            };
            $scope.generate();

            // Reset Filters
            $scope.resetFilters = function () {
                $scope.query = {
                    fromDate: '',
                    toDate: '',
                    product: ''
                };
                $scope.daterangeDD.dropdown('clear');
                $scope.dropdowns.rep.dropdown('clear');
                $scope.dropdowns.product.dropdown('clear');
                $scope.daterangeDD.dropdown('set text', 'Today').dropdown('set value', 'today');
                $scope.generate();
            };

            $scope.sum = function (array) {
                var sum = _.reduce(array, function (memo, num) {
                    return memo + num;
                }, 0);
                return sum;
            };

            $scope.getOrderTotal = function (product) {
                return $scope.sum(_.pluck(_.pluck(product.orders, 'pivot'), 'amount'));
            };

            $scope.getOrderQtyTotal = function (product) {
                return $scope.sum(_.pluck(_.pluck(product.orders, 'pivot'), 'quantity'));
            };

            $scope.getExportRoute = function () {
                var route = '{{ route('report.sales.by.product.export') }}';
                return route + '?' + $.param($scope.query);
            };

        }]);
    </script>
@endsection
