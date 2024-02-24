@extends('layouts.master')
@section('title', 'Cash Book - Company')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
    <div class="row" ng-controller="DayBookController">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-b-0">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h3 class="card-title"><i class="ti-receipt"></i> Cash Book - Company</h3>
                            <h6 class="card-subtitle">
                                A <code>company</code> & <code>date range</code> filters are required to generate this Cash Book!
                            </h6>
                        </div>
                        <div class="pull-right"></div>
                    </div>
                </div>
                <hr>
                <div class="card-body p-b-5">
                    <div class="form-filter">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group required @{{ hasError('company') ? 'has-danger' : '' }}">
                                    <label class="control-label">Company</label>
                                    <div class="ui fluid search selection dropdown company-drop-down @{{ hasError('company') ? 'error' : '' }}">
                                        <input type="hidden" name="company">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose a company</div>
                                        <div class="menu">
                                            @foreach(companyDropDown() as $key => $company)
                                                <div class="item" data-value="{{ $key }}">{{ $company }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="form-control-feedback">
                                        @{{ hasError('company') ? hasError('company') : ''}}
                                    </p>
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
                            <button ng-click="resetFilters()" class="btn btn-inverse"><i class="ti-eraser"></i> Reset</button>
                        </div>
                        <div class="pull-right"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="loading" ng-show="loading">
                            <div class="loading-dot"></div>
                            <div class="loading-dot"></div>
                            <div class="loading-dot"></div>
                            <div class="loading-dot"></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="loading" ng-show="loading">
                            <p>Please wait, rep's cash book is loading...</p>
                        </div>
                    </div>
                </div>

                <div class="card-body" ng-show="!loading">
                    <div class="row" ng-show="rep">
                        <div class="col-md-12">
                            <div class="ribbon-wrapper card">
                                <div class="ribbon ribbon-default">
                                    @{{ rep.name }}'s Cash Book
                                    <code>From</code> @{{ fromRange | date }} <code>To</code> @{{ toRange | date }}
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="ui celled structured table collapse-table">
                                            <thead>
                                            <tr>
                                                <th colspan="3" width="50%" class="text-center" style="background-color: #e0e7eb;">DEBIT</th>
                                            </tr>
                                            <tr>
                                                <th>Date</th>
                                                <th>Particulars</th>
                                                <th class="text-right">Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td colspan="3"><u>CASH RECEIVED</u></td>
                                            </tr>
                                            <tr ng-repeat="cashPayment in cashPayments">
                                                <td style="width: 15%;">@{{ cashPayment.payment_date | date }}</td>
                                                <td style="width: 65%;">
                                                    @{{ cashPayment.order.ref }} <br />
                                                    @{{ cashPayment.customer.display_name }}
                                                </td>
                                                <td class="text-right" style="width: 20%;">@{{ cashPayment.payment | number:2 }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-right"><b>Total Cash</b></td>
                                                <td class="text-right"><b>@{{ totalCash | number:2 }}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"><u>CHEQUES RECEIVED</u></td>
                                            </tr>
                                            <tr ng-repeat="chequePayment in chequePayments">
                                                <td>@{{ chequePayment.payment_date | date }}</td>
                                                <td>
                                                    @{{ chequePayment.order.ref }} <br />
                                                    @{{ chequePayment.customer.display_name }}
                                                </td>
                                                <td class="text-right">@{{ chequePayment.payment | number:2 }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-right"><b>Total Cheques</b></td>
                                                <td class="text-right"><b>@{{ totalCheques | number:2 }}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"><u>CREDIT SALES</u></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-right"><b>Total Credit</b></td>
                                                <td class="text-right"><b>@{{ 0 | number:2 }}</b></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="ui celled structured table collapse-table">
                                            <thead>
                                            <tr>
                                                <th colspan="3" class="text-center" style="background-color: #e0e7eb;">CREDIT</th>
                                            </tr>
                                            <tr>
                                                <th>Date</th>
                                                <th>Particulars</th>
                                                <th class="text-right">Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td colspan="3"><u>SALES</u></td>
                                            </tr>
                                            <tr ng-repeat="sale in sales">
                                                <td style="width: 15%;">@{{ sale.order_date | date }}</td>
                                                <td style="width: 65%;">
                                                    @{{ sale.ref }} <br />
                                                    @{{ sale.customer.display_name }}
                                                </td>
                                                <td class="text-right" style="width: 20%;">@{{ sale.total | number:2 }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-right"><b>Total Sales</b></td>
                                                <td class="text-right"><b>@{{ totalSales | number:2 }}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"><u>EXPENSES</u></td>
                                            </tr>
                                            <tr ng-repeat="expense in expenses">
                                                <td>@{{ expense.expense_date | date }}</td>
                                                <td>
                                                    @{{ expense.type.name }}
                                                </td>
                                                <td class="text-right">@{{ expense.amount | number:2 }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-right"><b>Total Expenses</b></td>
                                                <td class="text-right"><b>@{{ totalExpenses | number:2 }}</b></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row m-t-10">
                                    <div class="col-md-6">
                                        <table class="ui celled structured table collapse-table">
                                            <tr style="background-color: #e0e7eb;">
                                                <td colspan="2" class="text-right"><b>DEBIT TOTAL</b></td>
                                                <td style="width: 20%" class="text-right"><b>@{{ debitTotal | number:2 }}</b></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="ui celled structured table collapse-table">
                                            <tr style="background-color: #e0e7eb;">
                                                <td colspan="2" class="text-right"><b>CREDIT TOTAL</b></td>
                                                <td style="width: 20%" class="text-right"><b>@{{ creditTotal | number:2 }}</b></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row m-t-10">
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6">
                                        <table class="ui celled structured table collapse-table">
                                            <tr>
                                                <td colspan="3"><u>TRANSFERS</u></td>
                                            </tr>
                                            <tr ng-repeat="transfer in transfers">
                                                <td style="width: 15%;">@{{ transfer.date | date }}</td>
                                                <td style="width: 65%;">
                                                    @{{ transfer.transaction.auto_narration }}
                                                </td>
                                                <td class="text-right" style="width: 20%;">@{{ transfer.amount | number:2 }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-right"><b>Total Transfers</b></td>
                                                <td class="text-right"><b>@{{ totalTransfers | number:2 }}</b></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row m-t-10">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        <table class="ui celled structured table collapse-table">
                                            <tr style="background-color: #e0e7eb;">
                                                <td class="text-center">
                                                    <h3>CASH ON HAND</h3>
                                                    <h2>@{{ cashOnHand | number:2 }}</h2>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" ng-show="!rep && !loading">
                        <div class="col-md-12">
                            <hr>
                            <span class="text-muted">Please choose a company to generate the cash book report!</span>
                        </div>
                    </div>
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
    @include('report.general.date-range-script')
    <script src="{{ asset('js/vendor/object-helper.js') }}"></script>
    <script>
        app.controller('DayBookController', ['$scope', '$http', function ($scope, $http) {
            $scope.company = null;
            $scope.fromRange = null;
            $scope.toRange = null;

            $scope.sales = null;
            $scope.totalSales = 0;

            $scope.cashPayments = null;
            $scope.totalCash = 0;

            $scope.chequePayments = null;
            $scope.totalCheques = 0;

            $scope.expenses = null;
            $scope.totalExpenses = 0;

            $scope.transfers = null;
            $scope.totalTransfers = 0;

            $scope.debitTotal = 0;
            $scope.creditTotal = 0;

            $scope.cashOnHand = 0;

            $scope.query = {
                fromDate: '',
                toDate: '',
                companyId: null
            };

            var url = '{{ route('finance.day.book.company.index') }}';

            $scope.companyDropDown = $('.company-drop-down');

            $scope.loading = false;

            //initiate date range drop down
            dateRangeDropDown($scope);

            $scope.companyDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    $scope.query.companyId = val;
                }
            });

            $scope.errors = [];
            $scope.book_data = [];

            $scope.filterd = false;

            $scope.generate = function (companyId) {
                $scope.loading = true;
                $http.get(url + '?' + $.param($scope.query)).then(function (response) {
                    $scope.rep = response.data.rep;
                    $scope.fromRange = response.data.fromRange;
                    $scope.toRange = response.data.toRange;

                    $scope.sales = response.data.sales;
                    $scope.totalSales = response.data.totalSales;

                    $scope.cashPayments = response.data.cashPayments;
                    $scope.totalCash = response.data.totalCash;

                    $scope.chequePayments = response.data.chequePayments;
                    $scope.totalCheques = response.data.totalCheques;

                    $scope.expenses = response.data.expenses;
                    $scope.totalExpenses = response.data.totalExpenses;

                    $scope.transfers = response.data.transfers;
                    $scope.totalTransfers = response.data.totalTransfers;

                    $scope.debitTotal = response.data.debitTotal;
                    $scope.creditTotal = response.data.creditTotal;

                    $scope.cashOnHand = response.data.cashOnHand;

                    $scope.loading = false;
                });
            };

            $scope.resetFilters = function () {
                $scope.rep = null;
                $scope.query = {
                    fromDate: '',
                    toDate: '',
                    repId: null
                };
                dateRangeDropDown($scope);
                $scope.filterd = true;
                $scope.repDropDown.dropdown('clear');
            };

        }]);
    </script>
@endsection