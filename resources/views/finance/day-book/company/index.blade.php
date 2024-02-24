@extends('layouts.master')
@section('title', 'Day Book - Company')
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
                            <h3 class="card-title"><i class="ti-receipt"></i> Day Book - Company</h3>
                            <h6 class="card-subtitle">
                                A <code>company</code> & <code>date</code> filters are required to generate this day book!
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
                        @include('report.general.date.index')
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
                            <p>Please wait, company's day book is loading...</p>
                        </div>
                    </div>
                </div>

                <div class="card-body" ng-show="!loading">
                    <div class="row" ng-show="company">
                        <div class="col-md-9">
                            <div class="ribbon-wrapper card">
                                <div class="ribbon ribbon-default">
                                    @{{ company.name }}'s Day Book
                                    <code>for</code> @{{ fromRange | date }}
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="ui celled structured table collapse-table">
                                            <thead>
                                                <tr>
                                                    <th style="background: #dce7e5;">Starting Balance</th>
                                                    <th class="text-right" width="20%" style="background: #dce7e5;">
                                                        <span ng-if="openingBalType == 'Debit'">
                                                            <b>@{{ openingBal | number:2 }}</b>
                                                        </span>
                                                    </th>
                                                    <th class="text-right" width="20%" style="background: #dce7e5;">
                                                        <span ng-if="openingBalType == 'Credit'">
                                                            <b>@{{ openingBal | number:2 }}</b>
                                                        </span>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <span style="padding-left: 20px;">
                                                            <b>Closing CF Cash :</b>
                                                            <span class="text-info">@{{ openingCashAccBal | number:2 }}</span>
                                                        </span>
                                                    </th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <span style="padding-left: 20px;">
                                                            <b>Closing CF Cheque : </b>
                                                            <span class="text-info">@{{ openingChequeAccBal | number:2 }}</span>
                                                        </span>
                                                    </th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                <tr>
                                                    <th style="background: #ecf7f5">Particulars</th>
                                                    <th class="text-right" width="20%" style="background: #ecf7f5;">Debit</th>
                                                    <th class="text-right" width="20%" style="background: #ecf7f5;">Credit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td><u>Cash Transfers</u></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr ng-repeat="cashTransfer in cashTransfers">
                                                <td>
                                                    @{{ cashTransfer.transaction.auto_narration }}<br />
                                                    <b>From</b> @{{ cashTransfer.route }}
                                                </td>
                                                <td class="text-right"></td>
                                                <td class="text-right">@{{ cashTransfer.amount | number:2 }}</td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right"></td>
                                                <td class="text-right" style="background-color: #efefef;">
                                                    <b>@{{ totalCashTransfers | number:2 }}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td><u>Cheque Transfers</u></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr ng-repeat="chequeTransfer in chequeTransfers">
                                                <td>
                                                    @{{ chequeTransfer.transaction.auto_narration }}<br />
                                                    <b>From</b> @{{ chequeTransfer.route }}
                                                </td>
                                                <td class="text-right"></td>
                                                <td class="text-right">@{{ chequeTransfer.amount | number:2 }}</td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right"></td>
                                                <td class="text-right" style="background-color: #efefef;">
                                                    <b>@{{ totalChequeTransfers | number:2 }}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td><u>Total Transfers</u></td>
                                                <td class="text-right"></td>
                                                <td class="text-right" style="background-color: #efefef;">
                                                    <b>@{{ totalTransfers | number:2 }}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td><u>Goods Purchased</u></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr ng-repeat="purchase in purchases">
                                                <td>
                                                    <span>
                                                        @{{ purchase.transaction.auto_narration }}
                                                    </span>
                                                </td>
                                                <td class="text-right">@{{ purchase.amount | number:2 }}</td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right" style="background-color: #efefef;">
                                                    <b>@{{ totalPurchases | number:2 }}</b>
                                                </td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td><u>Goods Sold</u></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr ng-repeat="goodSold in goodsSold">
                                                <td>
                                                    <span>
                                                        @{{ goodSold.transaction.auto_narration }}
                                                    </span>
                                                </td>
                                                <td class="text-right"></td>
                                                <td class="text-right">@{{ goodSold.amount | number:2 }}</td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right"></td>
                                                <td class="text-right" style="background-color: #efefef;">
                                                    <b>@{{ totalGoodsSold | number:2 }}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td><u>Expenses</u></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr ng-repeat="expense in expenses">
                                                <td>
                                                    <span>
                                                        @{{ expense.type.name }} - @{{ expense.expense_account.name }}
                                                    </span><br />
                                                    <span>
                                                        @{{ expense.notes }}
                                                    </span>
                                                </td>
                                                <td class="text-right">@{{ expense.amount | number:2 }}</td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right" style="background-color: #efefef;">
                                                    <b>@{{ totalExpenses | number:2 }}</b>
                                                </td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>

                                            <tr>
                                                <td><u>Deposits</u></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr ng-repeat="transferDebitRecord in transferDebitRecords">
                                                <td><span>@{{ transferDebitRecord.transaction.auto_narration }}</span></td>
                                                <td class="text-right"></td>
                                                <td class="text-right">@{{ transferDebitRecord.amount | number:2 }}</td>
                                            </tr>
                                            <tr ng-repeat="transferCreditRecord in transferCreditRecords">
                                                <td><span>@{{ transferCreditRecord.transaction.auto_narration }}</span></td>
                                                <td class="text-right">@{{ transferCreditRecord.amount | number:2 }}</td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right" style="background-color: #efefef;">
                                                    <b>@{{ transferCreditBal | number:2 }}</b>
                                                </td>
                                                <td class="text-right" style="background-color: #efefef;">
                                                    <b>@{{ transferDebitBal | number:2 }}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td><u>Cheques Returned</u></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr ng-repeat="returnedCheque in returnedCheques">
                                                <td>
                                                    <span>
                                                        @{{ returnedCheque.auto_narration }}
                                                        - @{{ returnedCheque.tx_type.name }}
                                                    </span>
                                                </td>
                                                <td class="text-right">@{{ returnedCheque.amount | number:2 }}</td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right" style="background-color: #efefef;">
                                                    <b>@{{ totalReturnedCheques | number:2 }}</b>
                                                </td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td><u>Other Transactions</u></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr ng-repeat="debitRecord in debitRecords">
                                                <td>
                                                    <span>
                                                        @{{ debitRecord.transaction.auto_narration }}
                                                        - @{{ debitRecord.transaction.tx_type.name }}
                                                    </span>
                                                </td>
                                                <td class="text-right"></td>
                                                <td class="text-right">@{{ debitRecord.amount | number:2 }}</td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right"></td>
                                                <td class="text-right" style="background-color: #efefef;">
                                                    <b>@{{ debitBal | number:2 }}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr ng-repeat="creditRecord in creditRecords">
                                                <td>
                                                    <span>
                                                        @{{ creditRecord.transaction.auto_narration }}
                                                        - @{{ creditRecord.transaction.tx_type.name }}
                                                    </span>
                                                </td>
                                                <td class="text-right">@{{ creditRecord.amount | number:2 }}</td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right" style="background-color: #efefef;">
                                                    <b>@{{ creditBal | number:2 }}</b>
                                                </td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right" style="background-color: #ecf7f5"><b>TOTAL</b></td>
                                                <td class="text-right" style="background-color: #ecf7f5"><b>@{{ debitTotal | number:2 }}</b></td>
                                                <td class="text-right" style="background-color: #ecf7f5"><b>@{{ creditTotal | number:2 }}</b></td>
                                            </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>
                                                        <span style="padding-left: 20px;">
                                                            <b>Closing Cash : </b>
                                                            <span class="text-info">@{{ closingCashAccBal | number:2 }}</span>
                                                        </span>
                                                    </th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <span style="padding-left: 20px;">
                                                            <b>Closing Cheque : </b>
                                                            <span class="text-info">@{{ closingChequeAccBal | number:2 }}</span>
                                                        </span>
                                                    </th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                <tr>
                                                    <th style="background: #dce7e5;">Closing Balance</th>
                                                    <th class="text-right" width="20%" style="background: #dce7e5;">
                                                        <span ng-if="closingBalType == 'Debit'">
                                                            <b>@{{ closingBal | number:2 }}</b>
                                                        </span>
                                                    </th>
                                                    <th class="text-right" width="20%" style="background: #dce7e5;">
                                                        <span ng-if="closingBalType == 'Credit'">
                                                            <b>@{{ closingBal | number:2 }}</b>
                                                        </span>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" ng-show="!company && !loading">
                        <div class="col-md-12">
                            <hr>
                            <span class="text-muted">Please choose a company to generate the day book report!</span>
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
    @include('report.general.date.script')
    <script src="{{ asset('js/vendor/object-helper.js') }}"></script>
    <script>
        app.controller('DayBookController', ['$scope', '$http', function ($scope, $http) {
            $scope.company = null;
            $scope.fromRange = null;

            $scope.openingCashAccBal = 0;
            $scope.openingChequeAccBal = 0;

            $scope.purchases = null;
            $scope.totalPurchases = 0;

            $scope.goodsSold = null;
            $scope.totalGoodsSold = 0;

            $scope.expenses = null;
            $scope.totalExpenses = 0;

            $scope.transfers = null;
            $scope.totalTransfers = 0;

            $scope.deposits = null;
            $scope.totalDeposits = 0;

            $scope.returnedCheques = null;
            $scope.totalReturnedCheques = 0;

            $scope.cashTransfers = null;
            $scope.totalCashTransfers = 0;

            $scope.chequeTransfers = null;
            $scope.totalChequeTransfers = 0;

            $scope.transferDebitRecords = null;
            $scope.transferDebitBal = 0;

            $scope.transferCreditRecords = null;
            $scope.transferCreditBal = 0;

            $scope.debitRecords = null;
            $scope.debitBal = 0;

            $scope.creditRecords = null;
            $scope.creditBal = 0;

            $scope.openingBalType = null;
            $scope.openingBal = 0;

            $scope.closingBalType = null;
            $scope.closingBal = 0;

            $scope.debitTotal = null;
            $scope.creditTotal = 0;

            $scope.closingCashAccBal = 0;
            $scope.closingChequeAccBal = 0;

            $scope.reps = null;

            $scope.query = {
                date: '',
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
                    $scope.company = response.data.company;
                    $scope.fromRange = response.data.fromRange;

                    $scope.openingCashAccBal = response.data.openingCashAccBal;
                    $scope.openingChequeAccBal = response.data.openingChequeAccBal;

                    $scope.purchases = response.data.purchases;
                    $scope.totalPurchases = response.data.totalPurchases;

                    $scope.goodsSold = response.data.goodsSold;;
                    $scope.totalGoodsSold = response.data.totalGoodsSold;;

                    $scope.expenses = response.data.expenses;
                    $scope.totalExpenses = response.data.totalExpenses;

                    $scope.transfers = response.data.transfers;
                    $scope.totalTransfers = response.data.totalTransfers;

                    $scope.deposits = response.data.deposits;
                    $scope.totalDeposits = response.data.totalDeposits;

                    $scope.returnedCheques = response.data.returnedCheques;
                    $scope.totalReturnedCheques = response.data.totalReturnedCheques;

                    $scope.cashTransfers = response.data.cashTransfers;
                    $scope.totalCashTransfers = response.data.totalCashTransfers;

                    $scope.chequeTransfers = response.data.chequeTransfers;
                    $scope.totalChequeTransfers = response.data.totalChequeTransfers;

                    $scope.transferDebitRecords = response.data.transferDebitRecords;
                    $scope.transferDebitBal = response.data.transferDebitBal;

                    $scope.transferCreditRecords = response.data.transferCreditRecords;
                    $scope.transferCreditBal = response.data.transferCreditBal;

                    $scope.debitRecords = response.data.debitRecords;
                    $scope.debitBal = response.data.debitBal;

                    $scope.creditRecords = response.data.creditRecords;
                    $scope.creditBal = response.data.creditBal;

                    $scope.openingBalType = response.data.openingBalType;
                    $scope.openingBal = response.data.openingBal;

                    $scope.closingBalType = response.data.closingBalType;
                    $scope.closingBal = response.data.closingBal;

                    $scope.debitTotal = response.data.debitTotal;
                    $scope.creditTotal = response.data.creditTotal;

                    $scope.closingCashAccBal = response.data.closingCashAccBal;;
                    $scope.closingChequeAccBal = response.data.closingChequeAccBal;;

                    $scope.reps = response.data.reps;

                    $scope.loading = false;
                });
            };

            $scope.resetFilters = function () {
                $scope.rep = null;
                $scope.query = {
                    date: '',
                    repId: null
                };
                dateRangeDropDown($scope);
                $scope.filterd = true;
                $scope.companyDropDown.dropdown('clear');
            };

        }]);
    </script>
@endsection