@extends('layouts.master')
@section('title', 'Sales Locations')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row" ng-controller="SalesLocationController">
        <div class="col-12">
            <div class="card">
                <!-- .left-right-aside-column-->
                <div class="contact-page-aside">
                    <!-- .left-aside-column-->
                    <div class="left-aside">
                        <a target="_blank" href="{{ route('setting.sales.location.create') }}" class="btn btn-info btn-block">
                            <i class="fa fa-plus"></i> Add New Location
                        </a>
                        <ul class="list-style-none">
                            <li class="text-muted m-t-20">FILTER LOCATION BY</li>
                            <li class="divider"></li>
                            <li ng-class="{'active': !query.filter}"
                                ng-click="filterUpdate()"><a
                                        href="">All Locations</a></li>
                            <li ng-class="{'active': query.filter === 'Active'}"
                                ng-click="filterUpdate('Active')"><a href="">Active Locations</a></li>
                            <li ng-class="{'active': query.filter === 'Inactive'}"
                                ng-click="filterUpdate('Inactive')"><a href="">Inactive Locations</a></li>
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
                                    <h2 class="card-title m-t-10">Locations @{{ total ? ("(" + total +")") :
                                        '' }}</h2>
                                </div>
                                <div class="ml-auto">
                                    <input type="text" id="demo-input-search2" ng-model="searchLocations"
                                           placeholder="search for sales location here" class="form-control"
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
                                <p>loading stores</p>
                            </div>
                        </div>
                        <div class="row" ng-hide="loading">
                            <div class="col-md-12 m-b-20">
                                <table id="demo-foo-addrow" class="table m-t-10 table-hover no-wrap contact-list" data-page-size="10">
                                    <thead>
                                    <tr>
                                        <th>Location details</th>
                                        <th>Company</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-muted">
                                    <tr ng-repeat="location in locations">
                                        <td style="vertical-align: middle;">
                                            <a target="_blank" href="/setting/sales-location/@{{ location.id }}">
                                                @{{ location.name }}
                                            </a><br />
                                            <small>
                                                @{{  location.code }} |
                                                <i class="mdi mdi-email"></i> @{{ location.email }}
                                                <i class="mdi mdi-phone-classic"></i> @{{ location.phone }}
                                                <i class="mdi mdi-cellphone"></i> @{{ location.mobile }}
                                                <i class="mdi mdi-fax"></i> @{{ location.fax }}
                                            </small>
                                        </td>
                                        <td>@{{ location.company.name }}</td>
                                        <td>@{{ location.type }}</td>
                                        <td>
                                            <span class="label label-success" ng-if="location.is_active == 'Yes'">Active</span>
                                            <span class="label label-danger" ng-if="location.is_active == 'No'">Inactive</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="location/@{{ location.id }}/edit">
                                                <i class="ti-pencil" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- pagination panel -->
                            <div class="col-md-12" ng-show="checkPagination()">
                                @include('general.pagination.pagination')
                            </div>
                        </div>
                        <div class="row" ng-hide="loading" ng-if="locations.length === 0 && !filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">You haven't added any sales location yet, click on "Add New Location" button to add location.</p>
                                        <a target="_blank" href="{{ route('setting.sales.location.create') }}"
                                           class="btn btn-info">
                                            <i class="fa fa-plus"></i> Add New Location
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- if there no credit available message -->
                        <div class="row" ng-hide="loading" ng-if="locations.length === 0 && filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">There are <code>no</code> sales locations found.</p>
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
        app.controller('SalesLocationController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('setting.sales.location.index') }}';
            $scope.locations = [];
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
                $scope.query.search = $scope.searchLocations;
                $scope.fetchLocations();
            };

            $scope.filterUpdate = function (filter) {
                $scope.query.filter = filter ? filter : '';
                $scope.filterd = true;
                $scope.fetchLocations();
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
                $scope.fetchLocations();
            };

            $scope.fetchLocations = function () {
                $scope.loading = true;
                $scope.query.page = $scope.currentPaginationPage + 1;
                var queryRoute = $.param($scope.query);
                $http.get(moduleRoute + '?' + queryRoute).then(function (response) {
                    $scope.loading = false;
                    $scope.locations = response.data.data;
                    $scope.pagination = response.data;
                    $scope.total = response.data.total;
                    $scope.range();
                });
            };
            $scope.fetchLocations();

            $scope.resetFilters = function () {
                $scope.query = {
                    ajax: true,
                    page: 0,
                    filter: '',
                    search: ''
                };
                $scope.searchLocations = '';
                $scope.filterd = false;
                $scope.fetchLocations();
            };

            $scope.checkPagination = function () {
                return $scope.total > $scope.pagination.per_page;
            }
        }]);
    </script>
@endsection