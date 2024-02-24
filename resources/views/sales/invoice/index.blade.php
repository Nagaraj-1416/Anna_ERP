@extends('layouts.master')
@section('title', 'Invoices')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row" ng-controller="SalesInvoiceController">
        <div class="col-12">
            <div class="card">
                <!-- .left-right-aside-column-->
                <div class="contact-page-aside">
                    <!-- .left-aside-column-->
                    <div class="left-aside">
                        <ul class="list-style-none">
                            <li class="text-muted m-t-20">FILTER INVOICES BY</li>
                            <li class="divider"></li>
                            <li ng-class="{'active': query.filter === 'today'}"
                                ng-click="filterUpdate('today')"><a href="" class="text-purple"><b>Today's Invoices</b></a>
                            </li>
                            <li ng-class="{'active': !query.filter}" ng-click="filterUpdate()">
                                <a href="">All Invoices</a></li>
                            <li ng-class="{'active': query.filter === 'drafted'}"
                                ng-click="filterUpdate('drafted')"><a href="">Drafted Invoices</a></li>
                            <li ng-class="{'active': query.filter === 'Open'}"
                                ng-click="filterUpdate('Open')"><a href="">Open Invoices</a></li>
                            <li ng-class="{'active': query.filter === 'Overdue'}"
                                ng-click="filterUpdate('Overdue')"><a href="">Overdue Invoices</a></li>
                            <li ng-class="{'active': query.filter === 'PartiallyPaid'}"
                                ng-click="filterUpdate('PartiallyPaid')"><a href="">Partially Paid Invoices</a>
                            </li>
                            <li ng-class="{'active': query.filter === 'Paid'}"
                                ng-click="filterUpdate('Paid')"><a href="">Paid Invoices</a></li>
                            <li ng-class="{'active': query.filter === 'Canceled'}"
                                ng-click="filterUpdate('Canceled')"><a href="">Canceled Invoices</a></li>
                            <li ng-class="{'active': query.filter === 'recentlyCreated'}"
                                ng-click="filterUpdate('recentlyCreated')"><a href="">Recently Created</a></li>
                            <li ng-class="{'active': query.filter === 'recentlyUpdated'}"
                                ng-click="filterUpdate('recentlyUpdated')"><a href="">Recently Modified</a></li>
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
                                    <h2 class="card-title m-t-10">Invoices @{{ total ? ("(" + total +")")
                                        :
                                        '' }}</h2>
                                </div>
                                <div class="ml-auto">
                                    <input type="text" id="demo-input-search2" ng-model="searchInvoices"
                                           placeholder="search for invoices here" class="form-control"
                                           ng-change="filterUpdated()">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-inverse card-warning cursor pointer"
                                     ng-click="filterUpdate('drafted')">
                                    <div class="box bg-warning text-center">
                                        <h1 class="font-light text-white">@{{ draftInvCount }}</h1>
                                        <h6 class="text-white">Drafted</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-primary card-inverse cursor pointer"
                                     ng-click="filterUpdate('Open')">
                                    <div class="box text-center">
                                        <h1 class="font-light text-white">@{{ openInvCount }}</h1>
                                        <h6 class="text-white">Open</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-inverse card-success cursor pointer"
                                     ng-click="filterUpdate('Overdue')">
                                    <div class="box text-center">
                                        <h1 class="font-light text-white">@{{ overdueInvCount }}</h1>
                                        <h6 class="text-white">Overdue</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-inverse card-info cursor pointer"
                                     ng-click="filterUpdate('PartiallyPaid')">
                                    <div class="box text-center">
                                        <h1 class="font-light text-white">@{{ partPaidInvCount }}</h1>
                                        <h6 class="text-white">Partially Paid</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-inverse card-dark cursor pointer"
                                     ng-click="filterUpdate('Paid')">
                                    <div class="box text-center">
                                        <h1 class="font-light text-white">@{{ paidInvCount }}</h1>
                                        <h6 class="text-white">Paid</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-inverse card-danger cursor pointer"
                                     ng-click="filterUpdate('Canceled')">
                                    <div class="box bg-danger text-center">
                                        <h1 class="font-light text-white">@{{ canceledInvCount }}</h1>
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
                                <p>loading invoices</p>
                            </div>
                        </div>
                        <div class="row" ng-hide="loading">
                            <div class="col-md-12 m-b-20">
                                <table id="demo-foo-addrow" class="table m-t-10 table-hover no-wrap contact-list"
                                       data-page-size="10">
                                    <thead>
                                    <tr>
                                        <th colspan="2">Invoice details</th>
                                        <th>Customer</th>
                                        <th>Order</th>
                                        <th>Order status</th>
                                        <th>Invoice status</th>
                                        <th class="text-right">Amount</th>
                                        <th class="text-right">Received</th>
                                        <th class="text-right">Balance</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-muted">
                                    <tr ng-repeat="(key, invoice) in invoices">
                                        <td style="width: 3%;">
                                            <img src="@{{ getCusLogo(invoice) }}" alt="user" class="img-circle"/>
                                        </td>
                                        <td>
                                            <a target="_blank" href="/sales/invoice/@{{ invoice.id }}">
                                                @{{ invoice.ref }}
                                            </a><br/>
                                            <small>
                                                <i class="mdi mdi-calendar"></i> @{{ invoice.created_at | date }}
                                            </small>
                                        </td>
                                        <td>
                                            <a target="_blank" href="/sales/customer/@{{ invoice.customer.id }}">
                                                @{{ invoice.customer.display_name }}
                                            </a>
                                        </td>
                                        <td>
                                            <a target="_blank" href="/sales/order/@{{ invoice.sales_order_id }}">
                                                @{{ invoice.order.ref }}
                                            </a>
                                        </td>
                                        <td>
                                            <span ng-class="invoice.order.status == 'Closed' ? 'text-green' : '' ||
                                                invoice.order.status == 'Open' ? 'text-warning' : '' || invoice.order.status == 'Canceled' ? 'text-danger' : ''">
                                                <i ng-if="invoice.order.status == 'Closed'"
                                                   class="ti-check"></i>
                                                <i ng-if="invoice.order.status == 'Open'"
                                                   class="ti-truck"></i>
                                                @{{ invoice.order.status }}
                                            </span>
                                        </td>
                                        <td>
                                            <span ng-class="invoice.status == 'Paid' ? 'text-green' : '' || invoice.status == 'Open' ? 'text-info' : '' ||
                                                invoice.status == 'Partially Paid' ? 'text-warning' : '' || invoice.status == 'Canceled' ? 'text-danger' : ''">
                                                <i ng-if="invoice.status == 'Paid'" class="ti-check"></i>
                                                <i ng-if="invoice.status == 'Open'"
                                                   class="ti-truck"></i>
                                                <i ng-if="invoice.status == 'Partially Paid'"
                                                   class="ti-time"></i>
                                                @{{ invoice.status }}
                                            </span>
                                        </td>
                                        <td class="text-right">@{{ invoice.order.total | number:2 }}</td>
                                        <td class="text-right">@{{ invoice.payment_received | number:2 }}</td>
                                        <td class="text-right">@{{ invoice.payment_remaining | number:2 }}</td>
                                        <td class="text-center">
                                            <a title="View invoice details" class="p-10"
                                               href="/sales/invoice/@{{ invoice.id }}">
                                                <i class="ti-eye" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- pagination panel -->
                            <div class="col-md-12" ng-show="invoices.length">
                                @include('general.pagination.pagination')
                            </div>
                        </div>
                        <div class="row" ng-hide="loading" ng-if="invoices.length === 0 && !filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">You haven't added any invoices yet, visit sales orders to
                                            generate invoices.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- if there no order available message -->
                        <div class="row" ng-hide="loading" ng-if="invoices.length === 0 && filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">There are <code>no</code> invoices found.</p>
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
    @include('general.date-range.script')
    <script>
        app.controller('SalesInvoiceController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('sales.invoice.index') }}';
            $scope.invoices = [];
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
                userId: null,
                overdue: '{{ $overDue }}',
                dateRange: null,
                from_date: '{{ carbon()->toDateString() }}',
                to_date: '{{ carbon()->toDateString() }}',
            };
            $scope.el = {
                userDropDown: $('.user-drop-down'),
                customerDropDown: $('.customer-drop-down')
            };
            $scope.urls = {
                user: '{{ route('setting.user.search') }}',
                customer: '{{ route('sales.customer.search') }}',
            };
            $scope.filterUpdated = function () {
                $scope.filterd = true;
                $scope.query.search = $scope.searchInvoices;
                $scope.fetchInvoices();
            };

            $scope.filterUpdate = function (filter) {
                $scope.query.filter = filter ? filter : '';
                $scope.filterd = true;
                $scope.fetchInvoices();
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
                $scope.fetchInvoices();
            };

            $scope.fetchInvoices = function () {
                $scope.loading = true;
                $scope.query.page = $scope.currentPaginationPage + 1;
                var routeParam = $.param($scope.query);
                $http.get(moduleRoute + '?' + routeParam).then(function (response) {
                    $scope.loading = false;
                    $scope.invoices = response.data.data;
                    $scope.pagination = response.data;
                    $scope.total = response.data.total;
                    $scope.range();
                });
            };
            $scope.fetchInvoices();

            $scope.resetFilters = function () {
                $scope.query.userId = null;
                $scope.query.customerId = null;
                $scope.query.dateRange = null;
                $scope.query.filter = 'today';
                $scope.query.from_date = '{{ carbon()->toDateString() }}';
                $scope.query.to_date = '{{ carbon()->toDateString() }}';
                dateRangeDropDown($scope);
                $scope.el.userDropDown.dropdown('clear');
                $scope.el.customerDropDown.dropdown('clear');
                // $scope.fetchInvoices();
            };

            $scope.el.userDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: $scope.urls.user + '/{query}',
                    cache: false
                },
                onChange: function (val) {
                    $scope.query.userId = val;
                    $scope.fetchInvoices();
                }
            });

            $scope.el.customerDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    $scope.query.customerId = val;
                    $scope.fetchInvoices();
                }
            });

            $scope.handleDateRangeChange = function (val) {
                if(val){
                    $scope.query.dateRange = true;
                    $scope.query.filter = '';
                    $scope.fetchInvoices();
                }
            };
            dateRangeDropDown($scope);

            /** get draft invoice count */
            var draftInvRoute = '{{ route('sales.summary.index', [
                'model' => 'Invoice', 'take' => 'null', 'with' => 'null', 'where' => 'Draft', 'field' => 'status']) }}';
            $http.get(draftInvRoute + '?ajax=true').then(function (response) {
                $scope.draftInvCount = response.data ? response.data.count : 0;
            });

            /** get open invoice count */
            var openInvRoute = '{{ route('sales.summary.index', [
                'model' => 'Invoice', 'take' => 'null', 'with' => 'null', 'where' => 'Open', 'field' => 'status']) }}';
            $http.get(openInvRoute + '?ajax=true').then(function (response) {
                $scope.openInvCount = response.data ? response.data.count : 0;
            });

            /** get overdue invoice count */
            var overdueInvRoute = '{{ route('sales.summary.index', [
                'model' => 'Invoice', 'take' => 'null', 'with' => 'null', 'where' => 'Overdue', 'field' => 'status']) }}';
            $http.get(overdueInvRoute + '?ajax=true').then(function (response) {
                $scope.overdueInvCount = response.data ? response.data.count : 0;
            });

            /** get partially paid invoice count */
            var partPaidInvRoute = '{{ route('sales.summary.index', [
                'model' => 'Invoice', 'take' => 'null', 'with' => 'null', 'where' => 'Partially Paid', 'field' => 'status']) }}';
            $http.get(partPaidInvRoute + '?ajax=true').then(function (response) {
                $scope.partPaidInvCount = response.data ? response.data.count : 0;
            });

            /** get paid invoice count */
            var paidInvRoute = '{{ route('sales.summary.index', [
                'model' => 'Invoice', 'take' => 'null', 'with' => 'null', 'where' => 'Paid', 'field' => 'status']) }}';
            $http.get(paidInvRoute + '?ajax=true').then(function (response) {
                $scope.paidInvCount = response.data ? response.data.count : 0;
            });

            /** get canceled invoice count */
            var canceledInvRoute = '{{ route('sales.summary.index', [
                'model' => 'Invoice', 'take' => 'null', 'with' => 'null', 'where' => 'Canceled', 'field' => 'status']) }}';
            $http.get(canceledInvRoute + '?ajax=true').then(function (response) {
                $scope.canceledInvCount = response.data ? response.data.count : 0;
            });

            /** get refunded invoice count */
            var refundedInvRoute = '{{ route('sales.summary.index', [
                'model' => 'Invoice', 'take' => 'null', 'with' => 'null', 'where' => 'Refunded', 'field' => 'status']) }}';
            $http.get(refundedInvRoute + '?ajax=true').then(function (response) {
                $scope.refundedInvCount = response.data ? response.data.count : 0;
            });

            $scope.getCusLogo = function (invoice) {
                var route = '{{ route('sales.customer.logo', ['customer' => 'CUSTOMER']) }}';
                return route.replace('CUSTOMER', invoice.customer_id)
            };
        }]);
    </script>
@endsection
