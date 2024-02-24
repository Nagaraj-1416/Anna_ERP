@extends('layouts.master')
@section('title', 'Cash Shortages')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row" ng-controller="ShortageController">
        <div class="col-12">
            <div class="card">
                <!-- .left-right-aside-column-->
                <div class="contact-page-aside">
                    <!-- .left-aside-column-->
                    <div class="left-aside">
                        <ul class="list-style-none">
                            <li class="text-muted m-t-20">FILTER SHORTAGES BY</li>
                            <li class="divider"></li>
                            <li ng-class="{'active': !query.filter}"
                                ng-click="filterUpdate()"><a
                                        href="">All Shortages</a></li>
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
                                    <h2 class="card-title m-t-10">Cash Shortages @{{ total ? ("(" + total
                                        +")") :
                                        '' }}</h2>
                                </div>
                                <div class="ml-auto">
                                    <input type="text" id="demo-input-search2" ng-model="searchShortages"
                                           placeholder="search for price books here" class="form-control"
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
                                <p>loading sales shortages</p>
                            </div>
                        </div>
                        <div class="row" ng-hide="loading">
                            <div class="col-md-12 m-b-20">
                                <table id="demo-foo-addrow" class="table m-t-10 table-hover no-wrap contact-list"
                                       data-page-size="10">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Rep details</th>
                                            <th>Allocation details</th>
                                            <th class="text-right">Amount</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-muted">
                                    <tr ng-repeat="shortage in shortages">
                                        <td>
                                            @{{ getDate(shortage.date) }}
                                        </td>
                                        <td style="vertical-align: middle;">
                                            <a target="_blank" href="setting/sales-rep/@{{ shortage.rep.id }}">
                                                @{{ shortage.rep.name }}
                                            </a>
                                        </td>
                                        <td>
                                            <a target="_blank"
                                               href="/sales/allocation/@{{ shortage.handover.daily_sale_id }}">@{{
                                                shortage.handover.code }}</a><br />
                                            <span>@{{ shortage.daily_sale.route.name }}</span>
                                        </td>
                                        <td class="text-right">
                                            @{{ shortage.amount | number:2 }}
                                        </td>
                                        <td class="text-center">
                                            <a href="/sales/shortage/@{{ shortage.id }}/export" class="btn btn-sm btn-inverse">
                                                Export to PDF
                                            </a>
                                        </td>
                                        {{--<td>
                                            <div class=" btn-group" ng-if="shortage.status === 'Pending'">
                                                <a href="#" ng-click="approveShortage(shortage)"
                                                   class="btn btn-sm btn-success">Approve</a>
                                                <a href="#" ng-click="rejectShortage(shortage)"
                                                   class="btn btn-sm btn-danger">Reject</a>
                                            </div>
                                        </td>--}}
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- pagination panel -->
                            <div class="col-md-12" ng-show="checkPagination()">
                                @include('general.pagination.pagination')
                            </div>
                        </div>
                        <div class="row" ng-hide="loading" ng-if="shortages.length === 0 && !filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">You haven't added any price book yet, click on "Add New
                                            Book" button to add price book.</p>
                                        <a target="_blank" href="{{ route('setting.price.book.create') }}"
                                           class="btn btn-info">
                                            <i class="fa fa-plus"></i> Add New Book
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- if there no credit available message -->
                        <div class="row" ng-hide="loading" ng-if="shortages.length === 0 && filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">There are <code>no</code> sales shortages found.</p>
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
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script>
        app.controller('ShortageController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('sales.shortage.index') }}';
            $scope.shortages = [];
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
                $scope.query.search = $scope.searchShortages;
                $scope.fetchShortages();
            };

            $scope.filterUpdate = function (filter) {
                $scope.query.filter = filter ? filter : '';
                $scope.filterd = true;
                $scope.fetchShortages();
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
                $scope.fetchShortages();
            };

            $scope.fetchShortages = function () {
                $scope.loading = true;
                $scope.query.page = $scope.currentPaginationPage + 1;
                var queryRoute = $.param($scope.query);
                $http.get(moduleRoute + '?' + queryRoute).then(function (response) {
                    $scope.loading = false;
                    $scope.shortages = response.data.data;
                    $scope.pagination = response.data;
                    $scope.total = response.data.total;
                    $scope.range();
                });
            };
            $scope.fetchShortages();

            $scope.resetFilters = function () {
                $scope.query = {
                    ajax: true,
                    page: 0,
                    filter: '',
                    search: ''
                };
                $scope.searchShortages = '';
                $scope.filterd = false;
                $scope.fetchShortages();
            };

            $scope.checkPagination = function () {
                return $scope.total > $scope.pagination.per_page;
            };

            $scope.approveUrl = '{{ route('sales.shortage.approve', ['shortage' => 'ID']) }}';
            $scope.rejectUrl = '{{ route('sales.shortage.reject', ['shortage' => 'ID']) }}';
            $scope.approveShortage = function (shortage) {
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this action!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#26c6da',
                    confirmButtonText: 'Yes, Approve!'
                }).then(function (isConfirm) {
                    if (isConfirm.value) {
                        $.ajax({
                            url: $scope.approveUrl.replace('ID', shortage.id),
                            type: 'POST',
                            data: {'_token': '{{ csrf_token() }}'},
                            success: function (result) {
                                swal(
                                    'Approved!',
                                    'Shortage  approved successfully!',
                                    'success'
                                );
                                $scope.fetchShortages();
                            }
                        });
                    }
                });
            };

            $scope.rejectShortage = function (shortage) {
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this action!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#fc4b6c',
                    confirmButtonText: 'Yes, Reject!'
                }).then(function (isConfirm) {
                    if (isConfirm.value) {
                        $.ajax({
                            url: $scope.rejectUrl.replace('ID', shortage.id),
                            type: 'POST',
                            data: {'_token': '{{ csrf_token() }}'},
                            success: function (result) {
                                swal(
                                    'Rejected!',
                                    'Shortage  rejected successfully!',
                                    'success'
                                );
                                $scope.fetchShortages();
                            }
                        });
                    }
                });
            };

            $scope.getDate = function (date) {
                return moment(date).format('Y-MM-DD');
            };
        }]);
    </script>
@endsection