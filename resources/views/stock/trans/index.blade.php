@extends('layouts.master')
@section('title', 'Stock Transaction')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Stock') !!}
@endsection
@section('content')
<div class="row" ng-controller="StockController">
    <div class="col-12">
        <div class="card">
            <!-- .left-right-aside-column-->
            <div class="contact-page-aside">
                <!-- .left-aside-column-->
                <div class="left-aside">
                    <ul class="list-style-none">
                        <li class="text-muted m-t-20">FILTER TRANS BY</li>
                        <li class="divider"></li>
                        <li ng-class="{'active': !query.filter}"
                            ng-click="filterUpdate()"><a
                                    href="">All Transactions</a></li>
                        <li ng-class="{'active': query.filter === 'recentlyCreated'}"
                            ng-click="filterUpdate('recentlyCreated')"><a href="">Recently Created</a></li>
                        <li ng-class="{'active': query.filter === 'recentlyUpdated'}"
                            ng-click="filterUpdate('recentlyUpdated')"><a href="">Recently Modified</a></li>
                    </ul>
                    <ul class="list-style-none">
                        <li class="divider"></li>
                        <li class="m-t-10">Stock item</li>
                        <li>
                            <div class="ui fluid  search selection dropdown stock-drop-down">
                                <i class="dropdown icon"></i>
                                <div class="default text">choose an item</div>
                                <div class="menu">
                                    @foreach(productDropDown() as $key => $stock)
                                        <div class="item" data-value="{{ $key }}">{{ $stock }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </li>
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
                                <h2 class="card-title m-t-10">Transactions @{{ total ? ("(" + total +")") :
                                    '' }}</h2>
                            </div>
                            <div class="ml-auto"></div>
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
                            <p>loading transactions</p>
                        </div>
                    </div>
                    <div class="row" ng-hide="loading">
                        <div class="col-md-12 m-b-20">
                            <table id="demo-foo-addrow" class="table m-t-10 table-hover no-wrap contact-list" data-page-size="10">
                                <thead>
                                <tr>
                                    <th>Stock details</th>
                                    <th class="text-left">Transaction date</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Transaction</th>
                                    <th class="text-left">Description</th>
                                    <th class="text-left">Store</th>
                                </tr>
                                </thead>
                                <tbody class="text-muted">
                                <tr ng-repeat="tran in trans">
                                    <td style="vertical-align: middle;">
                                        <a target="_blank" href="stock/@{{ tran.stock.id }}">
                                            @{{ tran.stock.product.name }}
                                        </a>
                                    </td>
                                    <td class="text-left">@{{ tran.trans_date }}</td>
                                    <td class="text-center">@{{ tran.quantity }}</td>
                                    <td class="text-center">@{{ tran.transaction }}</td>
                                    <td class="text-left">@{{ tran.trans_description }}</td>
                                    <td class="text-left">@{{ tran.stock.store.name }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- pagination panel -->
                        <div class="col-md-12" ng-show="checkPagination()">
                            @include('general.pagination.pagination')
                        </div>
                    </div>

                    <!-- if there no credit available message -->
                    <div class="row" ng-hide="loading" ng-if="trans.length === 0 && filterd">
                        <div class="col-md-12">
                            <div class="card text-center">
                                <div class="card-body">
                                    <p class="card-text">There are <code>no</code> transactions found.</p>
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
        app.controller('StockController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('stock.trans.index') }}';
            $scope.trans = [];
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
                $scope.query.search = $scope.searchTrans;
                $scope.fetchTrans();
            };

            $scope.filterUpdate = function (filter) {
                $scope.query.filter = filter ? filter : '';
                $scope.filterd = true;
                $scope.fetchTrans();
            };

            $scope.el = {
                stockDropDown: $('.stock-drop-down')
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
                $scope.fetchTrans();
            };

            $scope.fetchTrans = function () {
                $scope.loading = true;
                $scope.query.page = $scope.currentPaginationPage + 1;
                var queryRoute = $.param($scope.query);
                $http.get(moduleRoute + '?' + queryRoute).then(function (response) {
                    $scope.loading = false;
                    $scope.trans = response.data.data;
                    $scope.pagination = response.data;
                    $scope.total = response.data.total;
                    $scope.range();
                });
            };
            $scope.fetchTrans();

            $scope.resetFilters = function () {
                $scope.query = {
                    ajax: true,
                    page: 0,
                    filter: '',
                    search: ''
                };
                $scope.searchTrans = '';
                $scope.filterd = false;
                $scope.el.stockDropDown.dropdown('clear');
                $scope.fetchTrans();
            };

            $scope.el.stockDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    if (!val) return;
                    $scope.filterd = true;
                    $scope.query.productId = val;
                    $scope.fetchTrans();
                }
            });

            $scope.checkPagination = function () {
                return $scope.total > $scope.pagination.per_page;
            };

        }]);
    </script>
@endsection