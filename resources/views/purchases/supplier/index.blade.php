@extends('layouts.master')
@section('title', 'Suppliers')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Purchase') !!}
@endsection
@section('content')
    <div class="row" ng-controller="SupplierController">
        <div class="col-12">
            <div class="card">
                <!-- .left-right-aside-column-->
                <div class="contact-page-aside">
                    <!-- .left-aside-column-->
                    <div class="left-aside">
                        @can('create', new \App\Supplier())
                            <a target="_blank" href="{{ route('purchase.supplier.create') }}"
                               class="btn btn-info btn-block">
                                <i class="fa fa-plus"></i> Add New Supplier
                            </a>
                        @endcan
                        <ul class="list-style-none">
                            <li class="text-muted m-t-20">FILTER SUPPLIERS BY</li>
                            <li class="divider"></li>
                            <li ng-class="{'active': !filterd}" ng-click="fetchSuppliers()"><a href="">All Suppliers</a>
                            </li>
                            <li ng-class="{'active': filterd === 'filter=top10'}"
                                ng-click="fetchSuppliers('filter=top10')"><a href="">Top 10 Suppliers</a></li>
                            <li ng-class="{'active': filterd === 'filter=active'}"
                                ng-click="fetchSuppliers('filter=active')"><a href="">Active Suppliers</a></li>
                            <li ng-class="{'active': filterd === 'filter=inActive'}"
                                ng-click="fetchSuppliers('filter=inActive')"><a href="">Inactive Suppliers</a></li>
                            <li ng-class="{'active': filterd === 'filter=recentlyCreated'}"
                                ng-click="fetchSuppliers('filter=recentlyCreated')"><a href="">Recently Created</a></li>
                            <li ng-class="{'active': filterd === 'filter=recentlyModified'}"
                                ng-click="fetchSuppliers('filter=recentlyModified')"><a href="">Recently Modified</a>
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
                                    <h3 class="card-title m-t-10">Suppliers
                                        @{{ pagination.total ? ("(" + pagination.total +")") :
                                        '' }}</h3>
                                </div>
                                <div class="ml-auto">
                                    <input type="text" id="demo-input-search2" ng-model="searchSuppliers"
                                           placeholder="search for suppliers here" class="form-control"
                                           ng-change="filterUpdated()">
                                </div>
                            </div>
                        </div>
                        <div class="row">
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
                                        <h6 class="text-white">Overdue Bills</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                <div class="card card-inverse card-info">
                                    <div class="box text-center">
                                        <h1 class="font-light text-white">0</h1>
                                        <h6 class="text-white">Open Bills</h6>
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
                                <p>loading suppliers</p>
                            </div>
                        </div>
                        <div class="row" ng-hide="loading">
                            <div class="col-md-3 col-sm-6 col-xs-12"
                                 ng-repeat="(key, supplier) in suppliers">
                                <div class="card card-body">
                                    <div class="row">
                                        <div class="col-md-3 text-center">
                                            <img ng-src="@{{ getSupLogo(supplier) }}" alt="img" class="img-responsive">
                                        </div>
                                        <div class="col-md-9">
                                            <h4 class="box-title m-b-0">
                                                <a target="_blank" href="supplier/@{{ supplier.id }}">@{{
                                                    supplier.display_name }}</a>
                                            </h4>
                                            <small>@{{ supplier.full_name }} | @{{ supplier.code }}</small>
                                            <p class="text-muted">
                                                <small><b>Email:</b> @{{ supplier.email }}</small>
                                                <br/>
                                                <small><b>Total POs:</b> 0</small>
                                            </p>
                                            <address>
                                                <abbr title="Phone">P:</abbr> @{{ supplier.mobile }}
                                                <abbr title="Phone">T:</abbr> @{{ supplier.phone }}
                                            </address>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- pagination panel -->
                            <div class="col-md-12" ng-show="suppliers.length">
                                @include('general.pagination.pagination')
                            </div>
                        </div>
                        <div class="row" ng-hide="loading" ng-if="suppliers.length === 0 && !filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">You haven't added any supplier yet, click below button to
                                            add.</p>
                                        <a href="{{ route('purchase.supplier.create') }}" class="btn btn-info"> <i
                                                    class="fa fa-plus"></i> Add New Supplier</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- if there no customers available message -->
                        <div class="row" ng-hide="loading" ng-if="suppliers.length === 0 && filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">There are <code>no</code> suppliers found.</p>
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
        app.controller('SupplierController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('purchase.supplier.index') }}';
            $scope.suppliers = [];
            $scope.loading = true;
            $scope.pagination = {};
            $scope.filterd = false;
            $scope.currentPaginationPage = 0;
            $scope.filterUpdated = function () {
                $scope.fetchSuppliers('', 'search=' + $scope.searchSuppliers);
            };

            $scope.getClass = function (filter) {
                if ($scope.filterd === filter) return 'active';
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
                $scope.fetchSuppliers();
            };
            $scope.fetchSuppliers = function (param, search) {
                if (!param) param = '';
                if (!search) search = '';
                $scope.loading = true;
                var page = $scope.currentPaginationPage + 1;
                if (search) {
                    page = 1;
                }
                if (param || search) {
                    if (param) {
                        $scope.filterd = param;
                    } else {
                        $scope.filterd = true;
                    }
                } else {
                    $scope.filterd = false;
                }
                $http.get(moduleRoute + '?ajax=true&page=' + page + '&' + param + '&' + search).then(function (response) {
                    $scope.loading = false;
                    $scope.suppliers = response.data.data;
                    $scope.pagination = response.data;
                    $scope.range();
                });
            };

            $scope.resetFilters = function () {
                $scope.query = {
                    ajax: true,
                    page: 0,
                    filter: '',
                    search: ''
                };
                $scope.searchSuppliers = '';
                $scope.filterd = false;
                $scope.fetchSuppliers();
            };

            $scope.fetchSuppliers();

            $scope.getSupLogo = function (supplier) {
                var route = '{{ route('purchase.supplier.logo', ['supplier' => 'SUPPLIER']) }}';
                return route.replace('SUPPLIER', supplier.id)
            };
        }]);
    </script>
@endsection