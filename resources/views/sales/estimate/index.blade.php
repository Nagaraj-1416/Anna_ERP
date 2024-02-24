@extends('layouts.master')
@section('title', 'Estimates')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row" ng-controller="EstimateController">
        <div class="col-12">
            <div class="card">
                <!-- .left-right-aside-column-->
                <div class="contact-page-aside">
                    <!-- .left-aside-column-->
                    <div class="left-aside">
                        @can('create', new \App\Estimate())
                            <a target="_blank" href="{{ route('sales.estimate.create') }}"
                               class="btn btn-info btn-block">
                                <i class="fa fa-plus"></i> Add New Estimate
                            </a>
                        @endcan
                        <ul class="list-style-none">
                            <li class="text-muted m-t-20">FILTER ESTIMATES BY</li>
                            <li class="divider"></li>
                            <li ng-class="{'active': !query.filter}"
                                ng-click="filterUpdate()" class="active"><a href="">All Estimates</a>
                            </li>
                            <li ng-class="{'active': query.filter === 'Draft'}"
                                ng-click="filterUpdate('Draft')"><a href="">Draft Estimates</a></li>
                            <li ng-class="{'active': query.filter === 'Sent'}"
                                ng-click="filterUpdate('Sent')"><a href="">Sent Estimates</a></li>
                            <li ng-class="{'active': query.filter === 'Accepted'}"
                                ng-click="filterUpdate('Accepted')"><a href="">Accepted Estimates</a></li>
                            <li ng-class="{'active': query.filter === 'Declined'}"
                                ng-click="filterUpdate('Declined')"><a href="">Declined Estimates</a></li>
                            <li ng-class="{'active': query.filter === 'Converted'}"
                                ng-click="filterUpdate('Converted')"><a href="">Converted to Orders</a></li>
                            <li ng-class="{'active': query.filter === 'recentlyCreated'}"
                                ng-click="filterUpdate('recentlyCreated')"><a href="">Recently Created</a>
                            </li>
                            <li ng-class="{'active': query.filter === 'recentlyUpdated'}"
                                ng-click="filterUpdate('recentlyUpdated')"><a href="">Recently Modified</a>
                            </li>
                        </ul>
                        <ul class="list-style-none">
                            <li class="divider"></li>
                            <li class="m-t-10">Customer</li>
                            <li>
                                <div class="ui fluid  search selection dropdown customer-drop-down">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a customer</div>
                                    <div class="menu"></div>
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
                            <li class="m-t-10">Sales Rep</li>
                            <li>
                                <div class="ui fluid  search selection dropdown rep-drop-down">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a sales rep</div>
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
                                    <h2 class="card-title m-t-10">Estimates @{{ total ? ("(" + total +")")
                                        :
                                        '' }}</h2>
                                </div>
                                <div class="ml-auto">
                                    <input type="text" id="demo-input-search2" ng-model="searchEstimates"
                                           ng-change="filterUpdated()" placeholder="search for estimates here"
                                           class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-inverse card-warning cursor pointer"
                                     ng-click="filterUpdate('Draft')">
                                    <div class="box bg-warning text-center">
                                        <h1 class="font-light text-white">@{{ draftedEstimateCount }}</h1>
                                        <h6 class="text-white">Drafted</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-primary card-inverse cursor pointer"
                                     ng-click="filterUpdate('Sent')">
                                    <div class="box text-center">
                                        <h1 class="font-light text-white">@{{ sentEstimateCount }}</h1>
                                        <h6 class="text-white">Sent</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-inverse card-success cursor pointer"
                                     ng-click="filterUpdate('Accepted')">
                                    <div class="box text-center">
                                        <h1 class="font-light text-white">@{{ acceptedEstimateCount }}</h1>
                                        <h6 class="text-white">Accepted</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-inverse card-info cursor pointer"
                                     ng-click="filterUpdate('Converted')">
                                    <div class="box text-center">
                                        <h1 class="font-light text-white">@{{ orderedEstimateCount }}</h1>
                                        <h6 class="text-white">Converted</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-inverse card-danger cursor pointer"
                                     ng-click="filterUpdate('Declined')">
                                    <div class="box bg-danger text-center">
                                        <h1 class="font-light text-white">@{{ declinedEstimateCount }}</h1>
                                        <h6 class="text-white">Declined</h6>
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
                                <p>loading Estimates</p>
                            </div>
                        </div>
                        <div class="row" ng-hide="loading">
                            <div class="col-md-3 col-sm-6 col-xs-12"
                                 ng-repeat="estimate in estimates">
                                <div class="card card-body">
                                    <div class="row">
                                        <div class="col-md-3 text-center">
                                            <img src="@{{ getCusLogo(estimate) }}" alt="img" class="img-responsive">
                                        </div>
                                        <div class="col-md-9">
                                            <h3 class="box-title m-b-0">
                                                <a target="_blank" href="estimate/@{{ estimate.id }}">@{{
                                                    estimate.estimate_no }}</a>
                                            </h3>
                                            <small>@{{ estimate.customer.display_name }} | @{{ estimate.customer.mobile
                                                }}
                                            </small>
                                            <p class="text-muted">
                                                <small><b>Status:</b> @{{ estimate.status }}</small>
                                                <br/>
                                                <small><b>Expiry Date:</b> @{{ estimate.expiry_date }}</small>
                                            </p>
                                            @{{ estimate.estimate_date }} | @{{ estimate.total | number }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- pagination panel -->
                            <div class="col-md-12" ng-show="estimates.length">
                                @include('general.pagination.pagination')
                            </div>
                        </div>
                        <div class="row" ng-if="estimates.length === 0 && !filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">You haven't added any estimates yet, click below button to
                                            add.</p>
                                        <a target="_blank" href="{{ route('sales.estimate.create') }}"
                                           class="btn btn-info">
                                            <i class="fa fa-plus"></i> Add New Estimate
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- if there no estimates available message -->
                        <div class="row" ng-hide="loading" ng-if="estimates.length == 0 && filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">There are <code>no</code> estimates found.</p>
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
        app.controller('EstimateController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('sales.estimate.index') }}';
            $scope.estimates = [];
            $scope.filterd = false;
            $scope.loading = true;
            $scope.pagination = {};
            $scope.currentPaginationPage = 0;
            $scope.query = {
                ajax: true,
                page: null,
                filter: null,
                search: null,
                customerId: null,
                productId: null,
                salesRepId: null,
                userId: null
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
            $scope.filterUpdated = function () {
                $scope.filterd = true;
                $scope.query.search = $scope.searchEstimates;
                $scope.fetchEstimations();
            };
            $scope.filterUpdate = function (filter) {
                $scope.query.filter = filter ? filter : '';
                $scope.filterd = true;
                $scope.fetchEstimations();
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
                $scope.fetchEstimations();
            };

            $scope.fetchEstimations = function () {
                $scope.loading = true;

                $scope.query.page = $scope.currentPaginationPage + 1;
                var queryRoute = $.param($scope.query);
                $http.get(moduleRoute + '?' + queryRoute).then(function (response) {
                    $scope.loading = false;
                    $scope.estimates = response.data.data;
                    $scope.pagination = response.data;
                    $scope.total = response.data.total;
                    $scope.range();
                });
            };
            $scope.fetchEstimations();

            $scope.resetFilters = function () {
                $scope.el.repDropDown.dropdown('clear');
                $scope.el.userDropDown.dropdown('clear');
                $scope.el.productsDropDown.dropdown('clear');
                $scope.el.customerDropDown.dropdown('clear');
                $scope.query = {
                    ajax: true,
                    page: 0,
                    filter: null,
                    search: null,
                    customerId: null,
                    productId: null,
                    salesRepId: null,
                    userId: null
                };
                $scope.filterd = false;
                $scope.searchEstimates = '';
                $scope.fetchEstimations();
            };
            $scope.el.repDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: $scope.urls.rep + '/{query}',
                    cache: false
                },
                onChange: function (val) {
                    $scope.filterd = true;
                    $scope.query.salesRepId = val;
                    if (val) $scope.fetchEstimations();
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
                    $scope.filterd = true;
                    $scope.query.userId = val;
                    if (val) $scope.fetchEstimations();
                }
            });

            $scope.el.customerDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: $scope.urls.customer + '/{query}',
                    cache: false
                },
                onChange: function (val) {
                    $scope.filterd = true;
                    $scope.query.customerId = val;
                    if (val) $scope.fetchEstimations();
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
                    $scope.filterd = true;
                    $scope.query.productId = val;
                    if (val) $scope.fetchEstimations();
                }
            });
            /** get drafted estimates count */
            var draftedEstimateRoute = '{{ route('sales.summary.index', [
                'model' => 'Estimate', 'take' => 'null', 'with' => 'null', 'where' => 'Draft', 'field' => 'status']) }}';
            $http.get(draftedEstimateRoute + '?ajax=true').then(function (response) {
                $scope.draftedEstimateCount = response.data ? response.data.count : 0;
            });

            /** get sent estimates count */
            var sentEstimateRoute = '{{ route('sales.summary.index', [
                'model' => 'Estimate', 'take' => 'null', 'with' => 'null', 'where' => 'Sent', 'field' => 'status']) }}';
            $http.get(sentEstimateRoute + '?ajax=true').then(function (response) {
                $scope.sentEstimateCount = response.data ? response.data.count : 0;
            });

            /** get accepted estimates count */
            var acceptedEstimateRoute = '{{ route('sales.summary.index', [
                'model' => 'Estimate', 'take' => 'null', 'with' => 'null', 'where' => 'Accepted', 'field' => 'status']) }}';
            $http.get(acceptedEstimateRoute + '?ajax=true').then(function (response) {
                $scope.acceptedEstimateCount = response.data ? response.data.count : 0;
            });

            /** get declined estimates count */
            var declinedEstimateRoute = '{{ route('sales.summary.index', [
                'model' => 'Estimate', 'take' => 'null', 'with' => 'null', 'where' => 'Declined', 'field' => 'status']) }}';
            $http.get(declinedEstimateRoute + '?ajax=true').then(function (response) {
                $scope.declinedEstimateCount = response.data ? response.data.count : 0;
            });

            /** get ordered estimates count */
            var orderedEstimateRoute = '{{ route('sales.summary.index', [
                'model' => 'Estimate', 'take' => 'null', 'with' => 'null', 'where' => 'Ordered', 'field' => 'status']) }}';
            $http.get(orderedEstimateRoute + '?ajax=true').then(function (response) {
                $scope.orderedEstimateCount = response.data ? response.data.count : 0;
            });

            $scope.getCusLogo = function (estimate) {
                var route = '{{ route('sales.customer.logo', ['customer' => 'CUSTOMER']) }}';
                return route.replace('CUSTOMER', estimate.customer_id)
            };
        }]);
    </script>
@endsection