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
                                A <code>Company</code> & <code>Date range</code> filters are required to generate this day book!
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
                            <p>Please wait, day book is loading...</p>
                        </div>
                    </div>
                </div>

                <div class="card-body" ng-show="!loading">

                    <div class="row" ng-show="company">
                        <div class="col-md-12">
                            <div class="ribbon-wrapper card">
                                <div class="ribbon ribbon-default">
                                    @{{ company.name }}'s Day Book
                                    <code>From</code> @{{ fromRange | date }} <code>To</code> @{{ toRange | date }}
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="ui celled structured table collapse-table">
                                            <thead>
                                            <tr>
                                                <th>ACCOUNT</th>
                                                <th class="text-right" width="15%">DEBIT</th>
                                                <th class="text-right" width="15%">CREDIT</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr style="background-color: #e0e7eb;">
                                                <td colspan="3"><u>SALES</u></td>
                                            </tr>
                                            <tr>
                                                <td>Sales made for the period</td>
                                                <td class="text-right"></td>
                                                <td class="text-right">@{{ sales | number:2 }}</td>
                                            </tr>
                                            <tr style="background-color: #e0e7eb;">
                                                <td colspan="3"><u>PAYMENTS RECEIVED</u></td>
                                            </tr>

                                            <tr>
                                                <td>Cash received</td>
                                                <td class="text-right">@{{ cashReceived | number:2 }}</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Cheque received</td>
                                                <td class="text-right">@{{ chequeReceived | number:2 }}</td>
                                                <td></td>
                                            </tr>
                                            <tr style="background-color: #e0e7eb;">
                                                <td colspan="3"><u>EXPENSES</u></td>
                                            </tr>
                                            <tr ng-repeat="expense in expenses">
                                                <td>
                                                    @{{ expense.type.name }}
                                                    <small>(@{{ expense.notes }} - by @{{ expense.prepared_by.name }})</small>
                                                </td>
                                                <td class="text-right">
                                                    @{{ expense.amount | number:2 }}
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right"><b>TOTAL EXPENSE</b></td>
                                                <td class="text-right"><b>(@{{ expensesTotal | number:2 }})</b></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right"><b>SHORTAGE</b></td>
                                                <td class="text-right"><b>(@{{ shortage | number:2 }})</b></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right"><b>EXCESS</b></td>
                                                <td class="text-right"><b>@{{ excess | number:2 }}</b></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right"><b>BALANCE CASH</b></td>
                                                <td class="text-right"><b>@{{ balCash | number:2 }}</b></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" ng-show="!company && !loading">
                        <div class="col-md-12">
                            <hr>
                            <span class="text-muted">Please choose company to generate the day book report</span>
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
            $scope.cashReceived = null;
            $scope.chequeReceived = null;
            $scope.expenses = null;
            $scope.expensesTotal = null;
            $scope.balCash = null;
            $scope.shortage = null;
            $scope.excess = null;

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
                    $scope.company = response.data.company;
                    $scope.fromRange = response.data.fromRange;
                    $scope.toRange = response.data.toRange;
                    $scope.sales = response.data.sales;
                    $scope.cashReceived = response.data.cashReceived;
                    $scope.chequeReceived = response.data.chequeReceived;
                    $scope.expenses = response.data.expenses;
                    $scope.expensesTotal = response.data.expensesTotal;
                    $scope.balCash = response.data.balCash;
                    $scope.shortage = response.data.shortage;
                    $scope.excess = response.data.excess;
                    $scope.loading = false;
                });
            };

            $scope.resetFilters = function () {
                $scope.company = null;
                $scope.query = {
                    fromDate: '',
                    toDate: '',
                    companyId: null
                };
                dateRangeDropDown($scope);
                $scope.filterd = true;
                $scope.companyDropDown.dropdown('clear');
            };

        }]);
    </script>
@endsection