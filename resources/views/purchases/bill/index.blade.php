@extends('layouts.master')
@section('title', 'Bills')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Purchase') !!}
@endsection
@section('content')
    <div class="row" ng-controller="BillController">
        <div class="col-12">
            <div class="card">
                <!-- .left-right-aside-column-->
                <div class="contact-page-aside">
                    <!-- .left-aside-column-->
                    <div class="left-aside">
                        @can('create', new \App\PurchaseOrder())
                            <a target="_blank" href="{{ route('purchase.order.create') }}"
                               class="btn btn-info btn-block">
                                <i class="fa fa-plus"></i> Add New Order
                            </a>
                        @endcan
                        <ul class="list-style-none">
                            <li class="text-muted m-t-20">FILTER BILLS BY</li>
                            <li class="divider"></li>
                            <li ng-class="{'active': !query.filter}" ng-click="filterUpdate()"><a
                                        href="">All Bills</a></li>
                            <li ng-class="{'active': query.filter === 'draft'}"
                                ng-click="filterUpdate('draft')"><a href="">Drafted Bills</a></li>
                            <li ng-class="{'active': query.filter === 'open'}"
                                ng-click="filterUpdate('open')"><a href="">Open Bills</a></li>
                            <li ng-class="{'active': query.filter === 'overdue'}"
                                ng-click="filterUpdate('overdue')"><a href="">Overdue Bills</a></li>
                            <li ng-class="{'active': query.filter === 'partiallyPaid'}"
                                ng-click="filterUpdate('partiallyPaid')"><a href="">Partially Paid Bills</a></li>
                            <li ng-class="{'active': query.filter === 'paid'}"
                                ng-click="filterUpdate('paid')"><a href="">Paid Bills</a></li>
                            <li ng-class="{'active': query.filter === 'canceled'}"
                                ng-click="filterUpdate('canceled')"><a href="">Canceled Bills</a></li>
                            <li ng-class="{'active': query.filter === 'recentlyCreated'}"
                                ng-click="filterUpdate('recentlyCreated')"><a href="">Recently Created</a></li>
                            <li ng-class="{'active': query.filter === 'recentlyModified'}"
                                ng-click="filterUpdate('recentlyModified')"><a href="">Recently Modified</a></li>
                        </ul>
                        <ul class="list-style-none">
                            <li class="divider"></li>
                            <li class="m-t-10">Supplier</li>
                            <li>
                                <div class="ui fluid  search selection dropdown supplier-drop-down">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a supplier</div>
                                    <div class="menu"></div>
                                </div>
                            </li>
                        </ul>
                        <ul class="list-style-none">
                            <li class="m-t-10">Prepared by</li>
                            <li>
                                <div class="ui fluid  search selection dropdown user-drop-down">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">prepared by</div>
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
                    </div>
                    <!-- /.left-aside-column-->
                    <div class="right-aside custom-right-aside">
                        <div class="right-page-header">
                            <div class="d-flex m-b-10">
                                <div class="align-self-center">
                                    <h2 class="card-title m-t-10">Bills
                                        @{{ pagination.total ? ("(" + pagination.total +")") : '' }}</h2>
                                </div>
                                <div class="ml-auto">
                                    <input type="text" id="demo-input-search2" ng-model="searchBills"
                                           placeholder="search for bills here" class="form-control"
                                           ng-change="filterUpdated()">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-inverse card-warning cursor pointer"
                                     ng-click="filterUpdate('draft')">
                                    <div class="box bg-warning text-center">
                                        <h1 class="font-light text-white">@{{ draftedBillCount }}</h1>
                                        <h6 class="text-white">Drafted</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-primary card-inverse cursor pointer"
                                     ng-click="filterUpdate('open')">
                                    <div class="box text-center">
                                        <h1 class="font-light text-white">@{{ openBillCount }}</h1>
                                        <h6 class="text-white">Open</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-inverse card-success cursor pointer"
                                     ng-click="filterUpdate('overdue')">
                                    <div class="box text-center">
                                        <h1 class="font-light text-white">@{{ overdueBillCount }}</h1>
                                        <h6 class="text-white">Overdue</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-inverse card-info cursor pointer"
                                     ng-click="filterUpdate('partiallyPaid')">
                                    <div class="box text-center">
                                        <h1 class="font-light text-white">@{{ partPaidBillCount }}</h1>
                                        <h6 class="text-white">Partially Paid</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-inverse card-dark cursor pointer"
                                     ng-click="filterUpdate('paid')">
                                    <div class="box text-center">
                                        <h1 class="font-light text-white">@{{ paidBillCount }}</h1>
                                        <h6 class="text-white">Paid</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-inverse card-danger cursor pointer"
                                     ng-click="filterUpdate('canceled')">
                                    <div class="box bg-danger text-center">
                                        <h1 class="font-light text-white">@{{ canceledBillCount }}</h1>
                                        <h6 class="text-white">Canceled</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
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
                                <p>loading bills</p>
                            </div>
                        </div>
                        <div class="row" ng-hide="loading">
                            <div class="col-md-3 col-sm-6 col-xs-12"
                                 ng-repeat="(key, bill) in bills">
                                <div class="card card-body">
                                    <div class="row">
                                        <div class="col-md-3 text-center">
                                            <img src="@{{ getSupLogo(bill) }}" alt="img" class="img-responsive">
                                        </div>
                                        <div class="col-md-9">
                                            <h3 class="box-title m-b-0">
                                                <a target="_blank" href="bill/@{{ bill.id }}">@{{ bill.bill_no }}</a>
                                            </h3>
                                            <small>@{{ bill.supplier.display_name }} | @{{bill.supplier.mobile}}</small>
                                            <p class="text-muted">
                                                <small><b>Order No:</b> @{{ bill.order.po_no }}</small>
                                                <br/>
                                                <small><b>Order Status:</b> @{{ bill.order.status }}</small>
                                                <br/>
                                                <small><b>Bill Status:</b> @{{ bill.status }}</small>
                                                <br/>
                                                <small><b>Due Date:</b> @{{ bill.due_date }}</small>
                                            </p>
                                            @{{ bill.bill_date }} | @{{ bill.amount | number }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- pagination panel -->
                            <div class="col-md-12" ng-show="bills.length">
                                @include('general.pagination.pagination')
                            </div>
                        </div>
                        <div class="row" ng-hide="loading" ng-if="!filterd && bills.length === 0">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">You haven't added any bills yet, visit purchase orders to
                                            generate bills.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- if there no order available message -->
                        <div class="row" ng-hide="loading"
                             ng-if="filterd && bills.length === 0">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">There are <code>no</code> bills found.</p>
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
        app.controller('BillController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('purchase.bill.index') }}';
            $scope.bills = [];
            $scope.loading = true;
            $scope.filterd = false;
            $scope.pagination = {};
            $scope.currentPaginationPage = 0;
            $scope.query = {
                ajax: true,
                page: null,
                filter: null,
                search: null,
                userId: null,
                supplierId: null,
                overdue: '{{ $overDue }}'
            };
            $scope.userId = '';
            $scope.el = {
                userDropDown: $('.user-drop-down'),
                supplierDropDown: $('.supplier-drop-down'),
            };
            $scope.urls = {
                user: '{{ route('setting.user.search') }}',
                supplier: '{{ route('purchase.supplier.search') }}',
            };
            $scope.filterUpdated = function () {
                $scope.filterd = true;
                $scope.query.search = $scope.searchBills;
                $scope.fetchBills();
            };

            $scope.filterUpdate = function (filter) {
                $scope.query.filter = filter ? filter : '';
                $scope.filterd = true;
                $scope.fetchBills();
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
                $scope.fetchBills();
            };

            $scope.fetchBills = function () {
                $scope.loading = true;
                $scope.query.page = $scope.currentPaginationPage + 1;
                var routeParam = $.param($scope.query);
                $http.get(moduleRoute + '?' + routeParam).then(function (response) {
                    $scope.loading = false;
                    $scope.bills = response.data.data;
                    $scope.pagination = response.data;
                    $scope.range();
                });
            };

            $scope.fetchBills();
            $scope.resetFilters = function () {
                $scope.filterd = false;
                $scope.el.userDropDown.dropdown('clear');
                $scope.el.supplierDropDown.dropdown('clear');
                $scope.query = {
                    ajax: true,
                    page: 0,
                    filter: null,
                    search: null,
                    userId: null,
                    supplierId: null,
                };
                $scope.searchBills = '';
                $scope.fetchBills();
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
                    if (val) $scope.fetchBills();
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
                    if (val) $scope.fetchBills();
                }
            });
            /** get drafted bill count */
            var draftedBillRoute = '{{ route('purchase.summary.index',
            ['model' => 'Bill', 'take' => 'null', 'with' => 'null', 'where' => 'Draft', 'field' => 'status']) }}';
            $http.get(draftedBillRoute + '?ajax=true').then(function (response) {
                $scope.draftedBillCount = response.data ? response.data.count : 0;
            });

            /** get open bill count */
            var openBillRoute = '{{ route('purchase.summary.index',
            ['model' => 'Bill', 'take' => 'null', 'with' => 'null', 'where' => 'Open', 'field' => 'status']) }}';
            $http.get(openBillRoute + '?ajax=true').then(function (response) {
                $scope.openBillCount = response.data ? response.data.count : 0;
            });

            /** get overdue bill count */
            var overdueBillRoute = '{{ route('purchase.summary.index',
            ['model' => 'Bill', 'take' => 'null', 'with' => 'null', 'where' => 'Overdue', 'field' => 'status']) }}';
            $http.get(overdueBillRoute + '?ajax=true').then(function (response) {
                $scope.overdueBillCount = response.data ? response.data.count : 0;
            });

            /** get partially paid bill count */
            var partPaidBillRoute = '{{ route('purchase.summary.index',
            ['model' => 'Bill', 'take' => 'null', 'with' => 'null', 'where' => 'Partially Paid', 'field' => 'status']) }}';
            $http.get(partPaidBillRoute + '?ajax=true').then(function (response) {
                $scope.partPaidBillCount = response.data ? response.data.count : 0;
            });

            /** get paid bill count */
            var paidBillRoute = '{{ route('purchase.summary.index',
            ['model' => 'Bill', 'take' => 'null', 'with' => 'null', 'where' => 'Paid', 'field' => 'status']) }}';
            $http.get(paidBillRoute + '?ajax=true').then(function (response) {
                $scope.paidBillCount = response.data ? response.data.count : 0;
            });

            /** get canceled bill count */
            var canceledBillRoute = '{{ route('purchase.summary.index',
            ['model' => 'Bill', 'take' => 'null', 'with' => 'null', 'where' => 'Canceled', 'field' => 'status']) }}';
            $http.get(canceledBillRoute + '?ajax=true').then(function (response) {
                $scope.canceledBillCount = response.data ? response.data.count : 0;
            });

            $scope.getSupLogo = function (bill) {
                var route = '{{ route('purchase.supplier.logo', ['supplier' => 'SUPPLIER']) }}';
                return route.replace('SUPPLIER', bill.supplier_id)
            };
        }]);
    </script>
@endsection