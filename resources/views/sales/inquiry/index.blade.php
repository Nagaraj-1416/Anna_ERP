@extends('layouts.master')
@section('title', 'Inquiries')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row" >
        <div class="col-12" ng-controller="SalesInquiryController">
            <div class="card">
                <!-- .left-right-aside-column-->
                <div class="contact-page-aside">
                    <!-- .left-aside-column-->
                    <div class="left-aside">
                        @can('create', new \App\SalesInquiry())
                        <a target="_blank" href="{{ route('sales.inquiries.create') }}" class="btn btn-info btn-block">
                            <i class="fa fa-plus"></i> Add New Inquiry
                        </a>
                        @endcan
                        <ul class="list-style-none">
                            <li class="text-muted m-t-20">FILTER INQUIRY BY</li>
                            <li class="divider"></li>
                            <li ng-class="{'active': ( query.filter === 'all' || query.filter == null)}"
                                ng-click="filterUpdate('all')"><a
                                        href="">All Inquiries</a></li>

                            <li ng-class="{'active': query.filter == 'Open'}"
                                ng-click="filterUpdate('Open')"><a href="">Open Inquiries</a></li>

                            <li ng-class="{'active': query.filter == 'ConvertedToEstimate'}"
                                ng-click="filterUpdate('ConvertedToEstimate')"><a href="">Converted as Estimate</a></li>

                            <li ng-class="{'active': query.filter == 'ConvertedToOrder'}"
                                ng-click="filterUpdate('ConvertedToOrder')"><a href="">Converted as Order</a></li>

                            <li ng-class="{'active': query.filter == 'recentlyCreated'}"
                                ng-click="filterUpdate('recentlyCreated')"><a href="">Recently Created</a></li>

                            <li ng-class="{'active': query.filter == 'recentlyUpdated'}"
                                ng-click="filterUpdate('recentlyUpdated')"><a href="">Recently Modified</a></li>
                        </ul>
                        <ul class="list-style-none">
                            <li class="divider"></li>
                            <li class="m-t-10">Customer</li>
                            <li>
                                <div class="ui fluid  search selection dropdown customer-drop-down">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a customer</div>
                                    <div class="menu"></div>
                                </div>
                            </li>
                        </ul>
                        <ul class="list-style-none">
                            <li class="m-t-10">Product</li>
                            <li>
                                <div class="ui fluid  search selection dropdown product-drop-down">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a product</div>
                                    <div class="menu"></div>
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
                                    <h2 class="card-title m-t-10">Inquiries @{{ total ? ("(" + total +")") :
                                        '' }}</h2>
                                </div>
                                <div class="ml-auto">
                                    <input type="text" id="demo-input-search2" ng-model="searchData"
                                           placeholder="search for inquiries here" class="form-control"
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
                                <p>loading sales inquiries</p>
                            </div>
                        </div>
                        <div class="row" ng-hide="loading">
                            <div class="col-md-3 col-sm-6 col-xs-12"
                                 ng-repeat="inquiry in inquiries ">
                                <div class="card card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3 class="box-title m-b-0">
                                                <a target="_blank" href="inquiries/@{{ inquiry.id }}">@{{ inquiry.code }}</a>
                                            </h3>
                                            <small ng-show="!inquiry.customer">Not associated with customer</small>
                                            <small ng-show="inquiry.customer">@{{ inquiry.customer.display_name }} | @{{inquiry.customer.mobile}}</small>
                                            <p class="text-muted">
                                                <small><b>Inquiry Status:</b> @{{ inquiry.status }}</small>
                                            </p>
                                            @{{ inquiry.inquiry_date }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- pagination panel -->
                            <div class="col-md-12" ng-show="inquiries.length">
                                @include('general.pagination.pagination')
                            </div>
                        </div>
                        <div class="row" ng-hide="loading" ng-if="inquiries.length === 0 && !filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">You haven't added any inquiries yet, click below button to
                                            add.</p>
                                        <a target="_blank" href="{{ route('sales.inquiries.create') }}"
                                           class="btn btn-info">
                                            <i class="fa fa-plus"></i> Add New Order
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- if there no inquiry available message -->
                        <div class="row" ng-hide="loading" ng-if="inquiries.length === 0 && filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">There are <code>no</code> inquiries found.</p>
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
        app.controller('SalesInquiryController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('sales.inquiries.index') }}';
            $scope.inquiries = [];
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
                product_id : null,
                user_id : null,
                customer_id : null,
            };
            // Drop down elements
            $scope.el = {
                userDropDown: $('.user-drop-down'),
                customerDropDown: $('.customer-drop-down'),
                productDropDown: $('.product-drop-down')
            };

            // Dropdown urls
            $scope.urls = {
                user: '{{ route('setting.user.search') }}',
                customer: '{{ route('sales.customer.search') }}',
                product: '{{ route('setting.product.search', ['type' => 'Finished Good']) }}',
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
                    $scope.inquiries = response.data.data;
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
                    product_id : null,
                    user_id : null,
                    customer_id : null,
                };
                $scope.el.userDropDown.dropdown('clear');
                $scope.el.customerDropDown.dropdown('clear');
                $scope.el.productDropDown.dropdown('clear');
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

            // products dropdown init
            $scope.el.productDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: $scope.urls.product + '/{query}',
                    cache: false
                },
                onChange: function (val) {
                    $scope.query.product_id = val;
                    $scope.filterd = true;
                    $scope.fetchInquiry();
                }
            });
        }]);
    </script>
@endsection