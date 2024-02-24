@extends('layouts.master')
@section('title', 'Payments')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, '') !!}
@endsection
@section('content')
    <div class="row" >
        <div class="col-12" ng-controller="ReceiptController">
            <div class="card">
                <!-- .left-right-aside-column-->
                <div class="contact-page-aside">
                    <!-- .left-aside-column-->
                    <div class="left-aside">
                        @can('create', new \App\Expense())
                        <a target="_blank" href="{{ route('expense.receipt.create') }}" class="btn btn-info btn-block">
                            <i class="fa fa-plus"></i> Add New Payment
                        </a>
                        @endcan
                        <ul class="list-style-none">
                            <li class="text-muted m-t-20">FILTER PAYMENTS BY</li>
                            <li class="divider"></li>
                            <li ng-class="{'active': ( query.filter === 'all' || query.filter == null)}"
                                ng-click="filterUpdate('all')"><a
                                        href="">All Payments</a></li>
                            <li ng-class="{'active': ( query.filter === 'Office')}"
                                ng-click="filterUpdate('Office')"><a
                                        href="">Office Payments</a></li>
                            <li ng-class="{'active': ( query.filter === 'Van')}"
                                ng-click="filterUpdate('Van')"><a
                                        href="">Van Payments </a></li>
                            <li ng-class="{'active': ( query.filter === 'Shop')}"
                                ng-click="filterUpdate('Shop')"><a
                                        href="">Shop Payments</a></li>
                            <li ng-class="{'active': query.filter == 'recentlyCreated'}"
                                ng-click="filterUpdate('recentlyCreated')"><a href="">Recently Created</a></li>

                            <li ng-class="{'active': query.filter == 'recentlyUpdated'}"
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
                                    <h2 class="card-title m-t-10">Payments @{{ total ? ("(" + total +")") : '' }}</h2>
                                </div>
                                <div class="ml-auto">
                                    <input type="text" id="demo-input-search2" ng-model="searchData"
                                           placeholder="search for payments here" class="form-control"
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
                                <p>loading payments.....</p>
                            </div>
                        </div>
                        <div class="row" ng-hide="loading">
                            <div class="col-md-12 m-b-20">
                                <table id="demo-foo-addrow" class="table m-t-10 table-hover no-wrap contact-list" data-page-size="10">
                                    <thead>
                                    <tr>
                                        <th>Payment #</th>
                                        <th>Payment date</th>
                                        <th>Payment type</th>
                                        <th>Company</th>
                                        <th>Prepared by</th>
                                        <th class="text-right">Amount</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-muted">
                                    <tr ng-repeat="expense in expenses">
                                        <td style="vertical-align: middle;">
                                            <a target="_blank" href="/expense/receipts/@{{ expense.id }}">
                                                @{{ expense.expense_no }}
                                            </a><br />
                                            <small>
                                                <b>Payment mode</b> : @{{ expense.payment_mode }}
                                            </small>
                                        </td>
                                        <td>@{{ expense.expense_date | date }}</td>
                                        <td>@{{ expense.type.name }}</td>
                                        <td>@{{ expense.company.name }}</td>
                                        <td>@{{ expense.prepared_by.name }}</td>
                                        <td class="text-right">@{{ expense.amount | number }}</td>
                                        <td class="text-center">
                                            <a href="/expense/receipts/@{{ expense.id }}/edit">
                                                <i class="ti-pencil" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- pagination panel -->
                            <div class="col-md-12" ng-show="expenses.length">
                                @include('general.pagination.pagination')
                            </div>
                        </div>
                        <div class="row" ng-hide="loading" ng-if="expenses.length === 0 && !filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">You haven't added any payments yet, click below button to add a payment.</p>
                                        <a target="_blank" href="{{ route('expense.receipt.create') }}"
                                           class="btn btn-info">
                                            <i class="fa fa-plus"></i> Add New Payment
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- if there no expense available message -->
                        <div class="row" ng-hide="loading" ng-if="expenses.length === 0 && filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">There are <code>no</code> payments found.</p>
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
        app.controller('ReceiptController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('expense.receipt.index') }}';
            $scope.expenses = [];
            $scope.loading = true;
            $scope.filterd = false;
            $scope.pagination = {};
            $scope.currentPaginationPage = 0;
            $scope.searchData = '';
            $scope.query = {
                ajax : true,
                page : null,
                search : null,
                filter : null,
                user_id : null,
                customer_id : null,
                type_id : null,
            };
            // Drop down elements
            $scope.el = {
                userDropDown: $('.user-drop-down'),
                customerDropDown: $('.customer-drop-down'),
                typeDropDown: $('.type-drop-down'),
            };

            // Dropdown urls
            $scope.urls = {
                user: '{{ route('setting.user.search') }}',
                customer: '{{ route('sales.customer.search') }}',
                type: '{{ route('expense.type.search') }}',
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
            $scope.filterUpdate = function(filter){
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
                $http.get(moduleRoute + '?' +  prams).then(function (response) {
                    $scope.loading = false;
                    $scope.expenses = response.data.data;
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
                    ajax : true,
                    page : null,
                    search : null,
                    filter : null,
                    user_id : null,
                    customer_id : null,
                    type_id : null,
                };
                $scope.el.userDropDown.dropdown('clear');
                $scope.el.customerDropDown.dropdown('clear');
                $scope.el.typeDropDown.dropdown('clear');
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

            // type dropdown init
            $scope.el.typeDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: $scope.urls.type + '/{query}',
                    cache: false
                },
                onChange: function (val) {
                    $scope.query.type_id = val;
                    $scope.filterd = true;
                    $scope.fetchInquiry();
                }
            });

            // customer dropdown init
            $scope.el.customerDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: $scope.urls.customer + '/{query}',
                    cache: false
                },
                onChange: function (val) {
                    $scope.query.customer_id = val;
                    $scope.filterd = true;
                    $scope.fetchInquiry();
                }
            });
        }]);
    </script>
@endsection
