@extends('layouts.master')
@section('title', 'Purchase by Product Category')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Reports') !!}
@endsection
@section('content')
    <section ng-controller="purchaseProductController">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-b-0">
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Purchase by Product Category</h3>
                    </div>
                    <hr>
                    <div class="card-body">
                        <!-- from to filter -->
                        <div class="form-filter">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group {{ $errors->has('product_category_id') ? 'has-danger' : '' }}">
                                        <label class="control-label">Product category</label>
                                        <div class="ui fluid  search selection dropdown product-drop-down {{ $errors->has('product_category_id') ? 'error' : '' }}">
                                            <input type="hidden" name="product_category_id">
                                            <i class="dropdown icon"></i>
                                            <div class="default text">choose a product category</div>
                                            <div class="menu">
                                                @foreach(productCategoryWithIDDropDown() as $key => $category)
                                                    <div class="item" data-value="{{ $key }}">
                                                        {{ $category }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <p class="form-control-feedback">{{ $errors->first('product_category_id') }}</p>
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
                            <h2 class="text-center"><b>Purchase by Product</b></h2>
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
                                        <th width="10%">ORDER#</th>
                                        <th width="15%">ORDER DATE</th>
                                        <th width="15%">STATUS</th>
                                        <th>DELIVERY STATUS</th>
                                        <th width="10%" class="text-right">PO AMOUNT</th>
                                        <th width="10%" class="text-right">BILLED</th>
                                        <th width="10%" class="text-right">MADE</th>
                                        <th width="10%" class="text-right">BALANCE</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="order in orders" ng-show="length">
                                        <td width="10%">
                                            <a target="_blank"
                                               href="{{ url('/') }}/purchase/order/@{{ order.id }}">
                                                @{{ order.po_no }}
                                            </a>
                                        </td>
                                        <td width="15%">@{{ order.order_date }}</td>
                                        <td width="15%">@{{ order.status }}</td>
                                        <td>@{{ order.delivery_status }}</td>
                                        <td width="10%" class="text-right ">@{{ order.total | number:2 }}
                                        </td>
                                        <td width="10%" class="text-right ">@{{ getBillTotal(order) |
                                            number:2 }}
                                        </td>
                                        <td width="10%" class="text-right ">@{{ getTotal(order) | number:2
                                            }}
                                        </td>
                                        <td width="10%" class="text-right ">@{{ getBalanced(order) |
                                            number:2 }}
                                        </td>
                                    </tr>
                                    <tr ng-show="length">
                                        <td colspan="4" class="text-right td-bg-info"><b>TOTAL</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ order_total |
                                                number:2 }}</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ bill_total | number:2
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
                category: '',
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
                onChange: function (val, name) {
                    $scope.query.category = val;
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
                var orderRoute = '{{ route('report.purchase.by.product.category') }}';
                $http.get(orderRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.orders = response.data.orders;
                    $scope.order_total = response.data.order_total;
                    $scope.bill_total = response.data.bill_total;
                    $scope.payment_total = response.data.payment_total;
                    $scope.balance = response.data.balance;
                    $scope.products = response.data.product;
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

            // Get Sales Order Balance Amount
            $scope.getBalanced = function (order) {
                return order.total - $scope.getTotal(order);
            };

            //Get Sales Order Payments Total
            $scope.getTotal = function (order) {
                var payments = order.payments;
                var amounts = _.pluck(payments, 'payment');
                var paid = $scope.sum(amounts);
                return paid;
            };

            // Get Order Bill Amount
            $scope.getBillTotal = function (order) {
                var bills = order.bills;
                var amounts = _.pluck(bills, 'amount');
                var paid = $scope.sum(amounts);
                return paid;
            };

            $scope.getOrderTotal = function () {
                var amounts = _.pluck($scope.orders, 'total');
                var amount = $scope.sum(amounts);
                return amount;
            };
            $scope.getExportRoute = function () {
                var route = '{{ route('report.purchase.by.product.category.export') }}';
                return route + '?' + $.param($scope.query);
            }
        }]);
    </script>
@endsection
