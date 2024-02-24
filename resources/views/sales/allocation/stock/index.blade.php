@extends('layouts.master')
@section('title', 'Stock Allocations')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
<div class="row" ng-controller="DailyStockController">
    <div class="col-12">
        <div class="card">
            <!-- .left-right-aside-column-->
            <div class="contact-page-aside">
                <!-- .left-aside-column-->
                <div class="left-aside">
                    <a target="_blank" href="{{ route('daily.stock.create') }}" class="btn btn-info btn-block">
                        <i class="fa fa-plus"></i> Allocate
                    </a>
                    <ul class="list-style-none">
                        <li class="text-muted m-t-20">FILTER STOCK BY</li>
                        <li class="divider"></li>
                        <li ng-class="{'active': !query.filter}"
                            ng-click="filterUpdate()"><a
                                    href="">All Allocations</a></li>
                        <li ng-class="{'active': query.filter === 'recentlyCreated'}"
                            ng-click="filterUpdate('recentlyCreated')"><a href="">Recently Created</a></li>
                        <li ng-class="{'active': query.filter === 'recentlyUpdated'}"
                            ng-click="filterUpdate('recentlyUpdated')"><a href="">Recently Modified</a></li>
                    </ul>
                    <hr>
                    <ul class="list-style-none">
                        <li class="text-muted" ng-click="resetFilters()">
                            <a class="text-primary" href="">Reset Filters</a>
                        </li>
                    </ul>
                </div>
                <!-- /.left-aside-column-->
                <div class="right-aside custom-right-aside">
                    <div class="right-page-header">
                        <div class="d-flex m-b-10">
                            <div class="align-self-center">
                                <h2 class="card-title m-t-10">Stock Allocations @{{ total ? ("(" + total +")") :
                                    '' }}</h2>
                            </div>
                            <div class="ml-auto">
                                <input type="text" id="demo-input-search2" ng-model="searchStocks"
                                       placeholder="search for stocks here" class="form-control"
                                       ng-change="filterUpdated()">
                            </div>
                        </div>
                    </div>
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
                            <p>loading stocks</p>
                        </div>
                    </div>
                    <div class="row" ng-hide="loading">
                        <div class="col-md-12 m-b-20">
                            <table id="demo-foo-addrow" class="table m-t-10 table-hover no-wrap contact-list" data-page-size="10">
                                <thead>
                                <tr>
                                    <th>Sales location details</th>
                                    <th>Route & product details</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody class="text-muted">
                                    <tr ng-repeat="stock in stocks">
                                        <td style="vertical-align: middle;">
                                            <a href="/sales/daily-stock/@{{ stock.id }}" target="_blank">
                                                @{{ stock.sale_location.name }}
                                            </a>
                                            <br />
                                            <small>
                                                @{{  stock.rep.name }}
                                            </small>
                                        </td>
                                        <td style="vertical-align: middle;">
                                            @{{ stock.route.name }}<br ng-show="stock.route" />
                                            <small>
                                                Required products: @{{  stock.items.length }}
                                            </small>
                                        </td>
                                        <td style="vertical-align: middle;">
                                            <span ng-class="stock.status == 'Pending' ? 'text-warning' : '' ||
                                            stock.status == 'Allocated' ? 'text-green' : '' ||
                                            stock.status == 'Canceled' ? 'text-danger' : ''">
                                                @{{ stock.status }}
                                            </span>
                                            <br />
                                            <small>
                                                Prepared by: @{{ stock.prepared_by.name }}
                                            </small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- pagination panel -->
                        <div class="col-md-12" ng-show="checkPagination()">
                            @include('general.pagination.pagination')
                        </div>
                    </div>
                    <div class="row" ng-hide="loading" ng-if="stocks.length === 0 && !filterd">
                        <div class="col-md-12">
                            <div class="card text-center">
                                <div class="card-body">
                                    <p class="card-text">You haven't added any stock allocations yet, click on "Allocate" button to continue.</p>
                                    <a target="_blank" href="{{ route('daily.stock.create') }}"
                                       class="btn btn-info">
                                        <i class="fa fa-plus"></i> Allocate
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- if there no credit available message -->
                    <div class="row" ng-hide="loading" ng-if="stocks.length === 0 && filterd">
                        <div class="col-md-12">
                            <div class="card text-center">
                                <div class="card-body">
                                    <p class="card-text">There are <code>no</code> stock allocations found.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- .left-aside-column-->
                </div>
                <!-- /.left-right-aside-column-->
            </div>
        </div>
    </div>
</div>
@endsection
@section('style')
@endsection
@section('script')
    <script>
        app.controller('DailyStockController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('daily.stock.index') }}';
            $scope.stocks = [];
            $scope.filterd = false;
            $scope.loading = true;
            $scope.pagination = {};
            $scope.currentPaginationPage = 0;
            $scope.query = {
                ajax: true,
                page: null,
                filter: null,
                search: null
            };

            $scope.filterUpdated = function () {
                $scope.filterd = true;
                $scope.query.search = $scope.searchStocks;
                $scope.fetchStocks();
            };

            $scope.filterUpdate = function (filter) {
                $scope.query.filter = filter ? filter : '';
                $scope.filterd = true;
                $scope.fetchStocks();
            };

            $scope.range = function () {
                var rangeSize = 10;
                $scope.pages = [];
                var start;
                var ret = [];
                if ($scope.pagination.total < 10) {
                    rangeSize = $scope.pagination.total
                }
                start = $scope.currentPaginationPage > 5 ? $scope.currentPaginationPage - 5 : 0;
                if (start > $scope.pageCount() - rangeSize) {
                    start = $scope.pageCount() - rangeSize + 1;
                }

                for (var i = start; i < start + rangeSize; i++) {
                    if (i < 0) continue;
                    if (i >= $scope.pagination.last_page) continue;
                    ret.push(i);
                    $scope.pages.push(i);
                }
                return ret;
            };

            $scope.prevPage = function () {
                if ($scope.currentPaginationPage > 0) {
                    $scope.currentPaginationPage--;
                }
                $scope.paginationChanged()
            };

            $scope.prevPageDisabled = function () {
                return $scope.currentPaginationPage === 0 ? "disabled" : "";
            };

            $scope.pageCount = function () {
                return $scope.pagination.last_page - 1;
            };

            $scope.nextPage = function () {
                if ($scope.currentPaginationPage < $scope.pageCount()) {
                    $scope.currentPaginationPage++;
                }
                $scope.paginationChanged()
            };

            $scope.nextPageDisabled = function () {
                return $scope.currentPaginationPage === $scope.pageCount() ? "disabled" : "";
            };

            $scope.setPage = function (n) {
                if ($scope.pagination.current_page === n + 1) return;
                $scope.currentPaginationPage = n;
                $scope.paginationChanged()
            };
            $scope.paginationChanged = function () {
                $scope.fetchStocks();
            };

            $scope.fetchStocks = function () {
                $scope.loading = true;
                $scope.query.page = $scope.currentPaginationPage + 1;
                var queryRoute = $.param($scope.query);
                $http.get(moduleRoute + '?' + queryRoute).then(function (response) {
                    $scope.loading = false;
                    $scope.stocks = response.data.data;
                    $scope.pagination = response.data;
                    $scope.total = response.data.total;
                    $scope.range();
                });
            };
            $scope.fetchStocks();

            $scope.resetFilters = function () {
                $scope.query = {
                    ajax: true,
                    page: 0,
                    filter: '',
                    search: ''
                };
                $scope.searchStocks = '';
                $scope.filterd = false;
                $scope.fetchStocks();
            };

            $scope.checkPagination = function () {
                return $scope.total > $scope.pagination.per_page;
            };

        }]);
    </script>
@endsection