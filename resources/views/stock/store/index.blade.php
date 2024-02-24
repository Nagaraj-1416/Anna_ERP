@extends('layouts.master')
@section('title', 'Store Stocks')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Stock') !!}
@endsection
@section('content')
<div class="row" ng-controller="StoreStockController">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body p-b-0">
                <h3 class="card-title"><i class="ti-bar-chart"></i> Store Stocks</h3>
            </div>
            <hr>
            <div class="card-body">
                <!-- from to filter -->
                <div class="form-filter">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group required @{{ hasError('store') ? 'has-danger' : '' }}">
                                <label class="control-label">Store</label>
                                <div class="ui fluid  search selection dropdown store-drop-down @{{ hasError('store') ? 'error' : '' }}">
                                    <input type="hidden" name="store">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a store</div>
                                    <div class="menu">
                                        @foreach(storeDropDown() as $key => $store)
                                            <div class="item" data-value="{{ $key }}">{{ $store }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                <p class="form-control-feedback">@{{ hasError('store') ? hasError('store') : ''
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
                    <div class="pull-right">
                        <a href="@{{ getExportRoute() }}" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i>
                            Export to PDF</a>
                    </div>
                </div>
                <hr class="hr-dark">

                <!-- heading section -->
                <div class="heading-section">
                    <h2 class="text-center"><b>Store Stocks</b></h2>
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
                    @include('stock.store._inc.store')
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
        app.controller('StoreStockController', ['$scope', '$http', function ($scope, $http) {
            var url = '{{ route('stock.store.index') }}';

            $scope.query = {
                date: new Date(),
                storeId: '',
                searchQuery: ''
            };

            $scope.main = [];
            $scope.stocks = [];
            $scope.loading = false;
            $scope.searchProducts = '';
            $scope.store = null;

            $scope.daterangeDD = $('.date-range');
            $scope.daterangeValue = '';

            $scope.searching = false;

            $scope.el = {
                loader: $('.cus-create-preloader')
            };

            //Initiate Date Range Drop down
            dateRangeDropDown($scope);

            $('.store-drop-down').dropdown({
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    $scope.query.storeId = val;
                }
            });

            $scope.searchingObject = [];
            $scope.generate = function (storeId) {
                $scope.loading = true;
                $http.get(url + '?' + $.param($scope.query)).then(function (response) {
                    $scope.main = response.data;
                    $scope.store = response.data.store;
                    $scope.stocks = $scope.main.stocks;
                    $scope.noStocks = $scope.main.noStocks;
                    $scope.loading = false;
                    $scope.searching = false;
                    if (storeId) {
                        $scope.searchingObject[storeId] = false;
                    }
                    $scope.initScroll();
                });
            };

            // $scope.el.loader.addClass('hidden');
            $scope.showLoader = function () {
                $scope.el.loader.addClass('loading');
                $scope.el.loader.removeClass('hidden');
            };

            $scope.hideLoader = function () {
                $scope.el.loader.removeClass('loading');
                $scope.el.loader.addClass('hidden');
            };

            $scope.searchProduct = _.debounce(function () {

            });

            $scope.initScroll = function () {
                $('.cardScroll').slimScroll({
                    height: '500px'
                });
            };

            // Reset Filters
            $scope.resetFilters = function () {
                dateRangeDropDown($scope);
                $('.store-drop-down').dropdown('clear');
                $scope.query.storeId = null;
                $scope.generate();
            };

            $scope.getExportRoute = function () {
                var route = '{{ route('stock.store.export') }}';
                return route + '?' + $.param($scope.query);
            };
        }
        ]).directive('cardDirective', function () {
            return function (scope, element, attrs) {
                scope.searchingObject[scope.store.id] = false;
                $('.cardScroll').slimScroll({
                    height: '500px'
                });
            };
        });
    </script>
@endsection