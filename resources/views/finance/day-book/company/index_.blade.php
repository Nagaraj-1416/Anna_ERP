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
                                                    <th>Particulars</th>
                                                    <th class="text-right" width="20%">Debit</th>
                                                    <th class="text-right" width="20%">Credit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td><u>Sales - Cash</u></td>
                                                <td class="text-right"></td>
                                                <td class="text-right" style="background-color: #efefef;">
                                                    <b>@{{ cashSales | number:2 }}</b>
                                                </td>
                                            </tr>
                                            <tr ng-repeat="rep in reps" ng-if="rep.cash_sales">
                                                <td>
                                                    <span style="padding-left: 15px;">@{{ rep.name }}</span>
                                                </td>
                                                <td></td>
                                                <td class="text-right">@{{ rep.cash_sales | number:2 }}</td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td><u>Sales - Credit</u></td>
                                                <td class="text-right"></td>
                                                <td class="text-right" style="background-color: #efefef;">
                                                    <b>@{{ creditSales | number:2 }}</b>
                                                </td>
                                            </tr>
                                            <tr ng-repeat="rep in reps" ng-if="rep.credit_sales">
                                                <td>
                                                    <span style="padding-left: 15px;">@{{ rep.name }}</span>
                                                </td>
                                                <td class="text-right">@{{ rep.credit_sales | number:2 }}</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right" style="background-color: #efefef;"><b>@{{ creditSales | number:2 }}</b></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td><u>Collections</u></td>
                                                <td class="text-right"></td>
                                                <td class="text-right" style="background-color: #efefef;">
                                                    <b>@{{ collection | number:2 }}</b>
                                                </td>
                                            </tr>
                                            <tr ng-repeat="rep in reps" ng-if="rep.collection">
                                                <td>
                                                    <span style="padding-left: 15px;">@{{ rep.name }}</span>
                                                </td>
                                                <td></td>
                                                <td class="text-right">@{{ rep.collection | number:2 }}</td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td><u>Returns (As Credit)</u></td>
                                                <td class="text-right" style="background-color: #efefef;">
                                                    <b>@{{ totalReturns | number:2 }}</b>
                                                </td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr ng-repeat="rep in reps" ng-if="rep.returns">
                                                <td>
                                                    <span style="padding-left: 15px;">@{{ rep.name }}</span>
                                                </td>
                                                <td class="text-right">
                                                    @{{ rep.returns | number:2 }}
                                                </td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td><u>Expenses</u></td>
                                                <td class="text-right" style="background-color: #efefef;">
                                                    <b>@{{ totalExpenses | number:2 }}</b>
                                                </td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr ng-repeat="rep in reps" ng-if="rep.expenses">
                                                <td>
                                                    <span style="padding-left: 15px;">@{{ rep.name }}</span>
                                                </td>
                                                <td class="text-right">
                                                    @{{ rep.expenses | number:2 }}
                                                </td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td><u>Transfers</u></td>
                                                <td class="text-right" style="background-color: #efefef;">
                                                    <b>@{{ totalTransfers | number:2 }}</b>
                                                </td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr ng-repeat="rep in reps" ng-if="rep.transfers">
                                                <td>
                                                    <span style="padding-left: 15px;">@{{ rep.name }}</span>
                                                </td>
                                                <td class="text-right">
                                                    @{{ rep.transfers | number:2 }}
                                                </td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td><u>Excess</u></td>
                                                <td class="text-right"></td>
                                                <td class="text-right" style="background-color: #efefef;">
                                                    <b>@{{ totalExcesses | number:2 }}</b>
                                                </td>
                                            </tr>
                                            <tr ng-repeat="rep in reps" ng-if="rep.excesses">
                                                <td>
                                                    <span style="padding-left: 15px;">@{{ rep.name }}</span>
                                                </td>
                                                <td class="text-right"></td>
                                                <td class="text-right">
                                                    @{{ rep.excesses | number:2 }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td><u>Shortage</u></td>
                                                <td class="text-right" style="background-color: #efefef;">
                                                    <b>@{{ totalShortages | number:2 }}</b>
                                                </td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr ng-repeat="rep in reps" ng-if="rep.shortages">
                                                <td>
                                                    <span style="padding-left: 15px;">@{{ rep.name }}</span>
                                                </td>
                                                <td class="text-right">
                                                    @{{ rep.shortages | number:2 }}
                                                </td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td style="height: 28px;"></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right"><b>TOTAL</b></td>
                                                <td class="text-right" style="background-color: #dce7e5"><b>@{{ debitTotal | number:2 }}</b></td>
                                                <td class="text-right" style="background-color: #dce7e5"><b>@{{ creditTotal | number:2 }}</b></td>
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

            $scope.cashOrders = null;
            $scope.cashSales = 0;

            $scope.creditOrders = null;
            $scope.creditSales = 0;

            $scope.payments = null;
            $scope.collection = 0;

            $scope.returns = null;
            $scope.totalReturns = 0;

            $scope.expenses = null;
            $scope.totalExpenses = 0;

            $scope.transfers = null;
            $scope.totalTransfers = 0;

            $scope.excesses = null;
            $scope.totalExcesses = 0;

            $scope.shortages = null;
            $scope.totalShortages = 0;

            $scope.debitTotal = null;
            $scope.creditTotal = 0;

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

                    $scope.cashOrders = response.data.cashOrders;
                    $scope.cashSales = response.data.cashSales;

                    $scope.creditOrders = response.data.creditOrders;
                    $scope.creditSales = response.data.creditSales;

                    $scope.payments = response.data.payments;
                    $scope.collection = response.data.collection;

                    $scope.returns = response.data.returns;
                    $scope.totalReturns = response.data.totalReturns;

                    $scope.expenses = response.data.expenses;
                    $scope.totalExpenses = response.data.totalExpenses;

                    $scope.transfers = response.data.transfers;
                    $scope.totalTransfers = response.data.totalTransfers;

                    $scope.excesses = response.data.excesses;
                    $scope.totalExcesses = response.data.totalExcesses;

                    $scope.shortages = response.data.excesses;
                    $scope.totalShortages = response.data.totalShortages;

                    $scope.debitTotal = response.data.debitTotal;
                    $scope.creditTotal = response.data.creditTotal;

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