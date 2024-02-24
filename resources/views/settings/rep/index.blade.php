@extends('layouts.master')
@section('title', 'Sales Reps')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row" ng-controller="RepController">
        <div class="col-12">
            <div class="card">
                <!-- .left-right-aside-column-->
                <div class="contact-page-aside">
                    <!-- .left-aside-column-->
                    <div class="left-aside">
                        <a target="_blank" href="{{ route('setting.staff.create') }}" class="btn btn-info btn-block">
                            <i class="fa fa-plus"></i> Add New Rep
                        </a>
                        <ul class="list-style-none">
                            <li class="text-muted m-t-20">FILTER REP BY</li>
                            <li class="divider"></li>
                            <li ng-class="{'active': !query.filter}"
                                ng-click="filterUpdate()"><a
                                        href="">All Reps</a></li>
                            <li ng-class="{'active': query.filter === 'Active'}"
                                ng-click="filterUpdate('Active')"><a href="">Active Reps</a></li>
                            <li ng-class="{'active': query.filter === 'Inactive'}"
                                ng-click="filterUpdate('Inactive')"><a href="">Inactive Reps</a></li>
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
                                    <h2 class="card-title m-t-10">Reps @{{ total ? ("(" + total +")") :
                                        '' }}</h2>
                                </div>
                                <div class="ml-auto">
                                    <input type="text" id="demo-input-search2" ng-model="searchReps"
                                           placeholder="search for rep here" class="form-control"
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
                                <p>loading reps</p>
                            </div>
                        </div>
                        <div class="row" ng-hide="loading">
                            <div class="col-md-12 m-b-20">
                                <table id="demo-foo-addrow" class="table m-t-10 table-hover no-wrap contact-list" data-page-size="10">
                                    <thead>
                                    <tr>
                                        <th colspan="2">Rep details</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-muted">
                                    <tr ng-repeat="rep in reps">
                                        <td style="width: 3%">
                                            <img src="@{{ getStaffImage(rep.staff) }}" alt="user" class="img-circle" />
                                        </td>
                                        <td style="vertical-align: middle;">
                                            <a target="_blank" href="/setting/sales-rep/@{{ rep.id }}">
                                                @{{ rep.name }}
                                            </a><br />
                                            <small>
                                                <i class="mdi mdi-email"></i> @{{ rep.staff.email }}
                                                <i class="mdi mdi-phone"></i> @{{ rep.staff.phone }}
                                                <i class="mdi mdi-cellphone"></i> @{{ rep.staff.phone }}
                                            </small>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            {{--<div class="col-md-3 col-sm-6 col-xs-12"
                                 ng-repeat="rep in reps">
                                <div class="card card-body">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <h3 class="box-title m-b-0">
                                                <a target="_blank" href="sales-rep/@{{ rep.id }}">@{{ rep.code
                                                    }}</a>
                                            </h3>
                                            <small>@{{ rep.name }}</small>
                                            <p class="text-muted">
                                                <small><b>Email:</b> @{{ rep.staff.email }}</small><br />
                                                <small><b>Phone:</b> @{{ rep.staff.phone }}</small><br />
                                                <small><b>Mobile:</b> @{{ rep.staff.mobile }}</small><br />
                                                <small><b>Designation:</b> @{{ rep.staff.designation }}</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>--}}
                            <!-- pagination panel -->
                            <div class="col-md-12" ng-show="checkPagination()">
                                @include('general.pagination.pagination')
                            </div>
                        </div>
                        <div class="row" ng-hide="loading" ng-if="reps.length === 0 && !filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">You haven't added any rep yet, visit to staff module and add new rep</p>
                                        <a target="_blank" href="{{ route('setting.staff.create') }}"
                                           class="btn btn-info">
                                            <i class="fa fa-plus"></i> Add New Rep
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- if there no route available message -->
                        <div class="row" ng-hide="loading" ng-if="reps.length === 0 && filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">There are <code>no</code> reps found.</p>
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
        app.controller('RepController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('setting.rep.index') }}';
            $scope.reps = [];
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
                $scope.query.search = $scope.searchReps;
                $scope.fetchReps();
            };

            $scope.filterUpdate = function (filter) {
                $scope.query.filter = filter ? filter : '';
                $scope.filterd = true;
                $scope.fetchReps();
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
                $scope.fetchReps();
            };

            $scope.fetchReps = function () {
                $scope.loading = true;
                $scope.query.page = $scope.currentPaginationPage + 1;
                var queryRoute = $.param($scope.query);
                $http.get(moduleRoute + '?' + queryRoute).then(function (response) {
                    $scope.loading = false;
                    $scope.reps = response.data.data;
                    $scope.pagination = response.data;
                    $scope.total = response.data.total;
                    $scope.range();
                });
            };
            $scope.fetchReps();

            $scope.resetFilters = function () {
                $scope.query = {
                    ajax: true,
                    page: 0,
                    filter: '',
                    search: ''
                };
                $scope.searchReps = '';
                $scope.filterd = false;
                $scope.fetchReps();
            };

            $scope.checkPagination = function () {
                return $scope.total > $scope.pagination.per_page;
            };

            $scope.getStaffImage = function (staff) {
                var route = '{{ route('setting.staff.image', ['staff' => 'STAFF']) }}';
                return route.replace('STAFF', staff.id)
            };
        }]);
    </script>
@endsection