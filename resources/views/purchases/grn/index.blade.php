@extends('layouts.master')
@section('title', 'GRNs')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Purchase') !!}
@endsection
@section('content')
    <div class="row" ng-controller="GrnController">
        <div class="col-12">
            <div class="card">
                <!-- .left-right-aside-column-->
                <div class="contact-page-aside">
                    <!-- .left-aside-column-->
                    <div class="left-aside">
                        {{--<a target="_blank" href="{{ route('purchase.grn.create') }}" class="btn btn-info btn-block">
                            <i class="fa fa-plus"></i> Add New GRN
                        </a>--}}
                        <ul class="list-style-none">
                            <li class="text-muted m-t-20">FILTER GRNS BY</li>
                            <li class="divider"></li>
                            <li ng-class="{'active': !query.filter}" ng-click="filterUpdate()"><a
                                        href="">All GRNs</a></li>
                            <li ng-class="{'active': query.filter === 'drafted'}"
                                ng-click="filterUpdate('drafted')"><a href="">Drafted GRNs</a></li>
                            <li ng-class="{'active': query.filter === 'sent'}" ng-click="filterUpdate('open')">
                                <a href="">Sent GRNs</a></li>
                            <li ng-class="{'active': query.filter === 'partiallyReceived'}"
                                ng-click="filterUpdate('partiallyReceived')"><a href="">Partially Received</a></li>
                            <li ng-class="{'active': query.filter === 'received'}"
                                ng-click="filterUpdate('received')"><a href="">Received</a></li>
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
                                    <h2 class="card-title m-t-10">GRNs
                                        @{{ pagination.total ? ("(" + pagination.total +")") :
                                        '' }}</h2>
                                </div>
                                <div class="ml-auto">
                                    <input type="text" id="demo-input-search2" ng-model="searchGrns"
                                           placeholder="search for GRNs here" class="form-control"
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
                                <p>loading goods receipt notes</p>
                            </div>
                        </div>
                        <div class="row" ng-hide="loading">
                            <div class="col-md-12 m-b-20">
                                <table id="demo-foo-addrow" class="table m-t-10 table-hover no-wrap contact-list"
                                       data-page-size="10">
                                    <thead>
                                    <tr>
                                        <th colspan="2">GRN Details</th>
                                        <th>Supplier</th>
                                        <th>Store</th>
                                        <th>Status</th>
                                        <th class="text-right">Amount</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-muted">
                                    <tr ng-repeat="grn in grns">
                                        <td style="width: 3%;">
                                            <img src="@{{ getSupLogo(order) }}" alt="user" class="img-circle"/>
                                        </td>
                                        <td>
                                            <a target="_blank" href="/purchase/grn/@{{ grn.id }}">
                                                @{{ grn.code }}
                                            </a><br/>
                                            <small>
                                                <i class="mdi mdi-calendar"></i> @{{ grn.created_at | date }}
                                            </small>
                                        </td>
                                        <td>
                                            <a target="_blank" href="/purchase/supplier/@{{ order.supplier.id }}">
                                                @{{ grn.supplier.display_name }}
                                            </a>
                                        </td>
                                        <td>
                                            @{{ grn.store.name }}
                                        </td>
                                        <td>
                                            <span ng-class="grn.status == 'Received' ? 'text-green' : '' ||
                                                grn.status == 'Sent' ? 'text-warning' : '' || grn.status == 'Drafted' ? 'text-danger' : ''">
                                                <i ng-if="grn.status == 'Received'"
                                                   class="ti-check"></i>
                                                <i ng-if="grn.status == 'Sent'"
                                                   class="ti-truck"></i>
                                                @{{ grn.status }}
                                            </span>
                                        </td>
                                        <td class="text-right">@{{ grn.total | number:2 }}</td>
                                        <td class="text-center">
                                            <a title="View order details" class="p-10"
                                               href="/purchase/grn/@{{ grn.id }}">
                                                <i class="ti-eye" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- pagination panel -->
                            <div class="col-md-12" ng-show="grns.length">
                                @include('general.pagination.pagination')
                            </div>
                        </div>
                        <div class="row" ng-hide="loading" ng-if="!filterd && grns.length === 0">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">You haven't added any GRNs yet, please visit received POs and create.</p>
                                        {{--<a target="_blank" href="{{ route('purchase.order.create') }}"
                                           class="btn btn-info">
                                            <i class="fa fa-plus"></i> Add New GRN
                                        </a>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- if there no order available message -->
                        <div class="row" ng-hide="loading"
                             ng-if="filterd  && grns.length === 0">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">There are <code>no</code> GRNs found.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- .left-aside-column-->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('style')
@endsection
@section('script')
    <script>
        app.controller('GrnController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('purchase.grn.index') }}';
            $scope.grns = [];
            $scope.loading = true;
            $scope.filterd = false;
            $scope.pagination = {};
            $scope.currentPaginationPage = 0;

            $scope.query = {
                ajax: true,
                page: null,
                filter: null,
                search: null
            };

            $scope.filterUpdated = function () {
                $scope.filterd = true;
                $scope.query.search = $scope.searchGrns;
                $scope.fetchGrns();
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
                $scope.fetchGrns();
            };

            $scope.filterUpdate = function (filter) {
                $scope.query.filter = filter ? filter : '';
                $scope.filterd = true;
                $scope.fetchGrns();
            };

            $scope.fetchGrns = function () {
                $scope.loading = true;
                $scope.query.page = $scope.currentPaginationPage + 1;
                var routeParam = $.param($scope.query);
                $http.get(moduleRoute + '?' + routeParam).then(function (response) {
                    $scope.loading = false;
                    $scope.grns = response.data.data;
                    $scope.pagination = response.data;
                    $scope.range();
                });
            };
            $scope.fetchGrns();

            $scope.resetFilters = function () {
                $scope.filterd = false;
                $scope.query = {
                    ajax: true,
                    page: 0,
                    filter: '',
                    search: ''
                };

                $scope.searchGrns = '';
                $scope.fetchGrns();
            };

            $scope.getSupLogo = function (grn) {
                var route = '{{ route('purchase.supplier.logo', ['supplier' => 'SUPPLIER']) }}';
                return route.replace('SUPPLIER', grn.supplier_id)
            };
        }]);
    </script>
@endsection