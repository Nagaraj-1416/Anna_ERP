@extends('layouts.master')
@section('title', 'Customer Ledger')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Reports') !!}
@endsection
@section('content')
    <section ng-controller="FinanceReportController">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-b-0">
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Customer Ledger</h3>
                    </div>
                    <hr>
                    <div class="card-body">
                        <!-- from to filter -->
                        <div class="form-filter">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group {{ $errors->has('customer') ? 'has-danger' : '' }} required">
                                        <label class="control-label">Customer</label>
                                        <div class="ui fluid  search selection dropdown customer-drop-down {{ $errors->has('customer') ? 'error' : '' }}">
                                            <input type="hidden" name="customer">
                                            <i class="dropdown icon"></i>
                                            <div class="default text">choose a customer</div>
                                            <div class="menu">
                                                @foreach(customerDropDown() as $key => $customer)
                                                    <div class="item" data-value="{{ $key }}">{{ $customer }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <p class="form-control-feedback">{{ $errors->first('customer') }}</p>
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
                            <div class="pull-right"></div>
                        </div>
                        <hr class="hr-dark">
                        <!-- heading section -->
                        <div class="heading-section">
                            <h2 class="text-center"><b>Customer Ledger</b></h2>
                            <p class="text-center text-muted"><b>From</b> @{{ fromDate | date}}
                                <b>To</b> @{{ toDate | date}}</p>
                            <h6 class="text-center text-muted">
                                <b><a target="_blank" href="/sales/customer/@{{ customer.id }}">@{{ customer.display_name }}</a></b>
                            </h6>
                            <br />
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
                                            <th width="8%">DATE</th>
                                            <th width="8%">TYPE</th>
                                            <th width="10%">Order/Invoice#</th>
                                            <th>DESCRIPTION</th>
                                            <th width="10%" class="text-right">DEBIT</th>
                                            <th width="10%" class="text-right">CREDIT</th>
                                            <th width="10%" class="text-right">BALANCE</th>
                                        </tr>
                                        <tr style="background-color: #ecf0f3;">
                                            <td colspan="4">
                                                <b>Starting Balance</b>
                                            </td>
                                            <td class="text-right" width="15%">
                                                <span ng-if="balances.intBalType == 'Debit'"><b>@{{ balances.intBal | number:2 }}</b></span>
                                            </td>
                                            <td class="text-right" width="15%">
                                                <span ng-if="balances.intBalType == 'Credit'"><b>@{{ balances.intBal | number:2 }}</b></span>
                                            </td>
                                            <td class="text-right" width="15%"><b>@{{ balances.intBal | number:2 }}</b></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="tran in trans" ng-show="length">
                                            <td width="10%">@{{ tran.date | date }}</td>
                                            <td width="10%">@{{ tran.tran_type }}</td>
                                            <td width="10%">
                                                <a target="_blank" href="@{{ tran.tran_ref_url }}@{{ tran.tran_ref_id }}">@{{ tran.tran_ref_no }}</a>
                                            </td>
                                            <td>
                                                <span ng-if="tran.tran_des_short == 'Sales In'">
                                                    <span class="text-warning">@{{ tran.tran_des_short }}</span>
                                                </span>
                                                <span ng-if="tran.tran_des_short == 'Cash In'">
                                                    <span class="text-green">@{{ tran.tran_des_short }}</span>
                                                </span>
                                                <span ng-if="tran.tran_des_short == 'Payment Cancel' || tran.tran_des_short == 'Invoice Cancel' || tran.tran_des_short == 'Sales Return'">
                                                    <span class="text-danger">@{{ tran.tran_des_short }}</span>
                                                </span>
                                                <br />
                                                @{{ tran.auto_narration }}
                                                <br />
                                                <a target="_blank" href="/finance/transaction/@{{ tran.id }}">View Journal</a>
                                            </td>
                                            <td class="text-right"><span ng-if="tran.action == 'InvoiceCreation' || tran.action == 'PaymentCancel' || tran.action == 'InvoiceCancel'">@{{ tran.amount | number:2 }}</span></td>
                                            <td class="text-right"><span ng-if="tran.action == 'PaymentCreation' || tran.action == 'SalesReturn'">@{{ tran.amount | number:2 }}</span></td>
                                            <td class="text-right">@{{ tran.balance | number:2 }}</td>
                                        </tr>
                                        <tr ng-show="!length">
                                            <td colspan="7">No data to display...</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr style="background-color: #ecf0f3;">
                                            <td colspan="4">
                                                <b>Totals and Ending Balance</b>
                                            </td>
                                            <td class="text-right" width="10%"><b>@{{ balances.debitBal | number:2 }}</b></td>
                                            <td class="text-right" width="10%"><b>@{{ balances.creditBal | number:2 }}</b></td>
                                            <td class="text-right" width="10%"><b>@{{ balances.endBal | number:2 }}</b></td>
                                        </tr>
                                    </tfoot>
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
        app.controller('FinanceReportController', ['$scope', '$http', function ($scope, $http) {
            $scope.query = {
                fromDate: '',
                toDate: '',
                customer: ''
            };

            $scope.products = [];
            $scope.daterangeDD = $('.date-range');
            $scope.daterangeValue = '';
            $scope.dropdowns = {
                customer: $('.customer-drop-down')
            };
            $scope.customerName = '';

            $scope.dropdowns.customer.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val, name) {
                    $scope.query.customer = val;
                    $scope.customerName = name;
                }
            });

            //Initiate Date Range Drop down
            dateRangeDropDown($scope);

            $scope.length = 0;

            // Generate Data using filters
            $scope.generate = function () {
                $scope.fromDate = $scope.query.fromDate;
                $scope.toDate = $scope.query.toDate;
                var orderRoute = '{{ route('report.finance.customer.ledger') }}';
                $http.get(orderRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.intBal = response.data.intBal;
                    $scope.intBalType = response.data.intBalType;
                    $scope.account = response.data.account;
                    $scope.customer = response.data.customer;
                    $scope.trans = response.data.trans;
                    $scope.balances = response.data.balances;
                    $scope.length = _.toArray($scope.trans).length;
                })
            };
            $scope.generate();

            // Reset Filters
            $scope.resetFilters = function () {
                $scope.query = {
                    fromDate: '',
                    toDate: '',
                    customer: ''
                };
                $scope.daterangeDD.dropdown('clear');
                $scope.dropdowns.customer.dropdown('clear');
                $scope.daterangeDD.dropdown('set text', 'Today').dropdown('set value', 'today');
                $scope.generate();
            };

        }]);
    </script>
@endsection
