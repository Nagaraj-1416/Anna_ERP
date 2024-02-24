@extends('layouts.master')
@section('title', 'Trial Balance')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
<div class="row" ng-controller="TrialBalanceController">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body p-b-0">
                <div class="clearfix">
                    <div class="pull-left">
                        <h3 class="card-title"><i class="ti-receipt"></i> Trial Balance</h3>
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
                    <h2 class="text-center"><b>Trial Balance</b></h2>
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
                            <th>ACCOUNTS</th>
                            <th width="15%">TYPE</th>
                            <th width="10%">CATEGORY</th>
                            <th width="10%" class="text-right">DEBIT</th>
                            <th width="10%" class="text-right">CREDIT</th>
                            <th width="10%" class="text-right">BALANCE</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="account in accounts" ng-show="length">
                            <td>
                                <a target="_blank" href="/finance/account/@{{ account.id }}?from=@{{ fromDate }}&to=@{{ toDate }}">
                                    @{{ account.name }}
                                </a>
                            </td>
                            <td>@{{ account.type.name }}</td>
                            <td>@{{ account.category.name }}</td>
                            <td class="text-right">
                                @{{ account.acc_debit_bal | number:2 }}
                            </td>
                            <td class="text-right">
                                @{{ account.acc_credit_bal | number:2 }}
                            </td>
                            <td class="text-right">@{{ account.acc_running_bal | number:2 }}</td>
                        </tr>
                        <tr ng-show="!length">
                            <td colspan="7">No data to display...</td>
                        </tr>
                        </tbody>
                        <tfoot>
                            <tr style="background-color: #ecf0f3;">
                                <td class="text-right" colspan="3">
                                    <b>TOTAL</b>
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
        app.controller('TrialBalanceController', ['$scope', '$http', function ($scope, $http) {
            $scope.query = {
                fromDate: '',
                toDate: '',
                company: '',
                account: ''
            };

            $scope.loading = false;

            $scope.products = [];

            $scope.daterangeDD = $('.date-range');
            $scope.daterangeValue = '';

            $scope.dropdowns = {
                company: $('.company-drop-down'),
                account: $('.account-drop-down')
            };

            $scope.dropdowns.company.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val, name) {
                    $scope.query.company = val;
                    $scope.companyName = name;
                    $scope.dropdowns.account.dropdown('clear');
                    accountDropDown(val);
                }
            });

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

            //Initiate Date Range Drop down
            dateRangeDropDown($scope);

            $scope.length = 0;

            // Generate Data using filters
            $scope.generate = function () {
                $scope.fromDate = $scope.query.fromDate;
                $scope.toDate = $scope.query.toDate;
                $scope.loading = true;
                var reportRoute = '{{ route('finance.trial.balance.index') }}';
                $http.get(reportRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.intBalView = response.data.intBalView;
                    $scope.intBalType = response.data.intBalType;
                    $scope.accounts = response.data.accounts;
                    $scope.debitBal = response.data.debitBal;
                    $scope.creditBal = response.data.creditBal;
                    $scope.endBal = response.data.endBal;
                    $scope.loading = false;
                    $scope.length = _.toArray($scope.accounts).length;
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
