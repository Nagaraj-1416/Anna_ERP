@extends('layouts.master')
@section('title', 'Sales Allocations')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row" ng-controller="AllocationController">
        <div class="col-12">
            <div class="card">
                <!-- .left-right-aside-column-->
                <div class="contact-page-aside">
                    <!-- .left-aside-column-->
                    <div class="left-aside">
                        @can('create', new \App\DailySale())
                            <a target="_blank" href="{{ route('sales.allocation.create') }}"
                               class="btn btn-info btn-block">
                                <i class="fa fa-plus"></i> Allocate
                            </a>
                        @endcan
                        <ul class="list-style-none">
                            <li class="text-muted m-t-20">FILTER ALLOCATION BY</li>
                            <li class="divider"></li>
                            <li ng-class="{'active': query.filter === 'today'}"
                                ng-click="filterUpdate('today')"><a href="" class="text-purple"><b>Today's
                                        Allocations</b></a>
                            </li>
                            <li ng-class="{'active': query.filter === 'HoPending'}"
                                ng-click="filterUpdate('HoPending')"><a href="" class="text-danger">Handovers
                                    Pending</a></li>
                            <li ng-class="{'active': !query.filter}"
                                ng-click="filterUpdate()"><a
                                        href="">All Allocations</a></li>
                            <li class="divider"></li>
                            <li ng-class="{'active': query.filter === 'Van'}"
                                ng-click="filterUpdate('Van')"><a href="">Van Allocations</a></li>
                            <li ng-class="{'active': query.filter === 'Shop'}"
                                ng-click="filterUpdate('Shop')"><a href="">Shop Allocations</a></li>
                            <li class="divider"></li>
                            <li ng-class="{'active': query.filter === 'Draft'}"
                                ng-click="filterUpdate('Draft')"><a href="">Drafted Allocations</a></li>
                            <li ng-class="{'active': query.filter === 'Active'}"
                                ng-click="filterUpdate('Active')"><a href="">Active Allocations</a></li>
                            <li ng-class="{'active': query.filter === 'Progress'}"
                                ng-click="filterUpdate('Progress')"><a href="">Progress Allocations</a></li>
                            <li ng-class="{'active': query.filter === 'Completed'}"
                                ng-click="filterUpdate('Completed')"><a href="">Completed Allocations</a></li>
                            <li ng-class="{'active': query.filter === 'Canceled'}"
                                ng-click="filterUpdate('Canceled')"><a href="">Canceled Allocations</a></li>
                            <li ng-class="{'active': query.filter === 'recentlyCreated'}"
                                ng-click="filterUpdate('recentlyCreated')"><a href="">Recently Created</a></li>
                            <li ng-class="{'active': query.filter === 'recentlyUpdated'}"
                                ng-click="filterUpdate('recentlyUpdated')"><a href="">Recently Modified</a></li>
                        </ul>
                        <ul class="list-style-none">
                            <li class="divider"></li>
                            @include('general.date-range.index')

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

                            <li class="m-t-10">Vehicle / Shop</li>
                            <li>
                                <div class="ui fluid  search selection dropdown location-drop-down">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a vehicle/shop</div>
                                    <div class="menu">
                                        @foreach(salesLocationDropDown() as $key => $location)
                                            <div class="item" data-value="{{ $key }}">{{ $location }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </li>

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

                            <li class="m-t-10">Product</li>
                            <li>
                                <div class="ui fluid  search selection dropdown product-drop-down">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a product</div>
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
                                    <h2 class="card-title m-t-10">Allocations @{{ total ? ("(" + total +")") :
                                        '' }}</h2>
                                </div>
                                <div class="ml-auto">
                                    <input type="text" id="demo-input-search2" ng-model="searchCredits"
                                           placeholder="search for allocations here" class="form-control"
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
                                <p>loading allocations</p>
                            </div>
                        </div>
                        <div class="row" ng-hide="loading">
                            <div class="col-md-12 m-b-20">
                                <table id="demo-foo-addrow" class="table m-t-10 table-hover no-wrap contact-list"
                                       data-page-size="10">
                                    <thead>
                                        <tr>
                                            <th colspan="2">Allocation details</th>
                                            <th style="width: 10%;" class="text-center">Products</th>
                                            <th style="width: 10%;" class="text-center">Customers</th>
                                            <th style="width: 10%;">Status</th>
                                            <th class="text-center" style="width: 10%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="allocation in allocations">
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-3 text-center" ng-show="allocation.customers.length">
                                                        <img ng-src="@{{ getRepImage(allocation) }}" src="" ng-show="allocation.rep_id"
                                                             alt="img" class="img-responsive">
                                                    </div>
                                                    <div class="col-md-9">
                                                        <a target="_blank" href="@{{ getShowURL(allocation.id) }}">
                                                            @{{ allocation.code }}
                                                        </a> <br />
                                                        <small ng-if="allocation.day_type == 'Single'">
                                                            @{{ allocation.from_date }}
                                                        </small>
                                                        <small ng-if="allocation.day_type == 'Multiple'">
                                                            @{{ allocation.from_date }} to @{{ allocation.to_date }}
                                                        </small>
                                                        <br/>
                                                        <small>@{{ allocation.route.name }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span>
                                                    @{{ allocation.sales_location.name }} <br />
                                                    @{{ allocation.rep.name }}
                                                </span> <br />
                                                <span ng-show="allocation.customers.length" ng-if="allocation.status == 'Progress' || allocation.status == 'Completed'">
                                                    <small class="text-green"><b>Visited:</b> @{{ allocation.visited_customers }}</small>
                                                    | <small class="text-danger"><b>Not Visited:</b> @{{ allocation.not_visited_customers }}</small>
                                                </span>
                                            </td>
                                            <td class="text-center">@{{ allocation.items.length }}</td>
                                            <td class="text-center">@{{ allocation.customers.length }}</td>
                                            <td>
                                                <span ng-class="allocation.status == 'Draft' ? 'text-info' : ''  ||
                                                        allocation.status == 'Active' ? 'text-info' : '' ||
                                                        allocation.status == 'Progress' ? 'text-warning' : '' ||
                                                        allocation.status == 'Completed' ? 'text-green' : '' ||
                                                        allocation.status == 'Canceled' ? 'text-danger' : ''">
                                                        <i ng-if="allocation.status == 'Completed'"
                                                           class="fa fa-check"></i> @{{ allocation.status }}
                                                    </span>
                                            </td>
                                            <td>
                                                <a target="_blank" ng-show="allocation.customers.length" class="btn btn-excel btn-sm" href="/sales/allocation/@{{ allocation.id }}/sales-sheet">
                                                    <i class="fa fa-book"></i> View Sales Sheet
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{--<div class="col-md-4 col-sm-6 col-xs-12"
                                 ng-repeat="allocation in allocations">
                                <div class="card card-body"
                                     ng-class="allocation.status == 'Completed' && allocation.not_visited_customers != 0 ? 'border-warning' : ''">
                                    <div class="row">
                                        <div class="col-md-4 text-center">
                                            <img src="" ng-show="allocation.rep_id"
                                                 alt="img" class="img-responsive">
                                            <small>
                                                <span ng-class="allocation.status == 'Draft' ? 'text-info' : ''  ||
                                                        allocation.status == 'Active' ? 'text-info' : '' ||
                                                        allocation.status == 'Progress' ? 'text-warning' : '' ||
                                                        allocation.status == 'Completed' ? 'text-green' : '' ||
                                                        allocation.status == 'Canceled' ? 'text-danger' : ''">
                                                        <i ng-if="allocation.status == 'Completed'"
                                                           class="fa fa-check"></i> <b>@{{ allocation.status }}</b>
                                                    </span>
                                            </small>
                                            <br/>
                                            <small>@{{ allocation.sales_location.name }}</small>
                                        </div>
                                        <div class="col-md-8">
                                            <h5 class="box-title m-b-0">
                                                <a target="_blank" href="allocation/@{{ allocation.id }}">
                                                    @{{ allocation.code }}
                                                </a>
                                            </h5>
                                            <small ng-if="allocation.day_type == 'Single'">
                                                @{{ allocation.from_date }}
                                            </small>
                                            <small ng-if="allocation.day_type == 'Multiple'">
                                                @{{ allocation.from_date }} to @{{ allocation.to_date }}
                                            </small>
                                            <p class="text-muted">
                                                <small><b>Products:</b> @{{ allocation.items.length }}</small>
                                                <br/>
                                                <small><b>Customers:</b> @{{ allocation.customers.length }}</small>
                                                <span ng-if="allocation.status == 'Progress' || allocation.status == 'Completed'">
                                                    <br/>
                                                    <small class="text-green"><b>Visited:</b> @{{ allocation.visited_customers }}</small>
                                                    <br/>
                                                    <small class="text-danger"><b>Not Visited:</b> @{{ allocation.not_visited_customers }}</small>
                                                </span>
                                                <br/>
                                                <small>@{{ allocation.route.name }}</small>
                                            </p>
                                            @{{ allocation.rep.name }}
                                            <a target="_blank" class="btn btn-excel btn-sm" href="/sales/allocation/@{{ allocation.id }}/sales-sheet">
                                                <i class="fa fa-book"></i> View Sales Sheet
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>--}}
                            <!-- pagination panel -->
                            <div class="col-md-12" ng-show="pagination.total > pagination.per_page">
                                @include('general.pagination.pagination')
                            </div>
                        </div>
                        <div class="row" ng-hide="loading" ng-if="allocations.length === 0 && !filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">You haven't added any sales allocation yet, click below
                                            button to
                                            add.</p>
                                        <a target="_blank" href="{{ route('sales.allocation.create') }}"
                                           class="btn btn-info">
                                            <i class="fa fa-plus"></i> Allocate
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- if there no allocation available message -->
                        <div class="row" ng-hide="loading" ng-if="allocations.length === 0 && filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">There are <code>no</code> allocations found.</p>
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
    <style>
        .error {
            color: red;
        }
    </style>
@endsection
@section('script')
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script src="{{ asset('js/vendor/object-helper.js') }}"></script>
    @include('general.date-range.script')
    <script>
        app.controller('AllocationController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('sales.allocation.index') }}';
            let moduleShowRoute = '{{ route('sales.allocation.show', ["_REPLACE_"]) }}';
            $scope.allocations = [];
            $scope.filterd = true;
            $scope.loading = true;
            $scope.pagination = {};
            $scope.currentPaginationPage = 0;
            $scope.query = {
                ajax: true,
                page: null,
                filter: 'today',
                search: null,
                route: null,
                rep: null,
                location: null,
                customer: null,
                product: null,
                from_date: '{{ carbon()->toDateString() }}',
                to_date: '{{ carbon()->toDateString() }}',
                dateRange: null,
            };

            $scope.filterUpdated = function () {
                $scope.query.search = $scope.searchCredits;
                $scope.fetchAllocations();
            };

            $scope.routeDD = $('.route-drop-down');
            $scope.repDD = $('.rep-drop-down');
            $scope.locationDD = $('.location-drop-down');
            $scope.customerDD = $('.customer-drop-down');
            $scope.productDD = $('.product-drop-down');

            //Route filter drop down
            $scope.routeDD.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    if (!val) return;
                    $scope.query.route = val;
                    $scope.fetchAllocations();
                }
            });

            //Rep filter drop down
            $scope.repDD.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    if (!val) return;
                    $scope.query.rep = val;
                    $scope.fetchAllocations();
                }
            });

            //Location filter drop down
            $scope.locationDD.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    if (!val) return;
                    $scope.query.location = val;
                    $scope.fetchAllocations();
                }
            });

            //Customer filter drop down
            $scope.customerDD.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    if (!val) return;
                    $scope.query.customer = val;
                    $scope.fetchAllocations();
                }
            });

            //Product filter drop down
            $scope.productDD.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: '{{ route('setting.product.search', ['type' => 'All']) }}/{query}',
                    cache: false,
                },
                onChange: function (val) {
                    if (!val) return;
                    $scope.query.product = val;
                    $scope.fetchAllocations();
                }
            });

            //Search filter function
            $scope.filterUpdate = function (filter) {
                if ($scope.query.filter !== filter) {
                    $scope.query.filter = filter ? filter : '';
                    $scope.fetchAllocations();
                }
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
                $scope.fetchAllocations();
            };

            $scope.fetchAllocations = function () {
                $scope.loading = true;
                $scope.query.page = $scope.currentPaginationPage + 1;
                var queryRoute = $.param($scope.query);
                $http.get(moduleRoute + '?' + queryRoute).then(function (response) {
                    $scope.loading = false;
                    $scope.allocations = response.data.data;
                    $scope.pagination = response.data;
                    $scope.total = response.data.total;
                    $scope.range();
                    $scope.filterd = true;
                });
            };
            $scope.fetchAllocations();

            $scope.resetFilters = function () {
                $scope.query = {
                    ajax: true,
                    page: 0,
                    filter: 'today',
                    search: '',
                    route: null,
                    rep: null,
                    location: null,
                    customer: null,
                    product: null,
                    dateRange: null,
                    from_date: '{{ carbon()->toDateString() }}',
                    to_date: '{{ carbon()->toDateString() }}',
                };
                $scope.searchAllocations = '';

                $scope.routeDD.dropdown('clear');
                $scope.repDD.dropdown('clear');
                $scope.locationDD.dropdown('clear');
                $scope.customerDD.dropdown('clear');
                $scope.productDD.dropdown('clear');
                dateRangeDropDown($scope);
                $scope.fetchAllocations();
            };

            $scope.getRepImage = function (allocation) {
                var route = '{{ route('setting.staff.image', ['staff' => 'STAFF']) }}';
                return route.replace('STAFF', allocation.rep.staff_id)
            };

            $scope.getShowURL = function (id) {
                return moduleShowRoute.replace('_REPLACE_', id);
            };

            $scope.handleDateRangeChange = function (val) {
                // console.log(11);
                if (!val) return;
                $scope.query.dateRange = true;
                $scope.query.filter = '';
                $scope.fetchAllocations();
            };
            dateRangeDropDown($scope);
        }]);
    </script>
@endsection
