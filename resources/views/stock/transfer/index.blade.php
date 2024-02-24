@extends('layouts.master')
@section('title', 'Transfers')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Stock') !!}
@endsection
@section('content')
<div class="row" ng-controller="StockTransferController">
    <div class="col-12">
        <div class="card">
            <!-- .left-right-aside-column-->
            <div class="contact-page-aside">
                <!-- .left-aside-column-->
                <div class="left-aside">
                    {{--<a target="_blank" href="{{ route('stock.create') }}" class="btn btn-info btn-block">
                        <i class="fa fa-plus"></i> Transfer
                    </a>--}}
                    <div class="btn-group btn-block">
                        <button type="button" class="btn btn-block btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-plus"></i> Transfer
                        </button>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                            @foreach(storeDropDown() as $keyStore => $store)
                                <a class="dropdown-item" href="{{ route('stock.transfer.create', $keyStore) }}">{{ $store }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- /.left-aside-column-->
                <div class="right-aside custom-right-aside">
                    <div class="right-page-header">
                        <div class="d-flex m-b-10">
                            <div class="align-self-center">
                                <h2 class="card-title m-t-10">Transfers @{{ total ? ("(" + total +")") :
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
                            <p>loading stock transfers</p>
                        </div>
                    </div>
                    <div class="row" ng-hide="loading">
                        <div class="col-md-12 m-b-20">
                            <table id="demo-foo-addrow" class="table m-t-10 table-hover no-wrap contact-list" data-page-size="10">
                                <thead>
                                <tr>
                                    <th colspan="2">Product details</th>
                                    <th class="text-center">Company</th>
                                    <th class="text-center">Min stock level</th>
                                    <th class="text-center">Available stock</th>
                                    <th class="text-center">Stock type</th>
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
                                    <td class="text-center">@{{ stock.company.name }}</td>
                                    <td class="text-center">@{{ stock.min_stock_level }}</td>
                                    <td class="text-center">@{{ stock.available_stock }}</td>
                                    <td class="text-center">@{{ stock.type }}</td>
                                    <td class="text-center">
                                        <a target="_blank" class="p-10 btn btn-info btn-sm" data-tooltip="Update Min Stock Level" href="/stock-summary/stock/@{{ stock.id }}/edit">
                                            <i class="ti-pencil" aria-hidden="true"></i>
                                        </a>
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
                    <div class="row" ng-hide="loading" ng-if="tranfers.length === 0 && !filterd">
                        <div class="col-md-12">
                            <div class="card text-center">
                                <div class="card-body">
                                    <p class="card-text">You haven't added any stock transfers yet, click on "Transfer" button to transfer stocks.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- if there no credit available message -->
                    <div class="row" ng-hide="loading" ng-if="tranfers.length === 0 && filterd">
                        <div class="col-md-12">
                            <div class="card text-center">
                                <div class="card-body">
                                    <p class="card-text">There are <code>no</code> stock transfers found.</p>
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
        app.controller('StockTransferController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('stock.transfer.index') }}';
            $scope.tranfers = [];
            $scope.filterd = false;
            $scope.loading = true;
            $scope.pagination = {};
            $scope.currentPaginationPage = 0;
            $scope.query = {
                ajax: true,
                page: null
            };

            $scope.filterUpdated = function () {
                $scope.filterd = true;
                $scope.fetchTransfers();
            };

            $scope.filterUpdate = function (filter) {
                $scope.filterd = true;
                $scope.fetchTransfers();
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
                $scope.fetchTransfers();
            };

            $scope.fetchTransfers = function () {
                $scope.loading = true;
                $scope.query.page = $scope.currentPaginationPage + 1;
                var queryRoute = $.param($scope.query);
                $http.get(moduleRoute + '?' + queryRoute).then(function (response) {
                    $scope.loading = false;
                    $scope.transfers = response.data.data;
                    $scope.pagination = response.data;
                    $scope.total = response.data.total;
                    $scope.range();
                });
            };
            $scope.fetchTransfers();

            $scope.resetFilters = function () {
                $scope.query = {
                    ajax: true,
                    page: 0
                };
                $scope.filterd = false;
                $scope.fetchTransfers();
            };

            $scope.checkPagination = function () {
                return $scope.total > $scope.pagination.per_page;
            };

        }]);
    </script>
@endsection