@extends('layouts.master')
@section('title', 'Credits')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Purchase') !!}
@endsection
@section('content')
    <div class="row" ng-controller="CreditController">
        <div class="col-12">
            <div class="card">
                <!-- .left-right-aside-column-->
                <div class="contact-page-aside">
                    <!-- .left-aside-column-->
                    <div class="left-aside">
                        @can('create', new \App\SupplierCredit())
                            <a target="_blank" href="{{ route('purchase.credit.create') }}"
                               class="btn btn-info btn-block">
                                <i class="fa fa-plus"></i> Add New Credit
                            </a>
                        @endcan
                        <ul class="list-style-none">
                            <li class="text-muted m-t-20">FILTER CREDIT BY</li>
                            <li class="divider"></li>
                            <li ng-class="{'active': !query.filter}"
                                ng-click="filterUpdate()"><a
                                        href="">All Credits</a></li>
                            <li ng-class="{'active': query.filter === 'Open'}"
                                ng-click="filterUpdate('Open')"><a href="">Open Credits</a></li>
                            <li ng-class="{'active': query.filter === 'Closed'}"
                                ng-click="filterUpdate('Closed')"><a href="">Closed Credits</a></li>
                            <li ng-class="{'active': query.filter === 'Canceled'}"
                                ng-click="filterUpdate('Canceled')"><a href="">Canceled Credits</a></li>
                            <li ng-class="{'active': query.filter === 'recentlyCreated'}"
                                ng-click="filterUpdate('recentlyCreated')"><a href="">Recently Created</a></li>
                            <li ng-class="{'active': query.filter === 'recentlyUpdated'}"
                                ng-click="filterUpdate('recentlyUpdated')"><a href="">Recently Modified</a></li>
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
                                    <h2 class="card-title m-t-10">Credits @{{ total ? ("(" + total +")") :
                                        '' }}</h2>
                                </div>
                                <div class="ml-auto">
                                    <input type="text" id="demo-input-search2" ng-model="searchCredits"
                                           placeholder="search for credits here" class="form-control"
                                           ng-change="filterUpdated()">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-inverse card-primary cursor pointer"
                                     ng-click="filterUpdate('Open')">
                                    <div class="box bg-primary text-center">
                                        <h1 class="font-light text-white">@{{ openCreditCount }}</h1>
                                        <h6 class="text-white">Open</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-inverse card-success cursor pointer"
                                     ng-click="filterUpdate('Closed')">
                                    <div class="box bg-success text-center">
                                        <h1 class="font-light text-white">@{{ closedCreditCount }}</h1>
                                        <h6 class="text-white">Closed</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card cursor pointer"
                                     ng-click="filterUpdate('Canceled')">
                                    <div class="box bg-danger text-center">
                                        <h1 class="font-light text-white">@{{ canceledCreditCount }}</h1>
                                        <h6 class="text-white">Canceled</h6>
                                    </div>
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
                                <p>loading credits</p>
                            </div>
                        </div>
                        <div class="row" ng-hide="loading">
                            <div class="col-md-3 col-sm-6 col-xs-12"
                                 ng-repeat="credit in credits ">
                                <div class="card card-body">
                                    <div class="row">
                                        {{--<div class="col-md-3 text-center">--}}
                                        {{--<img src="@{{ getCusLogo(credit) }}" alt="img" class="img-responsive">--}}
                                        {{--</div>--}}
                                        <div class="col-md-9">
                                            <h3 class="box-title m-b-0">
                                                <a target="_blank" href="credit/@{{ credit.id }}">@{{ credit.code
                                                    }}</a>
                                            </h3>
                                            <small>@{{ credit.supplier.display_name }} | @{{credit.supplier.mobile}}
                                            </small>
                                            <p class="text-muted">
                                                <small><b>Credit Status:</b> @{{ credit.status }}</small>
                                            </p>
                                            @{{ credit.date }} | @{{ credit.amount | number }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- pagination panel -->
                            <div class="col-md-12" ng-show="credits.length > pagination.pre_page">
                                @include('general.pagination.pagination')
                            </div>
                        </div>
                        <div class="row" ng-hide="loading" ng-if="credits.length === 0 && !filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">You haven't added any credits yet, click below button to
                                            add.</p>
                                        <a target="_blank" href="{{ route('purchase.credit.create') }}"
                                           class="btn btn-info">
                                            <i class="fa fa-plus"></i> Add New Credit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- if there no credit available message -->
                        <div class="row" ng-hide="loading" ng-if="credits.length === 0 && filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">There are <code>no</code> credits found.</p>
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
    <script>
        app.controller('CreditController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('purchase.credit.index') }}';
            $scope.credits = [];
            $scope.filterd = false;
            $scope.loading = true;
            $scope.pagination = {};
            $scope.currentPaginationPage = 0;
            $scope.query = {
                ajax: true,
                page: null,
                filter: null,
                search: null,
                supplierId: null,
                userId: null,
            };
            $scope.el = {
                userDropDown: $('.user-drop-down'),
                supplierDropDown: $('.supplier-drop-down')
            };
            $scope.urls = {
                user: '{{ route('setting.user.search') }}',
                supplier: '{{ route('purchase.supplier.search') }}',
            };
            $scope.filterUpdated = function () {
                $scope.filterd = true;
                $scope.query.search = $scope.searchCredits;
                $scope.fetchCredits();
            };

            $scope.filterUpdate = function (filter) {
                $scope.query.filter = filter ? filter : '';
                $scope.filterd = true;
                $scope.fetchCredits();
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
                $scope.fetchCredits();
            };

            $scope.fetchCredits = function () {
                $scope.loading = true;
                $scope.query.page = $scope.currentPaginationPage + 1;
                var queryRoute = $.param($scope.query);
                $http.get(moduleRoute + '?' + queryRoute).then(function (response) {
                    $scope.loading = false;
                    $scope.credits = response.data.data;
                    $scope.pagination = response.data;
                    $scope.total = response.data.total;
                    $scope.range();
                });
            };
            $scope.fetchCredits();
            $scope.resetFilters = function () {
                $scope.el.userDropDown.dropdown('clear');
                $scope.el.supplierDropDown.dropdown('clear');
                $scope.filterd = false;
                $scope.query = {
                    ajax: true,
                    page: 0,
                    filter: '',
                    search: '',
                    supplierId: null,
                    userId: null,
                };
                $scope.fetchCredits();
                $scope.searchCredits = '';
                if (!$scope.$$phase) $scope.$apply()
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
                    $scope.filterd = true;
                    if (val) {
                        $scope.fetchCredits();
                    }
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
                    $scope.query.supplierId = val;
                    $scope.filterd = true;
                    if (val) {
                        $scope.fetchCredits();
                    }
                }
            });
            /** get open credit count */
            var openCreditRoute = '{{ route('purchase.summary.index', [
                'model' => 'SupplierCredit', 'take' => 'null', 'with' => 'null', 'where' => 'Open', 'field' => 'status']) }}';
            $http.get(openCreditRoute + '?ajax=true').then(function (response) {
                $scope.openCreditCount = response.data ? response.data.count : 0;
            });

            /** get closed credit count */
            var closedCreditRoute = '{{ route('purchase.summary.index', [
                'model' => 'SupplierCredit', 'take' => 'null', 'with' => 'null', 'where' => 'Closed', 'field' => 'status']) }}';
            $http.get(closedCreditRoute + '?ajax=true').then(function (response) {
                $scope.closedCreditCount = response.data ? response.data.count : 0;
            });

            var canceledCreditRoute = '{{ route('sales.summary.index', [
                'model' => 'SupplierCredit', 'take' => 'null', 'with' => 'null', 'where' => 'Canceled', 'field' => 'status']) }}';
            $http.get(canceledCreditRoute + '?ajax=true').then(function (response) {
                $scope.canceledCreditCount = response.data ? response.data.count : 0;
            });
        }]);
    </script>
@endsection