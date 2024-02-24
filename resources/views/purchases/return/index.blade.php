@extends('layouts.master')
@section('title', 'Purchase Returns')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Purchase') !!}
@endsection
@section('content')
    <div class="row" ng-controller="ReturnController">
        <div class="col-12">
            <div class="card">
                <!-- .left-right-aside-column-->
                <div class="contact-page-aside">
                    <!-- .left-aside-column-->
                    <div class="left-aside">
                        <ul class="list-style-none">
                            <li class="text-muted m-t-20">FILTER CREDIT BY</li>
                            <li class="divider"></li>
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
                                    <h2 class="card-title m-t-10">Returns
                                        @{{ pagination.total ? ("(" + pagination.total +")") :
                                        '' }}</h2>
                                </div>
                                <div class="ml-auto">
                                    <input type="text" id="demo-input-search2" ng-model="searchOrders"
                                           placeholder="search for return here" class="form-control"
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
                                <p>loading purchase returns</p>
                            </div>
                        </div>
                        <div class="row" ng-hide="loading">
                            <div class="col-md-12 m-b-20">
                                <table id="demo-foo-addrow" class="table m-t-10 table-hover no-wrap contact-list"
                                       data-page-size="10">
                                    <thead>
                                    <tr>
                                        <th>Return details</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-muted">
                                    <tr ng-repeat="return in returns">
                                        <td>
                                            <a target="_blank" href="/purchase/return/@{{ return.id }}">
                                                @{{ return.date | date }}
                                            </a><br/>
                                            <small>
                                                @{{ return.company.name }}<br />
                                                @{{ return.notes }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <a title="View order details" class="p-10"
                                               href="/purchase/return/@{{ return.id }}">
                                                <i class="ti-eye" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- pagination panel -->
                            <div class="col-md-12" ng-show="orders.length">
                                @include('general.pagination.pagination')
                            </div>
                        </div>
                        <!-- if there no order available message -->
                        <div class="row" ng-hide="loading"
                             ng-if="filterd  && orders.length === 0">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">There are <code>no</code> returns found.</p>
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
        app.controller('ReturnController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('purchase.return.index') }}';
            $scope.returns = [];
            $scope.loading = true;
            $scope.filterd = false;
            $scope.pagination = {};
            $scope.currentPaginationPage = 0;
            $scope.userId = '';
            $scope.query = {
                ajax: true,
                page: null,
                filter: null,
                search: null,
                userId: null,
                supplierId: null,
                productId: null,
            };
            $scope.el = {
                userDropDown: $('.user-drop-down'),
                supplierDropDown: $('.supplier-drop-down'),
                productDropDown: $('.product-drop-down'),
            };
            $scope.urls = {
                user: '{{ route('setting.user.search') }}',
                supplier: '{{ route('purchase.supplier.search') }}',
                product: '{{ route('setting.product.search', ['type' => 'Raw Material']) }}',
            };

            $scope.filterUpdated = function () {
                $scope.filterd = true;
                $scope.query.search = $scope.searchOrders;
                $scope.fetchOrders();
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
                $scope.fetchOrders();
            };

            $scope.filterUpdate = function (filter) {
                $scope.query.filter = filter ? filter : '';
                $scope.filterd = true;
                $scope.fetchOrders();
            };

            $scope.fetchOrders = function () {
                $scope.loading = true;
                $scope.query.page = $scope.currentPaginationPage + 1;
                var routeParam = $.param($scope.query);
                $http.get(moduleRoute + '?' + routeParam).then(function (response) {
                    $scope.loading = false;
                    $scope.returns = response.data.data;
                    $scope.pagination = response.data;
                    $scope.range();
                });
            };
            $scope.fetchOrders();

            $scope.resetFilters = function () {
                $scope.el.userDropDown.dropdown('clear');
                $scope.query.userId = null;
                $scope.el.supplierDropDown.dropdown('clear');
                $scope.query.supplierId = null;
                $scope.el.productDropDown.dropdown('clear');
                $scope.query.productId = null;
                $scope.filterd = false;
                $scope.query = {
                    ajax: true,
                    page: 0,
                    filter: '',
                    search: '',
                    userId: null,
                    supplierId: null,
                    productId: null,
                };

                $scope.searchOrders = '';
                $scope.fetchOrders();
            };

            $scope.el.userDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: $scope.urls.user + '/{query}',
                    cache: false
                },
                onChange: function (val) {
                    $scope.filterd = true;
                    $scope.query.userId = val;
                    if (val) $scope.fetchOrders();
                }
            });


            $scope.el.supplierDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: $scope.urls.supplier + '/{query}',
                    cache: false
                },
                onChange: function (val) {
                    $scope.filterd = true;
                    $scope.query.supplierId = val;
                    if (val) $scope.fetchOrders();
                }
            });

            $scope.el.productDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: $scope.urls.product + '/{query}',
                    cache: false
                },
                onChange: function (val) {
                    $scope.filterd = true;
                    $scope.query.productId = val;
                    if (val) $scope.fetchOrders();
                }
            });


            /** get scheduled PO count */
            var scheduledPoRoute = '{{ route('purchase.summary.order', ['status' => 'Scheduled']) }}';
            $http.get(scheduledPoRoute + '?ajax=true').then(function (response) {
                $scope.scheduledPo = response.data ? response.data.count : 0;
            });

            /** get drafted PO count */
            var draftedPoRoute = '{{ route('purchase.summary.order', ['status' => 'Draft']) }}';
            $http.get(draftedPoRoute + '?ajax=true').then(function (response) {
                $scope.draftedPo = response.data ? response.data.count : 0;
            });

            /** get awaiting approval PO count */
            var ntApprovedPoRoute = '{{ route('purchase.summary.order', ['status' => 'Awaiting Approval']) }}';
            $http.get(ntApprovedPoRoute + '?ajax=true').then(function (response) {
                $scope.ntApprovedPo = response.data ? response.data.count : 0;
            });

            /** get open PO count */
            var openPoRoute = '{{ route('purchase.summary.order', ['status' => 'Open']) }}';
            $http.get(openPoRoute + '?ajax=true').then(function (response) {
                $scope.openPo = response.data ? response.data.count : 0;
            });

            /** get closed PO count */
            var closedPoRoute = '{{ route('purchase.summary.order', ['status' => 'Closed']) }}';
            $http.get(closedPoRoute + '?ajax=true').then(function (response) {
                $scope.closedPo = response.data ? response.data.count : 0;
            });

            /** get canceled PO count */
            var canceledPoRoute = '{{ route('purchase.summary.order', ['status' => 'Canceled']) }}';
            $http.get(canceledPoRoute + '?ajax=true').then(function (response) {
                $scope.canceledPo = response.data ? response.data.count : 0;
            });

            $scope.getSupLogo = function (order) {
                var route = '{{ route('purchase.supplier.logo', ['supplier' => 'SUPPLIER']) }}';
                return route.replace('SUPPLIER', order.supplier_id)
            };
        }]);
    </script>
@endsection