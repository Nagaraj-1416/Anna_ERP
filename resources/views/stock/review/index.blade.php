@extends('layouts.master')
@section('title', 'Stock Reviews')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Stock') !!}
@endsection
@section('content')
<div class="row" ng-controller="StockReviewController">
    <div class="col-12">
        <div class="card">
            <!-- .left-right-aside-column-->
            <div class="contact-page-aside">
                <!-- .left-aside-column-->
                <div class="left-aside">
                    {{--<a target="_blank" href="{{ route('stock.create') }}" class="btn btn-info btn-block">
                        <i class="fa fa-plus"></i> Transfer
                    </a>--}}
                    <div class="btn-group btn-block">
                        <button type="button" class="btn btn-block btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ti-check"></i> Review
                        </button>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                            @foreach(storeDropDown() as $keyStore => $store)
                                <a class="dropdown-item" href="{{ route('stock.review.create', $keyStore) }}">{{ $store }}</a>
                            @endforeach
                        </div>
                    </div>
                    <ul class="list-style-none">
                        <li class="text-muted m-t-20">FILTER REVIEW BY</li>
                        <li class="divider"></li>
                        <li ng-class="{'active': !query.filter}"
                            ng-click="filterUpdate()"><a
                                    href="">All Reviews</a></li>
                        <li ng-class="{'active': query.filter === 'recentlyCreated'}"
                            ng-click="filterUpdate('recentlyCreated')"><a href="">Recently Created</a></li>
                        <li ng-class="{'active': query.filter === 'recentlyUpdated'}"
                            ng-click="filterUpdate('recentlyUpdated')"><a href="">Recently Modified</a></li>
                    </ul>
                </div>
                <!-- /.left-aside-column-->
                <div class="right-aside custom-right-aside">
                    <div class="right-page-header">
                        <div class="d-flex m-b-10">
                            <div class="align-self-center">
                                <h2 class="card-title m-t-10">Stock Reviews @{{ total ? ("(" + total +")") :
                                    '' }}</h2>
                            </div>
                            <div class="ml-auto"></div>
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
                            <p>loading stock reviews</p>
                        </div>
                    </div>
                    <div class="row" ng-hide="loading">
                        <div class="col-md-12 m-b-20">
                            <table id="demo-foo-addrow" class="table m-t-10 table-hover no-wrap contact-list" data-page-size="10">
                                <thead>
                                <tr>
                                    <th>Review details</th>
                                    <th>Company</th>
                                    <th>Store</th>
                                    <th>Store staff</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody class="text-muted">
                                <tr ng-repeat="review in reviews">
                                    <td style="vertical-align: middle;">
                                        <a target="_blank" href="stock-review/@{{ review.id }}">
                                            @{{ review.date }}
                                        </a><br />
                                        <small>
                                            <b>Prepared by</b>: @{{ review.prepared_by.name }}<br/>
                                            <b>Prepared on</b>:  @{{ review.prepared_on }}
                                        </small>
                                    </td>
                                    <td>@{{ review.company.name }}</td>
                                    <td>@{{ review.store.name }}</td>
                                    <td>@{{ review.staff.short_name }}</td>
                                    <td>
                                        <span ng-class="review.status == 'Drafted' ? 'text-warning' : 'text-green'">@{{ review.status }}</span>
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
                    <div class="row" ng-hide="loading" ng-if="reviews.length === 0 && !filterd">
                        <div class="col-md-12">
                            <div class="card text-center">
                                <div class="card-body">
                                    <p class="card-text">You haven't added any stock reviews yet, click on "Review" button to review stocks.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- if there no credit available message -->
                    <div class="row" ng-hide="loading" ng-if="reviews.length === 0 && filterd">
                        <div class="col-md-12">
                            <div class="card text-center">
                                <div class="card-body">
                                    <p class="card-text">There are <code>no</code> stock reviews found.</p>
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
        app.controller('StockReviewController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('stock.review.index') }}';
            $scope.tranfers = [];
            $scope.filterd = false;
            $scope.loading = true;
            $scope.pagination = {};
            $scope.currentPaginationPage = 0;
            $scope.query = {
                ajax: true,
                page: null
            };

            $scope.filterUpdated = function () {
                $scope.filterd = true;
                $scope.fetchReviews();
            };

            $scope.filterUpdate = function (filter) {
                $scope.filterd = true;
                $scope.fetchReviews();
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
                $scope.fetchReviews();
            };

            $scope.fetchReviews = function () {
                $scope.loading = true;
                $scope.query.page = $scope.currentPaginationPage + 1;
                var queryRoute = $.param($scope.query);
                $http.get(moduleRoute + '?' + queryRoute).then(function (response) {
                    $scope.loading = false;
                    $scope.reviews = response.data.data;
                    $scope.pagination = response.data;
                    $scope.total = response.data.total;
                    $scope.range();
                });
            };
            $scope.fetchReviews();

            $scope.resetFilters = function () {
                $scope.query = {
                    ajax: true,
                    page: 0
                };
                $scope.filterd = false;
                $scope.fetchReviews();
            };

            $scope.checkPagination = function () {
                return $scope.total > $scope.pagination.per_page;
            };

        }]);
    </script>
@endsection