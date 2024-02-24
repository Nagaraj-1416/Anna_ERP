@extends('layouts.master')
@section('title', 'Main Stocks')
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
                    <a target="_blank" href="{{ route('stock.create') }}" class="btn btn-info btn-block">
                        <i class="fa fa-plus"></i> Add New Stock
                    </a>
                    <div class="btn-group btn-block">
                        <button type="button" class="btn btn-block btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-plus"></i> Transfer
                        </button>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                            @foreach(storeDropDown() as $keyStore => $store)
                                <a class="dropdown-item" href="{{ route('stock.transfer.create', $keyStore) }}">{{ $store }}</a>
                            @endforeach
                        </div>
                    </div>
                    <ul class="list-style-none">
                        <li class="text-muted m-t-20">FILTER STOCK BY</li>
                        <li class="divider"></li>
                        <li ng-class="{'active': !query.filter}"
                            ng-click="filterUpdate()"><a
                                    href="">All Stocks</a></li>
                        <li ng-class="{'active': query.filter === 'recentlyCreated'}"
                            ng-click="filterUpdate('recentlyCreated')"><a href="">Recently Created</a></li>
                        <li ng-class="{'active': query.filter === 'recentlyUpdated'}"
                            ng-click="filterUpdate('recentlyUpdated')"><a href="">Recently Modified</a></li>
                    </ul>
                    <ul class="list-style-none">
                        <li class="m-t-10">Type</li>
                        <li>
                            <div class="ui fluid  search selection dropdown type-drop-down">
                                <i class="dropdown icon"></i>
                                <div class="default text">choose a type</div>
                                <div class="menu">
                                    <div class="item" data-value="Raw Material">Raw Material</div>
                                    <div class="item" data-value="Finished Good">Finished Good</div>
                                    <div class="item" data-value="Third Party Product">Third Party Product</div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <ul class="list-style-none">
                        <li class="m-t-10">Category</li>
                        <li>
                            <div class="ui fluid  search selection dropdown category-drop-down">
                                <i class="dropdown icon"></i>
                                <div class="default text">choose a category</div>
                                <div class="menu"></div>
                            </div>
                        </li>
                    </ul>
                    <ul class="list-style-none">
                        <li class="m-t-10">Store</li>
                        <li>
                            <div class="ui fluid  search selection dropdown store-drop-down">
                                <i class="dropdown icon"></i>
                                <div class="default text">choose a store</div>
                                <div class="menu"></div>
                            </div>
                        </li>
                    </ul>
                    <hr>
                    <ul class="list-style-none">
                        <li class="text-muted" ng-click="resetFilters()">
                            <a class="text-primary" href="">Reset Filters</a>
                        </li>
                    </ul>
                    <hr>
                    <a target="_blank" href="{{ route('stock.out.create') }}" class="btn btn-danger btn-block">
                        <i class="fa fa-minus"></i> Stocks Out (Do not use)
                    </a>
                </div>
                <!-- /.left-aside-column-->
                <div class="right-aside custom-right-aside">
                    <div class="right-page-header">
                        <div class="d-flex m-b-10">
                            <div class="align-self-center">
                                <h2 class="card-title m-t-10">Stocks @{{ total ? ("(" + total +")") :
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
                                    <th colspan="2">Product details</th>
                                    <th>Company</th>
                                    <th class="text-center">Min stock level</th>
                                    <th class="text-center">Available stock</th>
                                    <th class="text-center">Category</th>
                                    <th class="text-center">Action</th>
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
                                    <td class="text-center">@{{ stock.category }}</td>
                                    <td class="text-center">
                                        <a target="_blank" class="p-10 btn btn-info btn-sm" data-tooltip="Update Min Stock Level" href="/stock-summary/stock/@{{ stock.id }}/edit">
                                            <i class="ti-pencil" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        {{--<div class="col-md-3 col-sm-6 col-xs-12"
                             ng-repeat="stock in stocks">
                            <div class="card card-body">
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <img src="@{{ getProductImage(stock) }}" alt="img" class="img-responsive">
                                    </div>
                                    <div class="col-md-9">
                                        <h3 class="box-title m-b-0">
                                            <a target="_blank" href="stock/@{{ stock.id }}">@{{ stock.product.name }}</a>
                                        </h3>
                                        <small>@{{ stock.product.code }} | @{{stock.store.name}}</small>
                                        <p class="text-muted">
                                            <small><b>Available:</b> @{{ stock.available_stock }} @{{ stock.product.measurement }}</small><br />
                                            <small><b>Reorder level:</b> @{{ stock.product.min_stock_level }} @{{ stock.product.measurement }}</small><br />
                                        </p>
                                        @{{ stock.type }} | @{{ stock.product.type }}
                                    </div>
                                </div>

                            </div>
                        </div>--}}
                        <!-- pagination panel -->
                        <div class="col-md-12" ng-show="checkPagination()">
                            @include('general.pagination.pagination')
                        </div>
                    </div>
                    <div class="row" ng-hide="loading" ng-if="stocks.length === 0 && !filterd">
                        <div class="col-md-12">
                            <div class="card text-center">
                                <div class="card-body">
                                    <p class="card-text">You haven't added any stocks yet, click on "Add New
                                        Stock" button to add stocks.</p>
                                    <a target="_blank" href="{{ route('stock.create') }}"
                                       class="btn btn-info">
                                        <i class="fa fa-plus"></i> Add New Stock
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
                                    <p class="card-text">There are <code>no</code> stocks found.</p>
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
            var moduleRoute = '{{ route('stock.index') }}';
            $scope.stocks = [];
            $scope.filterd = false;
            $scope.loading = true;
            $scope.pagination = {};
            $scope.currentPaginationPage = 0;
            $scope.query = {
                ajax: true,
                page: null,
                filter: null,
                search: null,
                typeId: null,
                categoryId: null,
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
                typeDropDown: $('.type-drop-down'),
                categoryDropDown: $('.category-drop-down'),
                storeDropDown: $('.store-drop-down')
            };
            $scope.urls = {
                stores: '{{ route('setting.store.search') }}',
                categories: '{{ route('setting.product.category.search') }}'
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
                $scope.el.typeDropDown.dropdown('clear');
                $scope.el.categoryDropDown.dropdown('clear');
                $scope.el.storeDropDown.dropdown('clear');
                $scope.query.typeId = null;
                $scope.query.categoryId = null;
                $scope.query.storeId = null;
                $scope.filterd = false;
                $scope.fetchStocks();
            };

            $scope.el.typeDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    $scope.filterd = true;
                    $scope.query.typeId = val;
                    $scope.fetchStocks();
                }
            });

            $scope.el.categoryDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: $scope.urls.categories + '/{query}',
                    cache: false
                },
                onChange: function (val) {
                    $scope.filterd = true;
                    $scope.query.categoryId = val;
                    $scope.fetchStocks();
                }
            });

            $scope.el.storeDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: $scope.urls.stores + '/{query}',
                    cache: false
                },
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