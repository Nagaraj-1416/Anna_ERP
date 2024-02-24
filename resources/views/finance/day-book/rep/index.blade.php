@extends('layouts.master')
@section('title', 'Day Book - Rep')
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
                            <h3 class="card-title"><i class="ti-receipt"></i> Day Book - Rep</h3>
                            <h6 class="card-subtitle">
                                A <code>rep</code> & <code>date</code> filters are required to generate this day book!
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
                                <div class="form-group required @{{ hasError('rep') ? 'has-danger' : '' }}">
                                    <label class="control-label">Rep</label>
                                    <div class="ui fluid  search selection dropdown rep-drop-down @{{ hasError('rep') ? 'error' : '' }}">
                                        <input type="hidden" name="rep">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose a rep</div>
                                        <div class="menu">
                                            @foreach(repDropDown() as $key => $rep)
                                                <div class="item" data-value="{{ $key }}">{{ $rep }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="form-control-feedback">
                                        @{{ hasError('rep') ? hasError('rep') : ''}}
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
                            <p>Please wait, rep's day book is loading...</p>
                        </div>
                    </div>
                </div>

                <div class="card-body" ng-show="!loading">
                    <div class="row" ng-show="rep">
                        <div class="col-md-9">
                            <div class="ribbon-wrapper card">
                                <div class="ribbon ribbon-default">
                                    @{{ rep.name }}'s Day Book
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
                                                    <td>Sales - Cash</td>
                                                    <td class="text-right"></td>
                                                    <td class="text-right" style="background-color: #efefef;">
                                                        <b>@{{ cashSales | number:2 }}</b>
                                                    </td>
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
                                                <tr ng-repeat="creditOrder in creditOrders">
                                                    <td>
                                                        <span style="padding-left: 15px;">
                                                            @{{ creditOrder.customer.display_name }} |
                                                            <a target="_blank" href="/sales/order/@{{ creditOrder.id }}">
                                                                @{{ creditOrder.ref }}
                                                            </a>
                                                        </span>
                                                    </td>
                                                    <td class="text-right">@{{ creditOrder.balance | number:2 }}</td>
                                                    <td class="text-right"></td>
                                                </tr>
                                                <tr>
                                                    <td style="height: 28px;"></td>
                                                    <td class="text-right" style="background-color: #efefef;"><b>@{{ creditSales | number:2 }}</b></td>
                                                    <td class="text-right"></td>
                                                </tr>
                                                <tr>
                                                    <td><u>Collections</u></td>
                                                    <td class="text-right"></td>
                                                    <td class="text-right"></td>
                                                </tr>
                                                <tr ng-repeat="payment in payments">
                                                    <td>
                                                        <span style="padding-left: 15px;">
                                                            @{{ payment.customer.display_name }} |
                                                            <a target="_blank" href="/sales/order/@{{ payment.order.id }}">
                                                                @{{ payment.order.ref }}
                                                            </a>
                                                        </span>
                                                    </td>
                                                    <td class="text-right"></td>
                                                    <td class="text-right">@{{ payment.payment | number:2 }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="height: 28px;"></td>
                                                    <td class="text-right"></td>
                                                    <td class="text-right" style="background-color: #efefef;">
                                                        <b>@{{ collection | number:2 }}</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="height: 28px;"></td>
                                                    <td class="text-right"></td>
                                                    <td class="text-right"></td>
                                                </tr>
                                                <tr>
                                                    <td>Returns (As Credit)</td>
                                                    <td class="text-right" style="background-color: #efefef;">
                                                        <b>@{{ totalReturns | number:2 }}</b>
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
                                                    <td class="text-right"></td>
                                                    <td class="text-right"></td>
                                                </tr>
                                                <tr ng-repeat="expense in expenses">
                                                    <td><span style="padding-left: 15px;">@{{ expense.type.name }}</span></td>
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
                                                    <td>Transfer</td>
                                                    <td class="text-right" style="background-color: #efefef;">
                                                        <b>@{{ totalTransfers | number:2 }}</b>
                                                    </td>
                                                    <td class="text-right"></td>
                                                </tr>
                                                <tr>
                                                    <td style="height: 28px;"></td>
                                                    <td class="text-right"></td>
                                                    <td class="text-right"></td>
                                                </tr>
                                                <tr>
                                                    <td>Excess</td>
                                                    <td class="text-right"></td>
                                                    <td class="text-right" style="background-color: #efefef;">
                                                        <b>@{{ totalExcesses | number:2 }}</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="height: 28px;"></td>
                                                    <td class="text-right"></td>
                                                    <td class="text-right"></td>
                                                </tr>
                                                <tr>
                                                    <td>Shortage</td>
                                                    <td class="text-right" style="background-color: #efefef;">
                                                        <b>@{{ totalShortages | number:2 }}</b>
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
                    <div class="row" ng-show="!rep && !loading">
                        <div class="col-md-12">
                            <hr>
                            <span class="text-muted">Please choose a rep to generate the day book report!</span>
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
            $scope.rep = null;
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

            $scope.query = {
                date: '',
                repId: null
            };

            var url = '{{ route('finance.day.book.rep.index') }}';

            $scope.repDropDown = $('.rep-drop-down');

            $scope.loading = false;

            //initiate date range drop down
            dateRangeDropDown($scope);

            $scope.repDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    $scope.query.repId = val;
                }
            });

            $scope.errors = [];
            $scope.book_data = [];

            $scope.filterd = false;

            $scope.generate = function (repId) {
                $scope.loading = true;
                $http.get(url + '?' + $.param($scope.query)).then(function (response) {
                    $scope.rep = response.data.rep;
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
                $scope.repDropDown.dropdown('clear');
            };

        }]);
    </script>
@endsection