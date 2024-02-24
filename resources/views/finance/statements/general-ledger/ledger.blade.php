@extends('layouts.master')
@section('title', 'General Ledger')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
<div class="row" ng-controller="GeneralLedgerController">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body p-b-0">
                <div class="clearfix">
                    <div class="pull-left">
                        <h3 class="card-title"><i class="ti-receipt"></i> General Ledger</h3>
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
                                <div class="ui fluid  search selection dropdown company-drop-down @{{ hasError('company') ? 'error' : '' }}">
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
                        <div class="col-md-3">
                            <div class="form-group required @{{ hasError('account') ? 'has-danger' : '' }}">
                                <label class="control-label">Chart of account</label>
                                <div class="ui fluid  search selection dropdown account-drop-down @{{ hasError('account') ? 'error' : '' }}">
                                    <input type="hidden" name="account">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose an account</div>
                                    <div class="menu">
                                        @foreach(accountsDropDown() as $key => $account)
                                            <div class="item" data-value="{{ $key }}">{{ $account }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                <p class="form-control-feedback">
                                    @{{ hasError('account') ? hasError('account') : ''}}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group {{ $errors->has('customer_id') ? 'has-danger' : '' }}">
                                <label class="control-label">Customer</label>
                                <div class="ui fluid  search selection dropdown customer-drop-down {{ $errors->has('customer_id') ? 'error' : '' }}">
                                    <input type="hidden" name="customer_id">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a customer</div>
                                    <div class="menu">
{{--                                        @foreach(customerDropDown() as $key => $customer)--}}
{{--                                            <div class="item" data-value="{{ $key }}">{{ $customer }}</div>--}}
{{--                                        @endforeach--}}
                                    </div>
                                </div>
                                <p class="form-control-feedback">{{ $errors->first('customer_id') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group {{ $errors->has('supplier_id') ? 'has-danger' : '' }}">
                                <label class="control-label">Supplier</label>
                                <div class="ui fluid search selection dropdown supplier-drop-down {{ $errors->has('supplier_id') ? 'error' : '' }}">
                                    <input type="hidden" name="supplier_id">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a customer</div>
                                    <div class="menu"></div>
                                </div>
                                <p class="form-control-feedback">{{ $errors->first('supplier_id') }}</p>
                            </div>
                        </div>
                        {{--<div class="col-md-3">
                            <div class="form-group required @{{ hasError('trans_type') ? 'has-danger' : '' }}">
                                <label class="control-label">Transaction type</label>
                                <div class="ui fluid  search selection dropdown trans-type-drop-down @{{ hasError('trans_type') ? 'error' : '' }}">
                                    <input type="hidden" name="trans_type">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a type</div>
                                    <div class="menu">
                                        @foreach(transTypeDropDown() as $key => $account)
                                            <div class="item" data-value="{{ $key }}">{{ $account }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                <p class="form-control-feedback">
                                    @{{ hasError('trans_type') ? hasError('trans_type') : ''}}
                                </p>
                            </div>
                        </div>--}}
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
                    <h2 class="text-center"><b>General Ledger</b></h2>
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
                    <table class="table color-table muted-table">
                        <thead>
                            <tr>
                                <th width="8%">DATE</th>
                                <th width="10%">TYPE</th>
                                <th width="15%">ORDER / INVOICE# / BILL#</th>
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
                                    <span ng-if="intBalType == 'Debit'"><b>@{{ intBalView | number:2 }}</b></span>
                                </td>
                                <td class="text-right" width="15%">
                                    <span ng-if="intBalType == 'Credit'"><b>@{{ intBalView | number:2 }}</b></span>
                                </td>
                                <td class="text-right" width="15%"><b>@{{ intBalView | number:2 }}</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="tran in trans" ng-show="length">
                                <td width="10%">@{{ tran.date | date }}</td>
                                <td width="10%">@{{ tran.tran_type }}</td>
                                <td width="10%">
                                    <a target="_blank" href="@{{ tran.tran_ref_url }}@{{ tran.tran_ref_id }}">
                                        @{{ tran.tran_ref_no }}
                                    </a>
                                </td>
                                <td>
                                    <span ng-if="tran.tran_des_short == 'Sales In' || tran.tran_des_short == 'Expense'">
                                        <span class="text-warning">@{{ tran.tran_des_short }}</span>
                                    </span>
                                    <span ng-if="tran.tran_des_short == 'Cash In' || tran.tran_des_short == 'Transfer'">
                                        <span class="text-green">@{{ tran.tran_des_short }}</span>
                                    </span>
                                    <span ng-if="tran.tran_des_short == 'Cheque Realised'">
                                        <span class="text-green">@{{ tran.tran_des_short }}</span>
                                    </span>
                                    <span ng-if="tran.tran_des_short == 'Payment Cancel'
                                    || tran.tran_des_short == 'Invoice Cancel'
                                    || tran.tran_des_short == 'Sales Return'
                                    || tran.tran_des_short == 'Cheque Bounced'">
                                        <span class="text-danger">@{{ tran.tran_des_short }}</span>
                                    </span>
                                    <br />
                                    @{{ tran.transaction.auto_narration }}
                                    <br />
                                    <a target="_blank" href="/finance/transaction/@{{ tran.transaction.id }}">View Journal</a>
                                </td>
                                <td class="text-right">
                                    <span ng-show="tran.type == 'Debit'">
                                        @{{ tran.amount | number:2 }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <span ng-show="tran.type == 'Credit'">
                                        @{{ tran.amount | number:2 }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    @{{ tran.balanceView | number:2 }}
                                </td>
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
                                <td class="text-right" width="10%"><b>@{{ debitBal | number:2 }}</b></td>
                                <td class="text-right" width="10%"><b>@{{ creditBal | number:2 }}</b></td>
                                <td class="text-right" width="10%"><b>@{{ endBal | number:2 }}</b></td>
                            </tr>
                        </tfoot>
                    </table>
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
    <script src="{{ asset('js/vendor/object-helper.js') }}"></script>
    @include('report.general.date-range-script')
    <script>
        app.controller('GeneralLedgerController', ['$scope', '$http', function ($scope, $http) {
            $scope.query = {
                fromDate: '',
                toDate: '',
                company: '',
                account: '',
                type: '',
                customer_id: '',
                supplier_id: '',
            };

            $scope.loading = false;

            $scope.products = [];

            $scope.daterangeDD = $('.date-range');
            $scope.daterangeValue = '';

            $scope.dropdowns = {
                company: $('.company-drop-down'),
                account: $('.account-drop-down'),
                type: $('.trans-type-drop-down'),
                customer_id: $('.customer-drop-down'),
                supplier_id: $('.supplier-drop-down'),
            };

            $scope.dropdowns.company.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val, name) {
                    $scope.query.company = val;
                    $scope.companyName = name;
                    $scope.dropdowns.account.dropdown('clear');
                    accountDropDown(val);
                    customerAndSupplierDropdown();
                }
            });

            function customerAndSupplierDropdown() {
                $scope.query.customer_id = '';
                $scope.query.supplier_id = '';

                const url = '{{ route('finance.general.ledger.customer-and-suppliers') }}'
                $http.get(url + '?' + $.param($scope.query)).then(function (response) {

                    $scope.dropdowns.supplier_id.dropdown({
                        clearable: true,
                        forceSelection: false,
                        placeholder: "choose a supplier",
                        values: response.data.suppliers.map(customer => {
                            return {
                                name: customer.full_name,
                                value: customer.id,
                            };
                        }),
                        onChange: function (val) {
                            $scope.query.supplier_id = val;
                        }
                    });

                    $scope.dropdowns.customer_id.dropdown({
                        clearable: true,
                        forceSelection: false,
                        placeholder: "choose a customer",
                        values: response.data.customers.map(customer => {
                            return {
                                name: customer.full_name,
                                value: customer.id,
                            };
                        }),
                        onChange: function (val) {
                            $scope.query.customer_id = val;
                        }
                    });
                })
            }

            function accountDropDown(company) {
                var url = '{{ route('finance.account.by.company.search', ['companyId']) }}';
                url = url.replace('companyId', company);
                $scope.dropdowns.account.dropdown('setting', {
                    forceSelection: false,
                    apiSettings: {
                        url: url + '/{query}',
                        cache:false,
                    },
                    saveRemoteData:false,
                    onChange: function (val, name) {
                        $scope.query.account = val;
                        $scope.accountName = name;
                    }
                });
            }

            /*$scope.dropdowns.account.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val, name) {
                    $scope.query.account = val;
                    $scope.accountName = name;
                }
            });*/

            $scope.dropdowns.type.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val, name) {
                    $scope.query.type = val;
                    $scope.typeName = name;
                }
            });

            //Initiate Date Range Drop down
            dateRangeDropDown($scope);

            $scope.length = 0;

            // Generate Data using filters
            $scope.generate = function () {
                if (!($scope.query.company && $scope.query.account)) {
                    return;
                }

                $scope.fromDate = $scope.query.fromDate;
                $scope.toDate = $scope.query.toDate;
                $scope.loading = true;
                var reportRoute = '{{ route('finance.general.ledger.index') }}';
                $http.get(reportRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.intBalView = response.data.intBalView;
                    $scope.intBalType = response.data.intBalType;
                    $scope.account = response.data.account;
                    $scope.trans = response.data.trans;
                    $scope.debitBal = response.data.debitBal;
                    $scope.creditBal = response.data.creditBal;
                    $scope.endBal = response.data.endBal;
                    $scope.loading = false;
                    $scope.length = _.toArray($scope.trans).length;
                })
            };

            // Reset Filters
            $scope.resetFilters = function () {
                $scope.query = {
                    fromDate: '',
                    toDate: '',
                    company: '',
                    account: ''
                };
                $scope.daterangeDD.dropdown('clear');
                $scope.daterangeDD.dropdown('set text', 'Today').dropdown('set value', 'today');
                $scope.dropdowns.company.dropdown('clear');
                $scope.dropdowns.account.dropdown('clear');
                $scope.generate();
            };

        }]);
    </script>
@endsection
