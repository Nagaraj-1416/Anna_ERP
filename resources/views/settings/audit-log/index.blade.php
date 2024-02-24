@extends('layouts.master')
@section('title', 'Audit Logs')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row" ng-controller="AuditLogController">
        <div class="col-12">
            <div class="card">
                <!-- .left-right-aside-column-->
                <div class="contact-page-aside">
                    <!-- .left-aside-column-->
                    <div class="left-aside">
                        {{--<ul class="list-style-none">--}}
                        {{--<li class="text-muted m-t-20">FILTER ROUTE BY</li>--}}
                        {{--<li class="divider"></li>--}}
                        {{--<li ng-class="{'active': !query.filter}"--}}
                        {{--ng-click="filterUpdate()"><a--}}
                        {{--href="">All Routes</a></li>--}}
                        {{--<li ng-class="{'active': query.filter === 'Active'}"--}}
                        {{--ng-click="filterUpdate('Active')"><a href="">Active Routes</a></li>--}}
                        {{--<li ng-class="{'active': query.filter === 'Inactive'}"--}}
                        {{--ng-click="filterUpdate('Inactive')"><a href="">Inactive Routes</a></li>--}}
                        {{--<li ng-class="{'active': query.filter === 'recentlyCreated'}"--}}
                        {{--ng-click="filterUpdate('recentlyCreated')"><a href="">Recently Created</a></li>--}}
                        {{--<li ng-class="{'active': query.filter === 'recentlyUpdated'}"--}}
                        {{--ng-click="filterUpdate('recentlyUpdated')"><a href="">Recently Modified</a></li>--}}
                        {{--</ul>--}}
                        {{--<hr>--}}
                        {{--<ul class="list-style-none">--}}
                        {{--<li class="text-muted" ng-click="resetFilters()">--}}
                        {{--<a class="text-primary" href="">Reset Filters</a>--}}
                        {{--</li>--}}
                        {{--</ul>--}}
                    </div>
                    <!-- /.left-aside-column-->
                    <div class="right-aside custom-right-aside">
                        <div class="right-page-header">
                            <div class="d-flex m-b-10">
                                <div class="align-self-center">
                                    <h2 class="card-title m-t-10">Audit logs @{{ total ? ("(" + total +")") :
                                        '' }}</h2>
                                </div>
                                <div class="ml-auto">
                                    <input type="text" id="demo-input-search2" ng-model="searchLogs"
                                           placeholder="search for log here" class="form-control"
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
                                <p>loading logs</p>
                            </div>
                        </div>
                        <div class="row" ng-hide="loading">
                            <div class="col-md-12 m-b-20">
                                <table id="demo-foo-addrow" class="table m-t-10 table-hover no-wrap contact-list" data-page-size="10">
                                    <thead>
                                    <tr>
                                        <th>Audit details</th>
                                        <th>Causer</th>
                                        <th>IP address</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-muted">
                                    <tr ng-repeat="log in logs">
                                        <td style="vertical-align: middle;">
                                            <a target="_blank" href="/setting/audit-logs/@{{ log.id }}">
                                                @{{ log.date }}
                                            </a><br />
                                            <small>
                                                @{{ log.action }}
                                            </small>
                                        </td>
                                        <td>
                                            @{{ log.causer.name ? log.causer.name : 'None'}}
                                        </td>
                                        <td>@{{ log.ip }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            {{--<div class="col-md-3 col-sm-6 col-xs-12"
                                 ng-repeat="log in logs">
                                <div class="card card-body">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <h5 class="box-title m-b-0">
                                                <a target="_blank" href="/setting/audit-logs/@{{ log.id }}">@{{ log.date
                                                    }}</a>
                                            </h5>
                                            <small>@{{ log.action }}</small>
                                            <br>
                                            <small><b>User : </b> @{{ log.causer.name ? log.causer.name : 'None'}}
                                            </small>
                                            <br>
                                            <small><b>IP : </b> @{{ log.ip }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>--}}
                            <!-- pagination panel -->
                            <div class="col-md-12" ng-show="checkPagination()">
                                @include('general.pagination.pagination')
                            </div>
                        </div>
                        <div class="row" ng-hide="loading" ng-if="logs.length === 0">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">There are <code>no</code> logs found.</p>
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
@section('script')
    <script>
        app.controller('AuditLogController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('setting.audit.log.index') }}';
            $scope.logs = [];
            $scope.filterd = false;
            $scope.loading = true;
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
                $scope.query.search = $scope.searchLogs;
                $scope.fetchRoutes();
            };

            $scope.filterUpdate = function (filter) {
                $scope.query.filter = filter ? filter : '';
                $scope.filterd = true;
                $scope.fetchRoutes();
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
                $scope.fetchRoutes();
            };

            $scope.fetchRoutes = function () {
                $scope.loading = true;
                $scope.query.page = $scope.currentPaginationPage + 1;
                var queryRoute = $.param($scope.query);
                $http.get(moduleRoute + '?' + queryRoute).then(function (response) {
                    $scope.loading = false;
                    $scope.logs = response.data.data;
                    console.log($scope.logs);
                    $scope.pagination = response.data;
                    $scope.total = response.data.total;
                    $scope.range();
                });
            };
            $scope.fetchRoutes();

            $scope.resetFilters = function () {
                $scope.query = {
                    ajax: true,
                    page: 0,
                    filter: '',
                    search: ''
                };
                $scope.searchRoutes = '';
                $scope.filterd = false;
                $scope.fetchRoutes();
            };

            $scope.checkPagination = function () {
                return $scope.total > $scope.pagination.per_page;
            }
        }]);
    </script>
@endsection