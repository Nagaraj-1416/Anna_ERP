@extends('layouts.master')
@section('title', 'Expense Reports')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Expense') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12" ng-controller="ReportController">
            <div class="card">
                <!-- .left-right-aside-column-->
                <div class="contact-page-aside">
                    <!-- .left-aside-column-->
                    <div class="left-aside">
                        @can('create', new \App\ExpenseReport())
                            <a target="_blank" href="{{ route('expense.reports.create') }}"
                               class="btn btn-info btn-block">
                                <i class="fa fa-plus"></i> Add New Report
                            </a>
                        @endcan
                        <ul class="list-style-none">
                            <li class="text-muted m-t-20">FILTER REPORT BY</li>
                            <li class="divider"></li>
                            <li ng-class="{'active': ( query.filter === 'all' || query.filter == null)}"
                                ng-click="filterUpdate('all')"><a
                                        href="">All Reports</a></li>

                            <li ng-class="{'active': query.filter == 'Draft'}"
                                ng-click="filterUpdate('Draft')"><a href="">Draft Reports</a></li>

                            <li ng-class="{'active': query.filter == 'Submitted'}"
                                ng-click="filterUpdate('Submitted')"><a href="">Submitted Reports</a></li>

                            <li ng-class="{'active': query.filter == 'Approved'}"
                                ng-click="filterUpdate('Approved')"><a href="">Approved Reports</a></li>

                            <li ng-class="{'active': query.filter == 'Rejected'}"
                                ng-click="filterUpdate('Rejected')"><a href="">Rejected Reports</a></li>

                            <li ng-class="{'active': query.filter == 'Partially Reimbursed'}"
                                ng-click="filterUpdate('Partially Reimbursed')"><a href="">Partially Reimbursed</a></li>

                            <li ng-class="{'active': query.filter == 'Reimbursed'}"
                                ng-click="filterUpdate('Reimbursed')"><a href="">Reimbursed Reports</a></li>

                            <li ng-class="{'active': query.filter == 'recentlyCreated'}"
                                ng-click="filterUpdate('recentlyCreated')"><a href="">Recently Created</a></li>

                            <li ng-class="{'active': query.filter == 'recentlyUpdated'}"
                                ng-click="filterUpdate('recentlyUpdated')"><a href="">Recently Modified</a></li>
                        </ul>
                        <ul class="list-style-none">
                            <li class="divider"></li>
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
                                    <h2 class="card-title m-t-10">Reports @{{ total ? ("(" + total +")") : '' }}</h2>
                                </div>
                                <div class="ml-auto">
                                    <input type="text" id="demo-input-search2" ng-model="searchData"
                                           placeholder="search reports here" class="form-control"
                                           ng-change="globalSearch()">
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
                                <p>loading reports.....</p>
                            </div>
                        </div>
                        <div class="row" ng-hide="loading">
                            <div class="col-md-3 col-sm-6 col-xs-12"
                                 ng-repeat="report in reports ">
                                <div class="card card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3 class="box-title m-b-0">
                                                <a target="_blank" href="/expense/reports/@{{ report.id }}">@{{
                                                    report.report_no }}</a>
                                            </h3>
                                            <small><b>Amount:</b> @{{ report.amount }} LKR</small>
                                            <p class="text-muted">
                                                <small><b>Report Status:</b> @{{ report.status }}</small>
                                            </p>
                                            @{{ report.report_from }} <b>to</b> @{{ report.report_to }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- pagination panel -->
                            <div class="col-md-12" ng-show="reports.length">
                                @include('general.pagination.pagination')
                            </div>
                        </div>
                        <div class="row" ng-hide="loading" ng-if="reports.length === 0 && !filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">You haven't added any reports yet, click below button to
                                            add.</p>
                                        <a target="_blank" href="{{ route('expense.reports.create') }}"
                                           class="btn btn-info">
                                            <i class="fa fa-plus"></i> Add New Expense Report
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- if there no expense available message -->
                        <div class="row" ng-hide="loading" ng-if="reports.length === 0 && filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">There are <code>no</code> reports found.</p>
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
        app.controller('ReportController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('expense.reports.index') }}';
            $scope.reports = [];
            $scope.loading = true;
            $scope.filterd = false;
            $scope.pagination = {};
            $scope.currentPaginationPage = 0;
            $scope.searchData = '';
            $scope.query = {
                ajax: true,
                page: null,
                search: null,
                filter: null,
                user_id: null,
            };
            // Drop down elements
            $scope.el = {
                userDropDown: $('.user-drop-down'),
            };

            // Dropdown urls
            $scope.urls = {
                user: '{{ route('setting.user.search') }}',
            };

            //Pagination range calculator
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

            // Pagination navigate to prev page
            $scope.prevPage = function () {
                if ($scope.currentPaginationPage > 0) {
                    $scope.currentPaginationPage--;
                }
                $scope.paginationChanged()
            };

            // if check and disable prev page
            $scope.prevPageDisabled = function () {
                return $scope.currentPaginationPage === 0 ? "disabled" : "";
            };

            // calculate total page count
            $scope.pageCount = function () {
                return $scope.pagination.last_page - 1;
            };

            // paginate navigate to next page
            $scope.nextPage = function () {
                if ($scope.currentPaginationPage < $scope.pageCount()) {
                    $scope.currentPaginationPage++;
                }
                $scope.paginationChanged()
            };

            // Paginate check and disable next page
            $scope.nextPageDisabled = function () {
                return $scope.currentPaginationPage === $scope.pageCount() ? "disabled" : "";
            };

            // paginate set page
            $scope.setPage = function (n) {
                if ($scope.pagination.current_page === n + 1) return;
                $scope.currentPaginationPage = n;
                $scope.paginationChanged()
            };

            // Paginate change page
            $scope.paginationChanged = function () {
                $scope.fetchInquiry();
            };

            // side filter changed
            $scope.filterUpdate = function (filter) {
                $scope.query.filter = filter;
                $scope.filterd = true;
                $scope.fetchInquiry();
            };

            // global search function
            $scope.globalSearch = function () {
                $scope.query.search = $scope.searchData;
                $scope.currentPaginationPage = 0;
                $scope.fetchInquiry();
            };

            // fetching data from server
            $scope.fetchInquiry = function () {
                $scope.loading = true;
                var page = $scope.currentPaginationPage + 1;
                $scope.query.page = page;
                var prams = $.param($scope.query);
                $http.get(moduleRoute + '?' + prams).then(function (response) {
                    $scope.loading = false;
                    $scope.reports = response.data.data;
                    $scope.pagination = response.data;
                    $scope.total = response.data.total;
                    $scope.range();
                });
            };

            // fetch init data from server
            $scope.fetchInquiry();

            // reset filter
            $scope.resetFilters = function () {
                $scope.query = {
                    ajax: true,
                    page: null,
                    search: null,
                    filter: null,
                    user_id: null,
                };
                $scope.el.userDropDown.dropdown('clear');
                $scope.el.customerDropDown.dropdown('clear');
                $scope.filterd = false;
            };

            // User dropdown init
            $scope.el.userDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: $scope.urls.user + '/{query}',
                    cache: false
                },
                onChange: function (val) {
                    $scope.query.user_id = val;
                    $scope.filterd = true;
                    $scope.fetchInquiry();
                }
            });
        }]);
    </script>
@endsection
