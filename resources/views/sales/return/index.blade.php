@extends('layouts.master')
@section('title', 'Returns')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row" ng-controller="ReturnController">
        <div class="col-12">
            <div class="card">
                <!-- .left-right-aside-column-->
                <div class="contact-page-aside">
                    <!-- .left-aside-column-->
                    <div class="left-aside">
                        {{--<a target="_blank" href="#"
                           class="btn btn-info btn-block">
                            <i class="fa fa-plus"></i> Add New return
                        </a>--}}
                        <ul class="list-style-none">
                            <li class="text-muted m-t-20">FILTER RETURNS BY</li>
                            <li class="divider"></li>
                            <li ng-class="{'active': !query.filter}"
                                ng-click="filterUpdate()" class="active"><a href="">All Returns</a>
                            </li>
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
                                    <h2 class="card-title m-t-10">Returns @{{ total ? ("(" + total +")")
                                        :
                                        '' }}</h2>
                                </div>
                                <div class="ml-auto">
                                    <input type="text" id="demo-input-search2" ng-model="searchReturns"
                                           ng-change="filterUpdated()" placeholder="search for returns here"
                                           class="form-control">
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
                                <p>loading returns</p>
                            </div>
                        </div>
                        <div class="row" ng-hide="loading">
                            <div class="col-md-12 m-b-20">
                                <table id="demo-foo-addrow" class="table m-t-10 table-hover no-wrap contact-list"
                                       data-page-size="10">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>No of items</th>
                                        <th>Allocation</th>
                                        <th>Company</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-muted">
                                    <tr ng-repeat="return in returns">
                                        <td>
                                            <a target="_blank" href="/sales/return/@{{ return.id }}">
                                                @{{ return.date }}
                                            </a><br/>
                                            <small>
                                                <i class="mdi mdi-shopping"></i> @{{ return.code }}
                                            </small>
                                        </td>
                                        <td>@{{ return.customer.display_name }}</td>
                                        <td>@{{ return.items.length }}</td>
                                        <td>
                                            <a target="_blank" href="/sales/allocation/@{{ return.daily_sale_id }}">
                                                @{{ return.allocation.code }}
                                            </a>
                                        </td>
                                        <td>@{{ return.company.name }}</td>
                                        <td class="text-center">
                                            <a title="Viex return details" class="p-10"
                                               href="/sales/return/@{{ return.id }}">
                                                <i class="ti-eye" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- pagination panel -->
                            <div class="col-md-12" ng-show="returns.length">
                                @include('general.pagination.pagination')
                            </div>
                        </div>
                        <div class="row" ng-if="returns.length === 0 && !filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">You haven't added any returns yet, click below button to
                                            add.</p>
                                        <a target="_blank" href="#"
                                           class="btn btn-info">
                                            <i class="fa fa-plus"></i> Add New Return
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- if there no estimates available message -->
                        <div class="row" ng-hide="loading" ng-if="returns.length == 0 && filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">There are <code>no</code> sales returns found.</p>
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
        app.controller('ReturnController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('sales.return.index') }}';
            $scope.returns = [];
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
                userId: null
            };
            $scope.el = {
                userDropDown: $('.user-drop-down'),
                customerDropDown: $('.customer-drop-down')
            };
            $scope.urls = {
                user: '{{ route('setting.user.search') }}',
                customer: '{{ route('sales.customer.search') }}'
            };
            $scope.filterUpdated = function () {
                $scope.filterd = true;
                $scope.query.search = $scope.searchEstimates;
                $scope.fetchReturns();
            };
            $scope.filterUpdate = function (filter) {
                $scope.query.filter = filter ? filter : '';
                $scope.filterd = true;
                $scope.fetchReturns();
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
                $scope.fetchReturns();
            };

            $scope.fetchReturns = function () {
                $scope.loading = true;

                $scope.query.page = $scope.currentPaginationPage + 1;
                var queryRoute = $.param($scope.query);
                $http.get(moduleRoute + '?' + queryRoute).then(function (response) {
                    $scope.loading = false;
                    $scope.returns = response.data.data;
                    $scope.pagination = response.data;
                    $scope.total = response.data.total;
                    $scope.range();
                });
            };
            $scope.fetchReturns();

            $scope.resetFilters = function () {
                $scope.el.userDropDown.dropdown('clear');
                $scope.el.customerDropDown.dropdown('clear');
                $scope.query = {
                    ajax: true,
                    page: 0,
                    filter: null,
                    search: null,
                    customerId: null,
                    userId: null
                };
                $scope.filterd = false;
                $scope.searchReturns = '';
                $scope.fetchReturns();
            };

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
                    if (val) $scope.fetchReturns();
                }
            });

            $scope.el.customerDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    $scope.filterd = true;
                    $scope.query.customerId = val;
                    if (val) $scope.fetchReturns();
                }
            });
        }]);
    </script>
@endsection