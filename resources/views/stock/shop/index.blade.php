@extends('layouts.master')
@section('title', 'Shop Stocks')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Stock') !!}
@endsection
@section('content')
    <div class="row" ng-controller="ShopStockController">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-b-0">
                    <h3 class="card-title"><i class="ti-truck"></i> Van Stocks</h3>
                </div>
                <hr>
                <div class="card-body">
                    <div class="form-filter">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group required @{{ hasError('shop') ? 'has-danger' : '' }}">
                                    <label class="control-label">Sales shop</label>
                                    <div class="ui fluid  search selection dropdown shop-drop-down @{{ hasError('shop') ? 'error' : '' }}">
                                        <input type="hidden" name="shop">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose a shop</div>
                                        <div class="menu">
                                            @foreach(shopDropDown() as $key => $shop)
                                                <div class="item" data-value="{{ $key }}">{{ $shop }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="form-control-feedback">@{{ hasError('shop') ? hasError('shop') : ''
                                        }}</p>
                                </div>
                            </div>
                        </div>
                        @include('report.general.date.index')
                    </div>
                    <div class="clearfix m-t-10">
                        <div class="pull-left">
                            <button ng-click="generate()" class="btn btn-info"><i class="ti-filter"></i>
                                Generate
                            </button>
                            <button ng-click="resetFilters()" class="btn btn-inverse"><i class="ti-eraser"></i>
                                Reset
                            </button>
                        </div>
                        <div class="pull-right"></div>
                    </div>
                    <hr class="hr-dark">

                    <!-- heading section -->
                    <div class="heading-section">
                        <h2 class="text-center"><b>Shop Stocks</b></h2>
                        <p class="text-center text-muted"><b>Available stocks as at </b> @{{ date | date }}</p>
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
                            <p>Please wait, stock lists loading...</p>
                        </div>
                    </div>

                    <div class="orders-list" ng-hide="loading">
                        @include('stock.shop._inc.shop')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection
@section('script')
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script src="{{ asset('js/vendor/object-helper.js') }}"></script>
    @include('report.general.date.script')
    @include('general.helpers')
    <script>
        app.controller('ShopStockController', ['$scope', '$http', function ($scope, $http) {
            var url = '{{ route('stock.shop.index') }}';

            $scope.query = {
                date: new Date(),
                shopId: ''
            };

            $scope.main = [];
            $scope.shop = null;

            $scope.daterangeDD = $('.date-range');
            $scope.daterangeValue = '';

            $scope.el = {
                loader: $('.cus-create-preloader')
            };

            //Initiate Date Range Drop down
            dateRangeDropDown($scope);

            $('.shop-drop-down').dropdown({
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    $scope.query.shopId = val;
                }
            });

            $scope.generate = function (shopId) {
                $scope.loading = true;
                $http.get(url + '?' + $.param($scope.query)).then(function (response) {
                    $scope.main = response.data;
                    $scope.items = $scope.main.items;
                    $scope.shop = $scope.main.shop;
                    $scope.loading = false;
                    $scope.initScroll();
                });
            };

            $scope.initScroll = function () {
                $('.cardScroll').slimScroll({
                    height: '500px'
                });
            };

            // Reset Filters
            $scope.resetFilters = function () {
                dateRangeDropDown($scope);
                $('.shop-drop-down').dropdown('clear');
                $scope.query.shopId = null;
                $scope.generate();
            };
        }
        ]).directive('cardDirective', function () {
            return function (scope, element, attrs) {
                scope.searchingObject[scope.vehicle.id] = false;
                $('.cardScroll').slimScroll({
                    height: '500px'
                });
            };
        });
    </script>
@endsection