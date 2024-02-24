@extends('layouts.master')
@section('title', 'Damaged Stocks')
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
                    <div class="btn-group btn-block">
                        <button type="button" class="btn btn-block btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-arrow-left"></i> Return
                        </button>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                            @foreach(damagedStoreDropDown() as $keyStore => $store)
                                <a class="dropdown-item" href="{{ route('stock.return.create', $keyStore) }}">{{ $store }}</a>
                            @endforeach
                        </div>
                    </div>
                    <ul class="list-style-none">
                        <li class="text-muted m-t-20">FILTER STOCKS BY</li>
                        <li class="divider"></li>
                        <li ng-class="{'active': !query.filter}"
                            ng-click="filterUpdate()"><a
                                    href="">All Damaged Stocks</a></li>
                        <li ng-class="{'active': query.filter === 'recentlyCreated'}"
                            ng-click="filterUpdate('recentlyCreated')"><a href="">Recently Created</a></li>
                        <li ng-class="{'active': query.filter === 'recentlyUpdated'}"
                            ng-click="filterUpdate('recentlyUpdated')"><a href="">Recently Modified</a></li>
                    </ul>
                    <ul class="list-style-none">
                        <li class="m-t-10">Store</li>
                        <li>
                            <div class="ui fluid  search selection dropdown store-drop-down">
                                <i class="dropdown icon"></i>
                                <div class="default text">choose a store</div>
                                <div class="menu">
                                    @foreach(damagedStoreDropDown() as $key => $store)
                                        <div class="item" data-value="{{ $key }}">{{ $store }}</div>
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
                                <h2 class="card-title m-t-10">Damaged stocks @{{ total ? ("(" + total +")") :
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
                            <p>loading damaged stocks</p>
                        </div>
                    </div>
                    <div class="row" ng-hide="loading">
                        <div class="col-md-12 m-b-20">
                            <table id="demo-foo-addrow" class="table m-t-10 table-hover no-wrap contact-list" data-page-size="10">
                                <thead>
                                <tr>
                                    <th colspan="2">Product details</th>
                                    <th>Company</th>
                                    <th class="text-center">Min stock level</th>
                                    <th class="text-center">Available stock</th>
                                </tr>
                                </thead>
                                <tbody class="text-muted">
                                <tr ng-repeat="stock in stocks">
                                    <td style="width: 3%">
                                        <img src="@{{ getProductImage(stock) }}" alt="user" class="img-circle" />
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <a target="_blank" href="stock/@{{ stock.id }}">
                                            @{{ stock.product.name }}
                                        </a><br />
                                        <small>
                                            <b>Type</b>: @{{ stock.product.type }}<br/>
                                            <b>Available at:</b>  @{{stock.store.name}}
                                        </small>
                                    </td>
                                    <td>@{{ stock.company.name }}</td>
                                    <td class="text-center">@{{ stock.min_stock_level }}</td>
                                    <td class="text-center">@{{ stock.available_stock }}</td>
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
                    <div class="row" ng-hide="loading" ng-if="stocks.length === 0 && filterd">
                        <div class="col-md-12">
                            <div class="card text-center">
                                <div class="card-body">
                                    <p class="card-text">There are <code>no</code> damaged stocks found.</p>
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
            var moduleRoute = '{{ route('stock.damaged.index') }}';
            $scope.stocks = [];
            $scope.filterd = false;
            $scope.loading = true;
            $scope.pagination = {};
            $scope.currentPaginationPage = 0;
            $scope.query = {
                ajax: true,
                page: null,
                filter: null,
                storeId: null
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

            $scope.el = {
                storeDropDown: $('.store-drop-down')
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
                $scope.el.storeDropDown.dropdown('clear');
                $scope.query.storeId = null;
                $scope.filterd = false;
                $scope.fetchStocks();
            };

            $scope.el.storeDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    $scope.filterd = true;
                    $scope.query.storeId = val;
                    $scope.fetchStocks();
                }
            });

            $scope.getProductImage = function (stock) {
                var route = '{{ route('setting.product.image', ['product' => 'PRODUCT']) }}';
                return route.replace('PRODUCT', stock.product_id)
            };

            $scope.checkPagination = function () {
                return $scope.total > $scope.pagination.per_page;
            };

            $scope.getStockCount = function (stock) {
                if(stock.available_stock < stock.min_stock_level) return 'border-danger';
            }
        }]);
    </script>
@endsection