@extends('layouts.master')
@section('title', 'Credit Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Reports') !!}
@endsection
@section('content')
    <section ng-controller="purchaseSupplierController">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-b-0">
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Credit Details</h3>
                    </div>
                    <hr>
                    <div class="card-body">
                        <!-- from to filter -->
                        <div class="form-filter">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group {{ $errors->has('supplier_id') ? 'has-danger' : '' }}">
                                        <label class="control-label">Supplier</label>
                                        <div class="ui fluid  search selection dropdown supplier-drop-down {{ $errors->has('supplier_id') ? 'error' : '' }}">
                                            <input type="hidden" name="supplier_id">
                                            <i class="dropdown icon"></i>
                                            <div class="default text">choose a supplier</div>
                                            <div class="menu"></div>
                                        </div>
                                        <p class="form-control-feedback">{{ $errors->first('supplier_id') }}</p>
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
                            <h2 class="text-center"><b>Credit Details</b></h2>
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
                                        <th width="10%">CREDIT#</th>
                                        <th width="15%">CREDIT DATE</th>
                                        <th width="15%">STATUS</th>
                                        <th width="15%" class="text-right">CREDIT</th>
                                        <th width="15%" class="text-right">REFUNDED</th>
                                        <th width="15%" class="text-right">USED CREDIT</th>
                                        <th width="15%" class="text-right">BALANCE</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="(key, datas) in credits" ng-show="length">
                                        <td colspan="7" class="no-padding-tbl-cel">
                                            <a class="m-l-10" target="_blank"
                                               href="{{ url('/') }}/purchase/supplier/@{{ getSupplier(key).id }}"><b>@{{
                                                    getSupplier(key).display_name }}</b></a>
                                            <hr>
                                            <table class="table no-border">
                                                <tbody>
                                                <tr ng-repeat="credit in datas" ng-show="length">
                                                    <td width="10%">
                                                        <a target="_blank"
                                                           href="{{ url('/') }}/purchase/credit/@{{ credit.id }}">
                                                            @{{ credit.code }}
                                                        </a>
                                                    </td>
                                                    <td width="15%">@{{ credit.date }}</td>
                                                    <td width="15%">@{{ credit.status }}</td>
                                                    <td width="15%" class="text-right ">@{{ credit.amount | number:2 }}
                                                    </td>
                                                    <td width="15%" class="text-right ">@{{ getRefundTotal(credit) |
                                                        number:2 }}
                                                    </td>
                                                    <td width="15%" class="text-right ">@{{ getPaymentsTotal(credit) |
                                                        number:2
                                                        }}
                                                    </td>
                                                    <td width="15%" class="text-right ">@{{ getBalanced(credit) |
                                                        number:2 }}
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr ng-show="length">
                                        <td colspan="3" class="text-right td-bg-info"><b>TOTAL</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ credits_total |
                                                number:2 }}</b></td>
                                        <td width="10%" class="text-right td-bg-success"><b>@{{ refunded_total |
                                                number:2
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
        app.controller('purchaseSupplierController', ['$scope', '$http', function ($scope, $http) {
            $scope.query = {
                fromDate: '',
                toDate: '',
                businessType: null,
                supplier: '',
            };
            $scope.loading = true;
            $scope.orders = [];
            $scope.daterangeDD = $('.date-range');
            $scope.daterangeValue = '';
            $scope.dropdowns = {
                type: $('.type-drop-down'),
                supplier: $('.supplier-drop-down')
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
            $scope.dropdowns.supplier.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: '{{ route('purchase.supplier.search') }}' + '/{query}',
                    cash: false,
                },
                onChange: function (val, name) {
                    $scope.query.supplier = val;
                    $scope.supplierName = name;
                }
            });
            //Initiate Date Range Drop down
            dateRangeDropDown($scope);
            $scope.length = 0;
            $scope.credits_total = 0;
            $scope.refunded_total = 0;
            $scope.payment_total = 0;
            $scope.balance = 0;
            // Generate Data using filters
            $scope.generate = function () {
                $scope.fromDate = $scope.query.fromDate;
                $scope.toDate = $scope.query.toDate;
                $scope.loading = true;
                var orderRoute = '{{ route('report.purchase.credit.details') }}';
                $http.get(orderRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.credits = response.data.credits;
                    $scope.credits_total = response.data.credits_total;
                    $scope.refunded_total = response.data.refunded_total;
                    $scope.payment_total = response.data.payment_total;
                    $scope.balance = response.data.balance;
                    $scope.suppliers = response.data.supplier;
                    $scope.loading = false;
                    $scope.length = _.toArray($scope.credits).length;
                })
            };
            $scope.generate();
            // Reset Filters
            $scope.resetFilters = function () {
                $scope.query = {
                    fromDate: '',
                    toDate: '',
                    businessType: null,
                    supplier: '',
                };
                $scope.daterangeDD.dropdown('clear');
                $scope.dropdowns.type.dropdown('clear');
                $scope.dropdowns.supplier.dropdown('clear');
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
            $scope.getBalanced = function (credit) {
                return credit.amount - $scope.getTotal(credit);
            };

            //Get Sales Order Payments Total
            $scope.getTotal = function (credit) {
                return $scope.getRefundTotal(credit) + $scope.getPaymentsTotal(credit);
            };

            // Get credit Refund Amount
            $scope.getRefundTotal = function (credit) {
                var refunds = credit.refunds;
                var amounts = _.pluck(refunds, 'amount');
                var paid = $scope.sum(amounts);
                return paid;
            };
            $scope.getPaymentsTotal = function (credit) {
                var payments = credit.payments;
                var amounts = _.pluck(payments, 'payment');
                var paid = $scope.sum(amounts);
                return paid;
            };

            $scope.getOrderTotal = function () {
                var amounts = _.pluck($scope.credits, 'total');
                var amount = $scope.sum(amounts);
                return amount;
            };

            $scope.getSupplier = function (id) {
                var test = _.find($scope.suppliers, function (value, key) {
                    if (value.id === parseInt(id)) return value;
                });
                return test;
            };

            $scope.getExportRoute = function () {
                var route = '{{ route('report.purchase.credit.details.export') }}';
                return route + '?' + $.param($scope.query);
            }
        }]);
    </script>
@endsection
