@extends('layouts.master')
@section('title', 'Customers')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row" ng-controller="CustomerController">
        <div class="col-12">
            <div class="card">
                <!-- .left-right-aside-column-->
                <div class="contact-page-aside">
                    <!-- .left-aside-column-->
                    <div class="left-aside">
                        @can('create', new \App\Customer())
                            <a target="_blank" href="{{ route('sales.customer.create') }}"
                               class="btn btn-info btn-block">
                                <i class="fa fa-plus"></i> Add New Customer
                            </a>
                        @endcan
                        <ul class="list-style-none">
                            <li class="divider"></li>
                            <li class="m-t-10">Display Name</li>
                            <li>
                                <input type="text" id="" ng-model="displayName"
                                       placeholder="search by display name here" class="form-control"
                                       ng-change="filterDisplayName()">
                            </li>
                        </ul>

                        <ul class="list-style-none">
                            <li class="divider"></li>
                            <li class="text-muted m-t-20">FILTER CUSTOMERS BY</li>
                            <li class="divider"></li>
                            <li ng-class="{'active': !query.filter}"><a href="" ng-click="filterUpdate()">All
                                    Customers</a>
                            </li>
                            <li ng-class="{'active': query.filter === 'Top10'}" ng-click="filterUpdate('Top10')"><a
                                        href="">Top 10 Customers</a></li>
                            <li ng-class="{'active': query.filter === 'active'}"><a href=""
                                                                                    ng-click="filterUpdate('active')">Active
                                    Customers</a></li>
                            <li ng-class="{'active': query.filter === 'inActive'}"><a href=""
                                                                                      ng-click="filterUpdate('inActive')">Inactive
                                    Customers</a></li>
                            <li ng-class="{'active': query.filter === 'located'}">
                                <a href="" class="text-purple" ng-click="filterUpdate('located')">Located Customers</a>
                            </li>
                            <li ng-class="{'active': query.filter === 'notLocated'}">
                                <a href="" class="text-danger" ng-click="filterUpdate('notLocated')">Not Located
                                    Customers</a>
                            </li>
                            <li ng-class="{'active': query.filter === 'recentlyCreated'}"><a href=""
                                                                                             ng-click="filterUpdate('recentlyCreated')">Recently
                                    Created</a></li>
                            <li ng-class="{'active': query.filter === 'recentlyModified'}"><a href=""
                                                                                              ng-click="filterUpdate('recentlyModified')">Recently
                                    Modified</a></li>
                        </ul>
                        <ul class="list-style-none">
                            <li class="divider"></li>
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
                            <li class="m-t-10">Route</li>
                            <li>
                                <div class="ui fluid  search selection dropdown route-drop-down">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a route</div>
                                    <div class="menu">
                                        @foreach(routeDropDown() as $key => $route)
                                            <div class="item" data-value="{{ $key }}">{{ $route }}</div>
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
                        <hr>
                        <p><b>Export to</b></p>
                        <a href="{{ route('sales.customer.export') }}" class="btn btn-pdf">
                            PDF
                        </a>
                        <a href="{{ route('sales.customer.export', ['type' => 'excel']) }}"
                           class="btn btn-excel">
                            Excel
                        </a>
                        <hr>
                        <a href="{{ route('sales.customer.change.route', ['length' => 100]) }}"
                           class="btn btn-danger">
                            Change Routes & Locations
                        </a>
                    </div>
                    <!-- /.left-aside-column-->
                    <div class="right-aside custom-right-aside">
                        <div class="right-page-header">
                            <div class="d-flex m-b-10">
                                <div class="align-self-center">
                                    <h3 class="card-title m-t-10">Customers @{{ total ? ("(" + total +")") :
                                        '' }}</h3>
                                </div>
                                <div class="ml-auto">

                                </div>
                                <div class="ml-auto">
                                    <input type="text" id="demo-input-search2" ng-model="searchCustomers"
                                           placeholder="search for customers here" class="form-control"
                                           ng-change="filterUpdated()">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-inverse card-warning">
                                    <div class="box bg-warning text-center">
                                        <h1 class="font-light text-white">0</h1>
                                        <h6 class="text-white">Estimates</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-primary card-inverse">
                                    <div class="box text-center">
                                        <h1 class="font-light text-white">0</h1>
                                        <h6 class="text-white">Orders</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-inverse card-success">
                                    <div class="box text-center">
                                        <h1 class="font-light text-white">0</h1>
                                        <h6 class="text-white">Overdue Invoices</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-inverse card-info">
                                    <div class="box text-center">
                                        <h1 class="font-light text-white">0</h1>
                                        <h6 class="text-white">Open Invoices</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-inverse card-dark">
                                    <div class="box text-center">
                                        <h1 class="font-light text-white">0</h1>
                                        <h6 class="text-white">Partially Paid</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-inverse card-danger">
                                    <div class="box bg-danger text-center">
                                        <h1 class="font-light text-white">0</h1>
                                        <h6 class="text-white">Paid</h6>
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
                                <p>loading customers</p>
                            </div>
                        </div>
                        <div class="row" ng-hide="loading">
                            <div class="col-md-3 col-sm-6 col-xs-12" ng-repeat="(key, customer) in customers">
                                <div class="card card-body"
                                     ng-class="customer.total_outstanding != 0 ? 'border-warning' : ''">
                                    <div class="row">
                                        <div class="col-md-4 text-center">
                                            <img src="@{{ getCusLogo(customer) }}" alt="img" class="img-responsive">
                                            <small ng-show="customer.gps_lat && customer.gps_long">
                                                <a href="@{{ getRoute(customer) }}" class="text-purple"><i
                                                            class="fa fa-map-marker"></i> View Location</a>
                                            </small>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="box-title m-b-0">
                                                <a target="_blank" href="customer/@{{ customer.id }}">@{{
                                                    customer.display_name }}</a>
                                            </h6>
                                            <small>@{{ customer.full_name }} | @{{ customer.code }}</small>
                                            <br/>
                                            <small>@{{ customer.tamil_name }}</small>
                                            <p class="text-muted">
                                                <small><b>Total Orders:</b> @{{ customer.total_orders }}</small>
                                                <br/>
                                                <small class="text-warning"><b>Total Sales:</b> @{{ customer.total_sales
                                                    | number }}
                                                </small>
                                                <br/>
                                                <small class="text-green"><b>Total Paid:</b> @{{ customer.total_paid |
                                                    number }}
                                                </small>
                                                <span ng-if="customer.total_outstanding != 0">
                                                <br/>
                                                <small class="text-danger"><b>Total Outstanding:</b> @{{ customer.total_outstanding | number }}</small>
                                                </span>
                                                <br/>
                                                <small><b>Route:</b> @{{ customer.route.name }}</small>
                                            </p>
                                            <address>
                                                <abbr title="Phone">P:</abbr> @{{ customer.mobile }}
                                                <abbr title="Phone">T:</abbr> @{{ customer.phone }}
                                            </address>
                                            <a target="_blank" class="btn btn-info btn-sm" href="/sales/customer/@{{ customer.id }}/ledger">
                                                <i class="ti-book"></i> View Ledger
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- pagination panel -->
                            <div class="col-md-12" ng-show="customers.length">
                                @include('general.pagination.pagination')
                            </div>
                        </div>
                        <div class="row" ng-hide="loading" ng-if="!filterd && customers.length === 0">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">You haven't added any customer yet, click below button to
                                            add.</p>
                                        <a href="{{ route('sales.customer.create') }}" class="btn btn-info"> <i
                                                    class="fa fa-plus"></i> Add New Customer</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- if there no customers available message -->
                        <div class="row" ng-hide="loading" ng-if="filterd && customers.length === 0">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">There are <code>no</code> customers found.</p>
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
        app.controller('CustomerController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('sales.customer.index') }}';
            $scope.customers = [];
            $scope.filtered = false;
            $scope.loading = true;
            $scope.param = '';

            $scope.query = {
                salesRepId: null,
                routeId: null,
                search: '',
                page: null,
                filter: null,
                displayName: null
            };

            $scope.el = {
                repDropDown: $('.rep-drop-down'),
                routeDropDown: $('.route-drop-down')
            };
            $scope.urls = {
                rep: '{{ route('setting.rep.search') }}',
                route: '{{ route('setting.route.search') }}'
            };

            $scope.filterDisplayName = function () {
                $scope.filterd = true;
                $scope.query.displayName = $scope.displayName;
                $scope.fetchCustomer(true);
            };
            $scope.filterUpdate = function (filter) {
                $scope.query.filter = filter ? filter : '';
                $scope.filterd = true;
                $scope.fetchCustomer();
            };

            $scope.pagination = {};
            $scope.currentPaginationPage = 0;
            $scope.filterUpdated = function () {
                $scope.filterd = true;
                $scope.query.search = $scope.searchCustomers;
                $scope.fetchCustomer(true);
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
                $scope.fetchCustomer();
            };
            $scope.fetchCustomer = function (search) {
                $scope.loading = true;
                if (search) {
                    $scope.currentPaginationPage = 0;
                }
                $scope.query.page = $scope.currentPaginationPage + 1;

                var queryRoute = $.param($scope.query);
                $http.get(moduleRoute + '?ajax=true&' + queryRoute).then(function (response) {
                    $scope.loading = false;
                    $scope.customers = response.data.data;
                    $scope.pagination = response.data;
                    $scope.total = response.data.total;
                    $scope.range();
                });
            };
            $scope.fetchCustomer();

            $scope.resetFilters = function () {
                $scope.el.repDropDown.dropdown('clear');
                $scope.el.routeDropDown.dropdown('clear');
                $scope.query = {
                    salesRepId: null,
                    routeId: null,
                    search: '',
                    page: null,
                    filter: null
                };
                $scope.filterd = false;
                $scope.param = '';
                $scope.fetchCustomer();
            };

            $scope.el.repDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    if (val) {
                        $scope.param = 'salesRepId=' + val;
                        $scope.filterd = true;
                        $scope.query.salesRepId = val;
                        $scope.fetchCustomer();
                    }
                }
            });

            $scope.el.routeDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    if (val) {
                        $scope.param = 'routeId=' + val;
                        $scope.filterd = true;
                        $scope.query.routeId = val;
                        $scope.fetchCustomer();
                    }
                }
            });

            $scope.getCusLogo = function (customer) {
                var route = '{{ route('sales.customer.logo', ['customer' => 'CUSTOMER']) }}';
                return route.replace('CUSTOMER', customer.id)
            };

            $scope.route = '{{ route('map.index') }}';
            $scope.getRoute = function (customer) {
                var info = {heading: customer.display_name, 'code': customer.code};
                $scope.customerParam = {
                    startLat: customer.gps_lat,
                    startLng: customer.gps_long,
                    startInfo: JSON.stringify(info)
                };
                return $scope.route + '?' + $.param($scope.customerParam);
            }
        }]);
    </script>
@endsection