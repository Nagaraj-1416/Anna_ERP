@extends('layouts.master')
@section('title', 'Payments Received')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Reports') !!}
@endsection
@section('content')
    <section ng-controller="salesProductController">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-b-0">
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Payments Received</h3>
                    </div>
                    <hr>
                    <div class="card-body">
                        <!-- from to filter -->
                        <div class="form-filter">
                            <div class="row m-b-15">
                                <div class="col-md-3">
                                    <div class="form-group {{ $errors->has('company_id') ? 'has-danger' : '' }}">
                                        <label class="control-label">Company</label>
                                        <div class="ui fluid  search selection dropdown company-drop-down {{ $errors->has('company_id') ? 'error' : '' }}">
                                            <input type="hidden" name="company_id">
                                            <i class="dropdown icon"></i>
                                            <div class="default text">choose a company</div>
                                            <div class="menu">
                                                @foreach(companyDropDown() as $key => $company)
                                                    <div class="item" data-value="{{ $key }}">{{ $company }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <p class="form-control-feedback">{{ $errors->first('company_id') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Payment Type</label>
                                        <div class="ui fluid  search selection dropdown payment-type-drop-down ">
                                            <input type="hidden" name="payment_type">
                                            <i class="dropdown icon"></i>
                                            <div class="default text">choose a payment type</div>
                                            <div class="menu">
                                                @foreach(paymentTypeDD() as $key => $type)
                                                    <div class="item" data-value="{{ $key }}">{{ $type }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Payment Mode</label>
                                        <div class="ui fluid  search selection dropdown payment-mode-drop-down">
                                            <input type="hidden" name="product_id">
                                            <i class="dropdown icon"></i>
                                            <div class="default text">choose a payment mode</div>
                                            <div class="menu">
                                                @foreach(paymentModeDD() as $key => $type)
                                                    <div class="item" data-value="{{ $key }}">{{ $type }}</div>
                                                @endforeach
                                            </div>
                                        </div>
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
                                <button ng-click="resetFilters()" class="btn btn-black"><i class="ti-eraser"></i>
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
                            <h2 class="text-center"><b>Payments Received</b></h2>
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
                                        <th width="10%">INVOICE#</th>
                                        <th width="15%">PAYMENT DATE</th>
                                        <th width="15%">TYPE</th>
                                        <th width="15%">MODE</th>
                                        <th width="15%">STATUS</th>
                                        <th width="15%">DEPOSIT TO</th>
                                        <th width="15%" class="text-right">PAYMENT</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="(key, datas) in payments" ng-show="length">
                                        <td colspan="8" class="no-padding-tbl-cel">
                                            <a class="m-l-10" target="_blank"
                                               href="{{ url('/') }}/sales/customer/@{{ getSupplier(key).id }}"><b>@{{
                                                    getSupplier(key).display_name }}</b></a>
                                            <hr>
                                            <table class="table no-border">
                                                <tbody>
                                                <tr ng-repeat="payment in datas" ng-show="length">
                                                    <td width="10%">
                                                        <a target="_blank"
                                                           href="{{ url('/') }}/sales/invoice/@{{ payment.invoice.id }}">
                                                            @{{ payment.invoice.invoice_no }}
                                                        </a>
                                                    </td>
                                                    <td width="15%">@{{ payment.payment_date }}</td>
                                                    <td width="15%">@{{ payment.payment_type }}</td>
                                                    <td width="15%">@{{ payment.payment_mode }}</td>
                                                    <td width="15%">@{{ payment.status }}</td>
                                                    <td width="15%">@{{ payment.deposited_to ? payment.deposited_to.name
                                                        : 'None'}}
                                                    </td>
                                                    <td width="15%" class="text-right">@{{ payment.payment | number:2
                                                        }}
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr ng-show="length">
                                        <td colspan="6" class="text-right td-bg-info"><b>GRAND TOTAL</b></td>
                                        <td width="15%" class="text-right td-bg-success"><b>@{{ balance | number:2
                                                }}</b></td>
                                    </tr>
                                    <tr ng-show="!length">
                                        <td colspan="7">No data to display...</td>
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
                company: '',
                paymentType: '',
                paymentMode: '',
            };
            $scope.loading = true;
            $scope.orders = [];
            $scope.daterangeDD = $('.date-range');
            $scope.daterangeValue = '';
            $scope.dropdowns = {
                company: $('.company-drop-down'),
                mode: $('.payment-mode-drop-down'),
                paymentType: $('.payment-type-drop-down')
            };

            // Business Type Drop Down
            $scope.dropdowns.company.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val, name) {
                    $scope.query.company = val;
                }
            });

            // Sales Rep Drop Down
            $scope.dropdowns.mode.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val, name) {
                    $scope.query.paymentMode = val;
                    $scope.productName = name;
                }
            });

            $scope.dropdowns.paymentType.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val, name) {
                    $scope.query.paymentType = val;
                    $scope.productName = name;
                }
            });
            //Initiate Date Range Drop down
            dateRangeDropDown($scope);
            $scope.length = 0;
            $scope.invoice_total = 0;
            $scope.payment_total = 0;
            $scope.balance = 0;
            // Generate Data using filters
            $scope.generate = function () {
                $scope.fromDate = $scope.query.fromDate;
                $scope.toDate = $scope.query.toDate;
                $scope.loading = true;
                var orderRoute = '{{ route('report.payments.received') }}';
                $http.get(orderRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.payments = response.data.payments;
                    $scope.customers = response.data.customer;
                    $scope.balance = response.data.payments_total;
                    $scope.loading = false;
                    $scope.length = _.toArray($scope.payments).length;
                })
            };
            $scope.generate();
            // Reset Filters
            $scope.resetFilters = function () {
                $scope.query = {
                    fromDate: '',
                    toDate: '',
                    businessType: null,
                    paymentType: '',
                    paymentMode: '',
                };
                $scope.daterangeDD.dropdown('clear');
                $scope.dropdowns.company.dropdown('clear');
                $scope.dropdowns.type.dropdown('clear');
                $scope.dropdowns.paymentType.dropdown('clear');
                $scope.dropdowns.mode.dropdown('clear');
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
                var invoices = order.invoices;
                var amounts = _.pluck(invoices, 'amount');
                var paid = $scope.sum(amounts);
                return paid;
            };

            $scope.getOrderTotal = function () {
                var amounts = _.pluck($scope.orders, 'total');
                var amount = $scope.sum(amounts);
                return amount;
            };

            $scope.getOrderBillTotal = function () {
                var invoices = _.pluck($scope.orders, 'invoices');
                var amounts = _.pluck(invoices, 'amount');
                var amount = $scope.sum(amounts);
            };

            $scope.getSupplier = function (id) {
                var name = _.find($scope.customers, function (value, key) {
                    if (value.id === parseInt(id)) return value;
                });
                return name;
            };

            $scope.getExportRoute = function () {
                var route = '{{ route('report.payments.received.export') }}';
                return route + '?' + $.param($scope.query);
            };

        }]);
    </script>
@endsection
