@extends('layouts.master')
@section('title', 'Chart of Accounts')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
    <div class="row" ng-controller="AccountController">
        <div class="col-12">
            <div class="card">
                <!-- .left-right-aside-column-->
                <div class="contact-page-aside">
                    <!-- .left-aside-column-->
                    <div class="left-aside">
                        <a target="_blank" href="{{ route('finance.account.create') }}" class="btn btn-info btn-block">
                            <i class="fa fa-plus"></i> Add New Account
                        </a>
                        <ul class="list-style-none">
                            <li class="text-muted m-t-20">FILTER STORE BY</li>
                            <li class="divider"></li>
                            <li ng-class="{'active': !query.filter}"
                                ng-click="filterUpdate()"><a
                                        href="">All Accounts</a></li>
                            <li class="divider"></li>
                            <li ng-class="{'active': query.filter === 'AssetAcc'}"
                                ng-click="filterUpdate('AssetAcc')"><a href="" class="text-primary"><b>Asset Accounts</b></a></li>

                            <li ng-class="{'active': query.filter === 'LiaAcc'}"
                                ng-click="filterUpdate('LiaAcc')"><a href="" class="text-warning"><b>Liability Accounts</b></a></li>

                            <li ng-class="{'active': query.filter === 'IncAcc'}"
                                ng-click="filterUpdate('IncAcc')"><a href="" class="text-megna"><b>Income Accounts</b></a></li>

                            <li ng-class="{'active': query.filter === 'ExpAcc'}"
                                ng-click="filterUpdate('ExpAcc')"><a href="" class="text-danger"><b>Expense Accounts</b></a></li>

                            <li ng-class="{'active': query.filter === 'EquAcc'}"
                                ng-click="filterUpdate('EquAcc')"><a href="" class="text-info"><b>Equity Accounts</b></a></li>

                            <li class="divider"></li>
                            <li ng-class="{'active': query.filter === 'recentlyCreated'}"
                                ng-click="filterUpdate('recentlyCreated')"><a href="">Recently Created</a></li>
                            <li ng-class="{'active': query.filter === 'recentlyUpdated'}"
                                ng-click="filterUpdate('recentlyUpdated')"><a href="">Recently Modified</a></li>
                        </ul>
                        <ul class="list-style-none">
                            <li class="divider"></li>
                            <li class="m-t-10">Account type</li>
                            <li>
                                <div class="ui fluid  search selection dropdown type-drop-down">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a type</div>
                                    <div class="menu">
                                        @foreach(accTypeDropDown() as $key => $accountType)
                                            <div class="item" data-value="{{ $key }}">{{ $accountType }}</div>
                                        @endforeach
                                    </div>
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
                                    <h2 class="card-title m-t-10">Accounts @{{ total ? ("(" + total +")") :
                                        '' }}</h2>
                                </div>
                                <div class="ml-auto">
                                    <input type="text" id="demo-input-search2" ng-model="searchAccounts"
                                           placeholder="search for account here" class="form-control"
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
                                <p>loading accounts</p>
                            </div>
                        </div>
                        <div class="row" ng-hide="loading">
                            <div class="col-md-12 m-b-20">
                                <table id="demo-foo-addrow" class="table m-t-10 table-hover no-wrap contact-list" data-page-size="10">
                                    <thead>
                                    <tr>
                                        <th>Account details</th>
                                        <th>Account type</th>
                                        <th>Account category</th>
                                        <th>Company</th>
                                        <th>Prefix</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-muted">
                                    <tr ng-repeat="account in accounts">
                                        <td style="vertical-align: middle;">
                                            <a target="_blank" href="account/@{{ account.id }}">
                                                @{{ account.name }}
                                            </a><br />
                                            <small>
                                                <span ng-if="account.latest_tx_date" class="text-info">Last transaction on @{{ account.latest_tx_date | date }}</span>
                                                <span ng-if="!account.latest_tx_date" class="text-warning">No transaction made so far</span>
                                            </small>
                                        </td>
                                        <td>@{{ account.type.name }}</td>
                                        <td>@{{ account.category.name }}</td>
                                        <td>@{{ account.company.name }}</td>
                                        <td>@{{ account.prefix }}</td>
                                        <td class="text-center">
                                            <a href="account/@{{ account.id }}/edit">
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
                        <div class="row" ng-hide="loading" ng-if="accounts.length === 0 && !filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">You haven't added any account yet, click on "Add New Account" button to add account.</p>
                                        <a target="_blank" href="{{ route('finance.account.create') }}"
                                           class="btn btn-info">
                                            <i class="fa fa-plus"></i> Add New Account
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- if there no credit available message -->
                        <div class="row" ng-hide="loading" ng-if="accounts.length === 0 && filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">There are <code>no</code> accounts found.</p>
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
        app.controller('AccountController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('finance.account.index') }}';
            $scope.accounts = [];
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

            $scope.el = {
                categoryDropDown: $('.category-drop-down'),
                typeDropDown: $('.type-drop-down')
            };

            $scope.filterUpdated = function () {
                $scope.filterd = true;
                $scope.query.search = $scope.searchAccounts;
                $scope.fetchAccounts();
            };

            $scope.filterUpdate = function (filter) {
                $scope.query.filter = filter ? filter : '';
                $scope.filterd = true;
                $scope.fetchAccounts();
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
                $scope.fetchAccounts();
            };

            $scope.fetchAccounts = function () {
                $scope.loading = true;
                $scope.query.page = $scope.currentPaginationPage + 1;
                var queryRoute = $.param($scope.query);
                $http.get(moduleRoute + '?' + queryRoute).then(function (response) {
                    $scope.loading = false;
                    $scope.accounts = response.data.data;
                    $scope.pagination = response.data;
                    $scope.total = response.data.total;
                    $scope.range();
                });
            };
            $scope.fetchAccounts();

            $scope.resetFilters = function () {
                $scope.query = {
                    ajax: true,
                    page: 0,
                    filter: '',
                    search: '',
                    type_id : null,
                    category_id : null
                };
                $scope.searchAccounts = '';
                $scope.filterd = false;
                $scope.fetchAccounts();
                $scope.el.typeDropDown.dropdown('clear');
                $scope.el.categoryDropDown.dropdown('clear');
            };

            $scope.el.typeDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    $scope.query.type_id = val;
                    $scope.filterd = true;
                    $scope.fetchAccounts();
                }
            });

            $scope.el.categoryDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    $scope.query.category_id = val;
                    $scope.filterd = true;
                    $scope.fetchAccounts();
                }
            });

            $scope.checkPagination = function () {
                return $scope.total > $scope.pagination.per_page;
            }
        }]);
    </script>
@endsection
