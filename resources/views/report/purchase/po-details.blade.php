@extends('layouts.master')
@section('title', 'Purchase Order Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Reports') !!}
@endsection
@section('content')
    <section ng-controller="purchaseProductController">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-b-0">
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Purchase Order Details</h3>
                    </div>
                    <hr>
                    <div class="card-body">
                        <!-- from to filter -->
                        <div class="form-filter">
                            <div class="row">
                                {{--<div class="col-md-3">--}}
                                {{--<div class="form-group {{ $errors->has('product_id') ? 'has-danger' : '' }}">--}}
                                {{--<label class="control-label">Product</label>--}}
                                {{--<div class="ui fluid  search selection dropdown product-drop-down {{ $errors->has('product_id') ? 'error' : '' }}">--}}
                                {{--<input type="hidden" name="product_id">--}}
                                {{--<i class="dropdown icon"></i>--}}
                                {{--<div class="default text">choose a product</div>--}}
                                {{--<div class="menu"></div>--}}
                                {{--</div>--}}
                                {{--<p class="form-control-feedback">{{ $errors->first('product_id') }}</p>--}}
                                {{--</div>--}}
                                {{--</div>--}}
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
                                <a  href="@{{ getExportRoute() }}" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i>
                                    Export to PDF</a>
                            </div>
                        </div>
                        <hr class="hr-dark">
                        <!-- heading section -->
                        <div class="heading-section">
                            <h2 class="text-center"><b>Purchase Order Details</b></h2>
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
                                        <th width="15%">SUPPLIER</th>
                                        <th width="10%">ORDER#</th>
                                        <th width="15%">ORDER DATE</th>
                                        <th width="15%">DELIVERY DATE</th>
                                        <th width="15%">STATUS</th>
                                        <th width="10%" class="text-right">AMOUNT</th>
                                        <th width="10%" class="text-right">MADE</th>
                                        <th width="10%" class="text-right">BALANCE</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="order in orders" ng-show="length">
                                        <td width="15%">
                                            <a target="_blank"
                                               href="{{ url('/') }}/purchase/supplier/@{{ order.supplier.id }}">
                                                @{{ order.supplier.display_name }}
                                            </a>
                                        </td>
                                        <td width="10%">
                                            <a target="_blank"
                                               href="{{ url('/') }}/purchase/order/@{{ order.id }}">
                                                @{{ order.po_no }}
                                            </a>
                                        </td>
                                        <td width="15%">@{{ order.order_date }}</td>
                                        <td width="15%">@{{ order.delivery_date }}</td>
                                        <td width="15%">@{{ order.status }}</td>
                                        <td width="10%" class="text-right ">@{{ order.total | number:2 }}</td>
                                        <td width="10%" class="text-right ">@{{ getPaid(order) | number:2 }}</td>
                                        <td width="10%" class="text-right ">@{{ getBalance(order) | number:2 }}</td>
                                    </tr>
                                    <tr ng-show="length">
                                        <td colspan="5" class="text-right td-bg-info"><b>TOTAL</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ order_total | number:2
                                                }}</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ payment_total | number:2
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
        app.controller('purchaseProductController', ['$scope', '$http', function ($scope, $http) {
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
                    url: '{{ route('setting.product.search', ['type' => 'Raw Material']) }}' + '/{query}',
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
            $scope.bill_total = 0;
            $scope.payment_total = 0;
            $scope.balance = 0;
            // Generate Data using filters
            $scope.generate = function () {
                $scope.fromDate = $scope.query.fromDate;
                $scope.toDate = $scope.query.toDate;
                $scope.loading = true;
                var orderRoute = '{{ route('report.purchase.by.product') }}';
                $http.get(orderRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.orders = response.data.orders;
                    $scope.order_total = response.data.order_total;
                    $scope.payment_total = response.data.payment_total;
                    $scope.balance = response.data.balance;
                    $scope.loading = false;
                    $scope.length = _.toArray($scope.orders).length;
                })
            };
            $scope.generate();
            // Reset Filters
            $scope.resetFilters = function () {
                $scope.query = {
                    fromDate: '',
                    toDate: '',
                    businessType: null,
                    product: '',
                };
                $scope.daterangeDD.dropdown('clear');
                $scope.dropdowns.type.dropdown('clear');
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

            $scope.getPaid = function (order) {
                var payments = order.payments;
                return $scope.sum(_.pluck(payments, 'payment'));
            };

            $scope.getBalance = function (order) {
                var paymentAmount = $scope.getPaid(order);
                return (order.total - paymentAmount);
            };

            $scope.getExportRoute = function () {
                var route = '{{ route('report.purchase.order.details.export') }}';
                return route + '?' + $.param($scope.query);
            }
        }]);
    </script>
@endsection