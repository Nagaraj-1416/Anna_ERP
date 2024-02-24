@extends('layouts.master')
@section('title', 'Transfers')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Transfers') !!}
@endsection
@section('content')
<div class="row" ng-controller="TransferController">
    <div class="col-12">
        <div class="card">
            <!-- .left-right-aside-column-->
            <div class="contact-page-aside">
                <!-- .left-aside-column-->
                <div class="left-aside">
                    <ul class="list-style-none">
                        <li class="text-muted m-t-20">FILTER TRANSACTION BY</li>
                        <li class="divider"></li>
                        <li ng-class="{'active': !query.filter}"
                            ng-click="filterUpdate()"><a
                                    href="">All Transfers</a></li>
                        <li ng-class="{'active': query.filter === 'cashTransfer'}"
                            ng-click="filterUpdate('cashTransfer')"><a href="">Cash Transfers</a></li>
                        <li ng-class="{'active': query.filter === 'chequeTransfer'}"
                            ng-click="filterUpdate('chequeTransfer')"><a href="">Cheque Transfers</a></li>
                        <li class="divider"></li>
                        <li ng-class="{'active': query.filter === 'byHand'}"
                            ng-click="filterUpdate('byHand')"><a href="">By Hand</a></li>
                        <li ng-class="{'active': query.filter === 'bankDeposit'}"
                            ng-click="filterUpdate('bankDeposit')"><a href="">Bank Deposit</a></li>
                        <li class="divider"></li>
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
                                <h2 class="card-title m-t-10">Transfers @{{ total ? ("(" + total +")") :
                                    '' }}</h2>
                            </div>
                            <div class="ml-auto">
                                <input type="text" id="demo-input-search2" ng-model="searchTransfers"
                                       placeholder="search your transfers here" class="form-control"
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
                            <p>loading transactions</p>
                        </div>
                    </div>
                    <div class="row" ng-hide="loading">
                        <div class="col-md-12 m-b-20">
                            <table class="table m-t-10 table-hover no-wrap contact-list">
                                <thead class="full-width">
                                    <tr>
                                        <th>Transfer details</th>
                                        <th class="text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="text-muted">
                                    <tr ng-repeat="transfer in transfers">
                                        <td>
                                            <a target="_blank" href="transfer/@{{ transfer.id }}">
                                                @{{ transfer.type }} transferred by @{{ transfer.transfer_by.name }}
                                            </a>
                                            <br />
                                            <small class="text-muted">
                                                <b>On</b> @{{ transfer.date | date }}
                                            </small><br />
                                            <small class="text-muted">
                                                <b>From: </b>@{{ transfer.sender_company.name }}
                                                <b>To: </b>@{{ transfer.receiver_company.name }}
                                            </small>
                                        </td>
                                        <td class="text-right">
                                            @{{ transfer.amount | number: 2 }}<br /><br />
                                            <small class="text-muted">
                                                <span ng-class="transfer.status == 'Pending' ? 'text-warning' : '' ||
                                                        transfer.status == 'Received' ? 'text-green' : '' ||
                                                        transfer.status == 'Declined' ? 'text-danger' : ''">
                                                    @{{ transfer.status }}
                                                </span>
                                            </small>
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
                    <div class="row" ng-hide="loading" ng-if="transfers.length === 0 && !filterd">
                        <div class="col-md-12">
                            <div class="card text-center">
                                <div class="card-body">
                                    <p class="card-text">You haven't added any transfers yet.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- if there no credit available message -->
                    <div class="row" ng-hide="loading" ng-if="transfers.length === 0 && filterd">
                        <div class="col-md-12">
                            <div class="card text-center">
                                <div class="card-body">
                                    <p class="card-text">There are <code>no</code> transfers found.</p>
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
        app.controller('TransferController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('finance.transfer.index') }}';
            $scope.transfers = [];
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
                $scope.query.search = $scope.searchTransfers;
                $scope.fetchTransfers();
            };

            $scope.filterUpdate = function (filter) {
                $scope.query.filter = filter ? filter : '';
                $scope.filterd = true;
                $scope.fetchTransfers();
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
                $scope.fetchTransfers();
            };

            $scope.fetchTransfers = function () {
                $scope.loading = true;
                $scope.query.page = $scope.currentPaginationPage + 1;
                var queryRoute = $.param($scope.query);
                $http.get(moduleRoute + '?' + queryRoute).then(function (response) {
                    $scope.loading = false;
                    $scope.transfers = response.data.data;
                    $scope.pagination = response.data;
                    $scope.total = response.data.total;
                    $scope.range();
                });
            };
            $scope.fetchTransfers();

            $scope.resetFilters = function () {
                $scope.query = {
                    ajax: true,
                    page: 0,
                    filter: '',
                    search: ''
                };
                $scope.searchTransfers = '';
                $scope.filterd = false;
                $scope.fetchTransfers();
            };

            $scope.checkPagination = function () {
                return $scope.total > $scope.pagination.per_page;
            }
        }]);
    </script>
@endsection