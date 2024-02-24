@extends('layouts.master')
@section('title', 'Orders')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row" ng-controller="SalesOrderController">
        <div class="col-12">
            <div class="card">
                <!-- .left-right-aside-column-->
                <div class="contact-page-aside">
                    <!-- .left-aside-column-->
                    <div class="left-aside">
                        @can('create', new \App\SalesOrder())
                            <a target="_blank" href="{{ route('sales.order.create') }}" class="btn btn-info btn-block">
                                <i class="fa fa-plus"></i> Add New Order
                            </a>
                        @endcan
                        <ul class="list-style-none">
                            <li class="text-muted m-t-20">FILTER ORDERS BY</li>
                            <li class="divider"></li>
                            <li ng-class="{'active': query.filter === 'today'}"
                                ng-click="filterUpdate('today')"><a href="" class="text-purple"><b>Today's Orders</b></a></li>
                            <li ng-class="{'active': !query.filter}"
                                ng-click="filterUpdate()"><a
                                        href="">All Orders</a></li>
                            <li ng-class="{'active': query.filter === 'scheduled'}"
                                ng-click="filterUpdate('scheduled')"><a href="">Scheduled Orders</a></li>
                            <li ng-class="{'active': query.filter === 'drafted'}"
                                ng-click="filterUpdate('drafted')"><a href="">Drafted Orders</a></li>
                            <li ng-class="{'active': query.filter === 'approvalPending'}"
                                ng-click="filterUpdate('approvalPending')"><a href="">Approval Pending</a></li>
                            <li ng-class="{'active': query.filter === 'open'}"
                                ng-click="filterUpdate('open')"><a href="">Open Orders</a></li>
                            <li ng-class="{'active': query.filter === 'overdue'}"
                                ng-click="filterUpdate('overdue')"><a href="">Overdue</a></li>
                            <li ng-class="{'active': query.filter === 'partiallyInvoiced'}"
                                ng-click="filterUpdate('partiallyInvoiced')"><a href="">Partially Invoiced</a></li>
                            <li ng-class="{'active': query.filter === 'fullyInvoiced'}"
                                ng-click="filterUpdate('fullyInvoiced')"><a href="">Fully Invoiced</a></li>
                            <li ng-class="{'active': query.filter === 'closed'}"
                                ng-click="filterUpdate('closed')"><a href="">Closed Orders</a></li>
                            <li ng-class="{'active': query.filter === 'Canceled'}"
                                ng-click="filterUpdate('Canceled')"><a href="">Canceled Orders</a></li>
                            <li ng-class="{'active': query.filter === 'recentlyCreated'}"
                                ng-click="filterUpdate('recentlyCreated')"><a href="">Recently Created</a></li>
                            <li ng-class="{'active': query.filter === 'recentlyUpdated'}"
                                ng-click="filterUpdate('recentlyUpdated')"><a href="">Recently Modified</a></li>
                            <li class="divider"></li>
                            <li ng-class="{'active': query.filter === 'opening'}"
                                ng-click="filterUpdate('opening')"><a href="" class="text-danger"><b>Opening Orders</b></a></li>
                        </ul>
                        <ul class="list-style-none">
                            <li class="divider"></li>
                            @include('general.date-range.index')
                            <li class="m-t-10">Customer</li>
                            <li>
                                <div class="ui fluid  search selection dropdown customer-drop-down">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a customer</div>
                                    <div class="menu">
                                        @foreach(customerDropDown() as $key => $customer)
                                            <div class="item" data-value="{{ $key }}">{{ $customer }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul class="list-style-none">
                            <li class="m-t-10">Product</li>
                            <li>
                                <div class="ui fluid  search selection dropdown product-drop-down">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a product</div>
                                    <div class="menu"></div>
                                </div>
                            </li>
                        </ul>
                        <ul class="list-style-none">
                            <li class="m-t-10">Rep</li>
                            <li>
                                <div class="ui fluid  search selection dropdown rep-drop-down">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a rep</div>
                                    <div class="menu">
                                        @foreach(repDropDown() as $key => $rep)
                                            <div class="item" data-value="{{ $key }}">{{ $rep }}</div>
                                        @endforeach
                                    </div>
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
                                    <h2 class="card-title m-t-10">Orders @{{ total ? ("(" + total +")") :
                                        '' }}</h2>
                                </div>
                                <div class="ml-auto">
                                    <input type="text" id="demo-input-search2" ng-model="searchOrders"
                                           placeholder="search for orders here" class="form-control"
                                           ng-change="filterUpdated()">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-inverse card-warning cursor pointer"
                                     ng-click="filterUpdate('scheduled')">
                                    <div class="box bg-warning text-center">
                                        <h1 class="font-light text-white">@{{ scheduledOrderCount }}</h1>
                                        <h6 class="text-white">Scheduled</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-primary card-inverse cursor pointer"
                                     ng-click="filterUpdate('drafted')">
                                    <div class="box text-center">
                                        <h1 class="font-light text-white">@{{ draftOrderCount }}</h1>
                                        <h6 class="text-white">Drafted</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2 ">
                                <div class="card card-inverse card-success cursor pointer"
                                     ng-click="filterUpdate('approvalPending')">
                                    <div class="box text-center">
                                        <h1 class="font-light text-white">@{{ ntApprovedOrderCount }}</h1>
                                        <h6 class="text-white">Awaiting Approval</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2 ">
                                <div class="card card-inverse card-info cursor pointer"
                                     ng-click="filterUpdate('open')">
                                    <div class="box text-center">
                                        <h1 class="font-light text-white">@{{ openOrderCount }}</h1>
                                        <h6 class="text-white">Open</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2 ">
                                <div class="card card-inverse card-dark cursor pointer"
                                     ng-click="filterUpdate('closed')">
                                    <div class="box text-center">
                                        <h1 class="font-light text-white">@{{ closedOrderCount }}</h1>
                                        <h6 class="text-white">Closed</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2 ">
                                <div class="card card-inverse card-danger cursor pointer"
                                     ng-click="filterUpdate('Canceled')">
                                    <div class="box bg-danger text-center">
                                        <h1 class="font-light text-white">@{{ canceledOrderCount }}</h1>
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
                                <p>loading sales orders</p>
                            </div>
                        </div>
                        <div class="row" ng-hide="loading">
                            <div class="col-md-12 m-b-20">
                                <table id="demo-foo-addrow" class="table m-t-10 table-hover no-wrap contact-list"
                                       data-page-size="10">
                                    <thead>
                                    <tr>
                                        <th colspan="2">Order details</th>
                                        <th>Customer</th>
                                        <th>Order status</th>
                                        <th class="text-center">Cash / Credit</th>
                                        <th class="text-center">Printed?</th>
                                        <th class="text-right">Amount</th>
                                        <th class="text-right">Received</th>
                                        <th class="text-right">Balance</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-muted">
                                    <tr ng-repeat="order in orders">
                                        <td style="width: 3%;">
                                            <img src="@{{ getCusLogo(order) }}" alt="user" class="img-circle"/>
                                        </td>
                                        <td>
                                            <a target="_blank" href="/sales/order/@{{ order.id }}">
                                                @{{ order.ref }}
                                            </a><br/>
                                            <small>
                                                <i class="mdi mdi-calendar"></i> @{{ order.created_at | date }}
                                            </small>
                                        </td>
                                        <td>
                                            <a target="_blank" href="/sales/customer/@{{ order.customer.id }}">
                                                @{{ order.customer.display_name }}
                                            </a>
                                        </td>
                                        <td>
                                            <span ng-class="order.status == 'Closed' ? 'text-green' : '' ||
                                                order.status == 'Open' ? 'text-warning' : '' || order.status == 'Canceled' ? 'text-danger' : ''">
                                                <i ng-if="order.status == 'Closed'"
                                                   class="ti-check"></i>
                                                <i ng-if="order.status == 'Open'"
                                                   class="ti-truck"></i>
                                                @{{ order.status }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-green" ng-if="order.is_credit_sales == 'No'">Cash</span>
                                            <span class="text-danger" ng-if="order.is_credit_sales == 'Yes'">Credit</span>
                                        </td>
                                        <td class="text-center">@{{ order.is_order_printed }}</td>

                                        <td class="text-right">@{{ order.total | number:2 }}</td>
                                        <td class="text-right">@{{ order.payment_received | number:2 }}</td>
                                        <td class="text-right">@{{ order.payment_remaining | number:2 }}</td>
                                        <td class="text-center">
                                            <a title="View order details" class="p-10"
                                               href="/sales/order/@{{ order.id }}">
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
                        <div class="row" ng-hide="loading" ng-if="orders.length === 0 && !filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">You haven't added any orders yet, click below button to
                                            add.</p>
                                        <a target="_blank" href="{{ route('sales.order.create') }}"
                                           class="btn btn-info">
                                            <i class="fa fa-plus"></i> Add New Order
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- if there no order available message -->
                        <div class="row" ng-hide="loading" ng-if="orders.length === 0 && filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">There are <code>no</code> orders found.</p>
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
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection
@section('script')
    @include('general.date-range.script')
    <script>
        app.controller('SalesOrderController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('sales.order.index') }}';
            $scope.orders = [];
            $scope.filterd = true;
            $scope.loading = true;
            $scope.pagination = {};
            $scope.currentPaginationPage = 0;
            $scope.query = {
                ajax: true,
                page: null,
                filter: 'today',
                search: null,
                customerId: null,
                productId: null,
                salesRepId: null,
                userId: null,
                dateRange: null,
                from_date: '{{ carbon()->toDateString() }}',
                to_date: '{{ carbon()->toDateString() }}',
            };
            $scope.filterUpdated = function () {
                $scope.filterd = true;
                $scope.query.search = $scope.searchOrders;
                $scope.fetchOrders();
            };

            $scope.filterUpdate = function (filter) {
                $scope.query.filter = filter ? filter : '';
                $scope.filterd = true;
                $scope.fetchOrders();
            };

            $scope.el = {
                repDropDown: $('.rep-drop-down'),
                userDropDown: $('.user-drop-down'),
                customerDropDown: $('.customer-drop-down'),
                productsDropDown: $('.product-drop-down')
            };
            $scope.urls = {
                rep: '{{ route('setting.rep.search') }}',
                user: '{{ route('setting.user.search') }}',
                customer: '{{ route('sales.customer.search') }}',
                product: '{{ route('setting.product.search', ['type' => 'Finished Good']) }}'
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

            $scope.fetchOrders = function () {
                $scope.loading = true;
                $scope.query.page = $scope.currentPaginationPage + 1;
                var queryRoute = $.param($scope.query);
                $http.get(moduleRoute + '?' + queryRoute).then(function (response) {
                    $scope.loading = false;
                    $scope.orders = response.data.data;
                    $scope.pagination = response.data;
                    $scope.total = response.data.total;
                    $scope.range();
                });
            };
            $scope.fetchOrders();

            $scope.resetFilters = function () {
                $scope.query.salesRepId = null;
                $scope.query.customerId = null;
                $scope.query.productId = null;
                $scope.query.userId = null;
                $scope.filterd = true;
                $scope.query.dateRange = null;
                $scope.query.from_date = '{{ carbon()->toDateString() }}';
                $scope.query.to_date = '{{ carbon()->toDateString() }}';
                dateRangeDropDown($scope);
                $scope.el.repDropDown.dropdown('clear');
                $scope.el.userDropDown.dropdown('clear');
                $scope.el.productsDropDown.dropdown('clear');
                $scope.el.customerDropDown.dropdown('clear');
                $scope.fetchOrders();
            };

            $scope.el.repDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    if (!val) return;
                    $scope.filterd = true;
                    $scope.query.salesRepId = val;
                    $scope.fetchOrders();
                }
            });

            $scope.el.userDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: $scope.urls.user + '/{query}',
                    cache: false
                },
                onChange: function (val) {
                    if (!val) return;
                    $scope.filterd = true;
                    $scope.query.userId = val;
                    $scope.fetchOrders();
                }
            });

            $scope.el.customerDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    if (!val) return;
                    $scope.filterd = true;
                    $scope.query.customerId = val;
                    $scope.fetchOrders();
                }
            });

            $scope.el.productsDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: $scope.urls.product + '/{query}',
                    cache: false
                },
                onChange: function (val) {
                    if (!val) return;
                    $scope.filterd = true;
                    $scope.query.productId = val;
                    $scope.fetchOrders();
                }
            });

            $scope.handleDateRangeChange = function (val) {
                if (val) {
                    $scope.query.dateRange = true;
                    $scope.query.filter = '';
                    $scope.fetchOrders();
                }
            };
            dateRangeDropDown($scope);
            /** get scheduled sales order count */
            var scheduledOrderRoute = '{{ route('sales.summary.index', [
                'model' => 'SalesOrder', 'take' => 'null', 'with' => 'null', 'where' => 'Scheduled', 'field' => 'status']) }}';
            $http.get(scheduledOrderRoute + '?ajax=true').then(function (response) {
                $scope.scheduledOrderCount = response.data ? response.data.count : 0;
            });

            /** get drafted sales order count */
            var draftOrderRoute = '{{ route('sales.summary.index', [
                'model' => 'SalesOrder', 'take' => 'null', 'with' => 'null', 'where' => 'Draft', 'field' => 'status']) }}';
            $http.get(draftOrderRoute + '?ajax=true').then(function (response) {
                $scope.draftOrderCount = response.data ? response.data.count : 0;
            });

            /** get awaiting for approval sales order count */
            var ntApprovedOrderRoute = '{{ route('sales.summary.index', [
                'model' => 'SalesOrder', 'take' => 'null', 'with' => 'null', 'where' => 'Awaiting Approval', 'field' => 'status']) }}';
            $http.get(ntApprovedOrderRoute + '?ajax=true').then(function (response) {
                $scope.ntApprovedOrderCount = response.data ? response.data.count : 0;
            });

            /** get open sales order count */
            var openOrderRoute = '{{ route('sales.summary.index', [
                'model' => 'SalesOrder', 'take' => 'null', 'with' => 'null', 'where' => 'Open', 'field' => 'status']) }}';
            $http.get(openOrderRoute + '?ajax=true').then(function (response) {
                $scope.openOrderCount = response.data ? response.data.count : 0;
            });

            /** get closed sales order count */
            var closedOrderRoute = '{{ route('sales.summary.index', [
                'model' => 'SalesOrder', 'take' => 'null', 'with' => 'null', 'where' => 'Closed', 'field' => 'status']) }}';
            $http.get(closedOrderRoute + '?ajax=true').then(function (response) {
                $scope.closedOrderCount = response.data ? response.data.count : 0;
            });

            /** get canceled sales order count */
            var canceledOrderRoute = '{{ route('sales.summary.index', [
                'model' => 'SalesOrder', 'take' => 'null', 'with' => 'null', 'where' => 'Canceled', 'field' => 'status']) }}';
            $http.get(canceledOrderRoute + '?ajax=true').then(function (response) {
                $scope.canceledOrderCount = response.data ? response.data.count : 0;
            });

            $scope.getCusLogo = function (order) {
                var route = '{{ route('sales.customer.logo', ['customer' => 'CUSTOMER']) }}';
                return route.replace('CUSTOMER', order.customer_id)
            };

            $scope.getRepImage = function (order) {
                var route = '{{ route('setting.staff.image', ['staff' => 'STAFF']) }}';
                return route.replace('STAFF', order.sales_rep.staff_id)
            };

        }]);
    </script>
@endsection