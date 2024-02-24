@extends('layouts.master')
@section('title', 'Customer Ledger')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row" ng-controller="CustomerLedgerController">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <!-- from to filter -->
                    <div class="form-filter">
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
                    <table class="table color-table muted-table">
                        <thead>
                        <tr>
                            <th width="8%">DATE</th>
                            <th width="15%">TYPE</th>
                            <th width="15%">ORDER / INVOICE#</th>
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
                                <span ng-if="tran.tran_des_short == 'Cash In' || tran.tran_des_short == 'Manual Cheque Registered' || tran.tran_des_short == 'Returned Cheque Payment' ">
                                    <span class="text-green">@{{ tran.tran_des_short }}</span>
                                </span>
                                <span ng-if="tran.tran_des_short == 'Cheque Realised'">
                                    <span class="text-green">@{{ tran.tran_des_short }}</span>
                                </span>
                                <span ng-if="tran.tran_des_short == 'Payment Cancel'
                                || tran.tran_des_short == 'Invoice Cancel'
                                || tran.tran_des_short == 'Sales Return'
                                || tran.tran_des_short == 'Cheque Bounced'
                                || tran.tran_des_short == 'Returned Cheque Payment Canceled' ">
                                    <span class="text-danger">@{{ tran.tran_des_short }}</span>
                                </span>
                                <br />
                                @{{ tran.auto_narration }}
                                <br />
                                <a target="_blank" href="/finance/transaction/@{{ tran.id }}">View Journal</a>
                            </td>
                            <td class="text-right">
                                <span ng-if="tran.action == 'InvoiceCreation'
                                || tran.action == 'PaymentCancel'
                                || tran.action == 'ChequeBounced'
                                || tran.action == 'ReturnedChequePaymentCancel'">
                                    @{{ tran.amount | number:2 }}
                                </span>
                            </td>
                            <td class="text-right">
                                <span ng-if="tran.action == 'PaymentCreation'
                                || tran.action == 'SalesReturn'
                                || tran.action == 'InvoiceCancel'
                                || tran.action == 'ChequeRealised'
                                || tran.action == 'ManualChequeRegistered'
                                || tran.action == 'ReturnedChequePayment'">
                                    @{{ tran.amount | number:2 }}
                                </span>
                            </td>
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
            </div>
        </div>
    </div>
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
        app.controller('CustomerLedgerController', ['$scope', '$http', function ($scope, $http) {

            $scope.query = {
                fromDate: '',
                toDate: ''
            };

            $scope.daterangeDD = $('.date-range');
            $scope.daterangeValue = '';

            dateRangeDropDown($scope);

            $scope.daterangeDD.dropdown('set text', 'This Month').dropdown('set value', 'this month');

            $scope.length = 0;

            $scope.generate = function () {
                $scope.fromDate = $scope.query.fromDate;
                $scope.toDate = $scope.query.toDate;
                var orderRoute = '{{ route('sales.customer.ledger', $customer) }}';
                $http.get(orderRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.intBal = response.data.intBal;
                    $scope.intBalType = response.data.intBalType;
                    $scope.account = response.data.account;
                    $scope.trans = response.data.trans;
                    $scope.balances = response.data.balances;
                    $scope.length = _.toArray($scope.trans).length;
                })
            };
            $scope.generate();

            $scope.resetFilters = function () {
                $scope.query = {
                    fromDate: '',
                    toDate: ''
                };
                $scope.daterangeDD.dropdown('clear');
                $scope.dropdowns.customer.dropdown('clear');
                $scope.daterangeDD.dropdown('set text', 'Today').dropdown('set value', 'today');
                $scope.generate();
            };

        }]);
    </script>
@endsection
