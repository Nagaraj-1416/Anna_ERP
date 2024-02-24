@extends('layouts.master')
@section('title', 'Products')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row" ng-controller="ProductController">
        <div class="col-12">
            <div class="card">
                <!-- .left-right-aside-column-->
                <div class="contact-page-aside">
                    <!-- .left-aside-column-->
                    <div class="left-aside">
                        <a target="_blank" href="{{ route('setting.product.create') }}" class="btn btn-info btn-block">
                            <i class="fa fa-plus"></i> Add New Product
                        </a>
                        <ul class="list-style-none">
                            <li class="text-muted m-t-20">FILTER PRODUCT BY</li>
                            <li class="divider"></li>
                            <li ng-class="{'active': !query.filter}"
                                ng-click="filterUpdate()"><a
                                        href="">All Products</a></li>
                            <li ng-class="{'active': query.filter === 'active'}"
                                ng-click="filterUpdate('active')"><a href="">Active Products</a></li>
                            <li ng-class="{'active': query.filter === 'inactive'}"
                                ng-click="filterUpdate('inactive')"><a href="">Inactive Products</a></li>
                            <li ng-class="{'active': query.filter === 'recentlyCreated'}"
                                ng-click="filterUpdate('recentlyCreated')"><a href="">Recently Created</a></li>
                            <li ng-class="{'active': query.filter === 'recentlyUpdated'}"
                                ng-click="filterUpdate('recentlyUpdated')"><a href="">Recently Modified</a></li>
                        </ul>
                        <ul class="list-style-none">
                            <li class="divider"></li>
                            <li class="m-t-10">Type</li>
                            <li>
                                <div class="ui fluid  search selection dropdown type-drop-down">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a type</div>
                                    <div class="menu">
                                        <div class="item" data-value="Raw Material">Raw Material</div>
                                        <div class="item" data-value="Finished Good">Finished Good</div>
                                        <div class="item" data-value="Third Party Product">Third Party Product</div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul class="list-style-none">
                            <li class="m-t-10">Category</li>
                            <li>
                                <div class="ui fluid  search selection dropdown category-drop-down">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a category</div>
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
                        <hr>
                        <p><b>Export to</b></p>
                        <a href="{{ route('setting.product.export') }}" class="btn btn-pdf">
                            PDF
                        </a>
                        <a href="{{ route('setting.product.export', ['type' => 'excel']) }}"
                           class="btn btn-excel">
                            Excel
                        </a>
                    </div>
                    <!-- /.left-aside-column-->
                    <div class="right-aside custom-right-aside">
                        <div class="right-page-header">
                            <div class="d-flex m-b-10">
                                <div class="align-self-center">
                                    <h2 class="card-title m-t-10">Products @{{ total ? ("(" + total +")") :
                                        '' }}</h2>
                                </div>
                                <div class="ml-auto">
                                    <input type="text" id="demo-input-search2" ng-model="searchProducts"
                                           placeholder="search for products here" class="form-control"
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
                                <p>loading products</p>
                            </div>
                        </div>
                        <div class="row" ng-hide="loading">
                            <div class="col-md-12 m-b-20">
                                <table id="demo-foo-addrow" class="table m-t-10 table-hover no-wrap contact-list"
                                       data-page-size="10">
                                    <thead>
                                    <tr>
                                        <th colspan="2">Product details</th>
                                        <th>Base price details</th>
                                        <th class="text-center">Min stock level</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-muted">
                                    <tr ng-repeat="product in products">
                                        <td style="width: 3%">
                                            <img src="@{{ getProductImage(product) }}" alt="user" class="img-circle"/>
                                        </td>
                                        <td style="vertical-align: middle;">
                                            <a target="_blank" href="/setting/product/@{{ product.id }}">
                                                @{{ product.name }}
                                            </a><br/>
                                            <small>
                                                <b>Type</b>: @{{ product.type }}<br/>
                                                <b>Category:</b> @{{ product.category.name }}
                                            </small>
                                        </td>
                                        <td style="vertical-align: middle;">
                                            <div ng-if="product.type == 'Raw Material'">
                                                <small><b>Buying:</b> @{{ product.buying_price | number:2 }}</small>
                                            </div>
                                            <div ng-if="product.type == 'Finished Good'">
                                                <small>
                                                    <b>Wholesale:</b> @{{ product.wholesale_price | number:2 }}<br />
                                                    <b>Retail:</b> @{{ product.wholesale_price | number:2 }}<br />
                                                    <b>Distribution:</b> @{{ product.wholesale_price | number:2 }}<br />
                                                    <b>Packet:</b> @{{ product.packet_price | number:2 }}
                                                </small>
                                            </div>
                                            <div ng-if="product.type == 'Third Party Product'">
                                                <small>
                                                    <b>Buying:</b> @{{ product.buying_price | number:2 }}<br />
                                                    <b>Wholesale:</b> @{{ product.wholesale_price | number:2 }}<br />
                                                    <b>Retail:</b> @{{ product.wholesale_price | number:2 }}<br />
                                                    <b>Distribution:</b> @{{ product.wholesale_price | number:2 }}<br />
                                                    <b>Packet:</b> @{{ product.packet_price | number:2 }}
                                                </small>
                                            </div>
                                        </td>
                                        <td class="text-center">@{{ product.min_stock_level }}</td>
                                        <td>
                                            <span class="label label-success"
                                                  ng-if="product.is_active == 'Yes'">Active</span>
                                            <span class="label label-danger"
                                                  ng-if="product.is_active == 'No'">Inactive</span>
                                        </td>
                                        <td class="text-center">
                                            <a class="p-10" href="/setting/product/@{{ product.id }}/edit">
                                                <i class="ti-pencil" aria-hidden="true"></i>
                                            </a>
                                            <a href="" class="p-10 upload-product-image upload-image"
                                               ng-click="uploadImageTrigger(product)">
                                                <i class="ti-image" aria-hidden="true"></i>
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
                        <div class="row" ng-hide="loading" ng-if="products.length === 0 && !filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">You haven't added any products yet, click on "Add New
                                            Product" button to add products.</p>
                                        <a target="_blank" href="{{ route('setting.product.create') }}"
                                           class="btn btn-info">
                                            <i class="fa fa-plus"></i> Add New Product
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- if there no credit available message -->
                        <div class="row" ng-hide="loading" ng-if="products.length === 0 && filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">There are <code>no</code> products found.</p>
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
        @include('_inc.image.add', ['btn' => 'upload-image'])
    </div>
@endsection
@section('style')
@endsection
@section('script')
    @include('_inc.image._inc.script', ['url' => route('setting.product.upload', ['product' => 'ID'])])
    <script src="{{ asset('js/vendor/slidereveal.js') }}"></script>
    <script>
        app.controller('ProductController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('setting.product.index') }}';
            $scope.products = [];
            $scope.filterd = false;
            $scope.loading = true;
            $scope.pagination = {};
            $scope.currentPaginationPage = 0;
            $scope.query = {
                ajax: true,
                page: null,
                filter: null,
                search: null,
                type_id : null,
                category_id : null
            };

            // Drop down elements
            $scope.el = {
                typeDropDown: $('.type-drop-down'),
                categoryDropDown: $('.category-drop-down')
            };

            // Dropdown urls
            $scope.urls = {
                category: '{{ route('setting.product.category.search') }}'
            };

            $scope.filterUpdated = function () {
                $scope.filterd = true;
                $scope.query.search = $scope.searchProducts;
                $scope.fetchProducts();
            };

            $scope.filterUpdate = function (filter) {
                $scope.query.filter = filter ? filter : '';
                $scope.filterd = true;
                $scope.fetchProducts();
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
                $scope.fetchProducts();
            };

            $scope.fetchProducts = function () {
                $scope.loading = true;
                $scope.query.page = $scope.currentPaginationPage + 1;
                var queryRoute = $.param($scope.query);
                $http.get(moduleRoute + '?' + queryRoute).then(function (response) {
                    $scope.loading = false;
                    $scope.products = response.data.data;
                    $scope.pagination = response.data;
                    $scope.total = response.data.total;
                    $scope.range();
                });
            };
            $scope.fetchProducts();

            $scope.resetFilters = function () {
                $scope.query = {
                    ajax: true,
                    page: 0,
                    filter: '',
                    search: '',
                    type_id : null,
                    category_id : null
                };
                $scope.searchProducts = '';
                $scope.filterd = false;
                $scope.fetchProducts();
            };

            // type dropdown init
            $scope.el.typeDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    $scope.query.type_id = val;
                    $scope.filterd = true;
                    $scope.fetchProducts();
                }
            });

            // category dropdown init
            $scope.el.categoryDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: $scope.urls.category + '/{query}',
                    cache: false
                },
                onChange: function (val) {
                    $scope.query.category_id = val;
                    $scope.filterd = true;
                    $scope.fetchProducts();
                }
            });

            $scope.getProductImage = function (product) {
                var route = '{{ route('setting.product.image', ['product' => 'PRODUCT']) }}';
                return route.replace('PRODUCT', product.id)
            };

            $scope.checkPagination = function () {
                return $scope.total > $scope.pagination.per_page;
            };

            $scope.el = {
                'loader': $('.image-preloader'),
            };


            $scope.imageSlider = $('#image-sidebar').slideReveal({
                position: "right",
                width: '400px',
                push: false,
                overlay: true,
                shown: function (slider, trigger) {
                    // init scroll for side bar body
                    $('#image-body').slimScroll({
                        color: 'gray',
                        height: '100%',
                        railVisible: true,
                        alwaysVisible: false
                    });
                },
                show: function (slider, trigger) {
                    $scope.hideLoader();
                    $scope.resetForm();
                },
            });
            $scope.selectedProduct = {};
            initSide($scope, $http);
            // Image Upload

            $scope.uploadImageTrigger = function (product) {
                $scope.selectedProduct = product;
                $scope.imageSlider.slideReveal("show");
            }
        }]);
    </script>
@endsection
