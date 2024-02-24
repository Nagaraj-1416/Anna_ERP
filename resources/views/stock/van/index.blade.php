@extends('layouts.master')
@section('title', 'Van Stocks')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Stock') !!}
@endsection
@section('content')
    <div class="row" ng-controller="VanStockController">
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
                                <div class="form-group required @{{ hasError('vehicle') ? 'has-danger' : '' }}">
                                    <label class="control-label">Sales van</label>
                                    <div class="ui fluid search selection dropdown vehicle-drop-down @{{ hasError('vehicle') ? 'error' : '' }}">
                                        <input type="hidden" name="vehicle">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose a van</div>
                                        <div class="menu">
                                            @foreach(vanDropDown() as $key => $vehicle)
                                                <div class="item" data-value="{{ $key }}">{{ $vehicle }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="form-control-feedback">@{{ hasError('vehicle') ? hasError('vehicle') : ''
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
                        <h2 class="text-center"><b>Van Stocks</b></h2>
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
                        @include('stock.van._inc.van')
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
        app.controller('VanStockController', ['$scope', '$http', function ($scope, $http) {
            var url = '{{ route('stock.van.index') }}';

            $scope.query = {
                date: new Date(),
                vanId: ''
            };

            $scope.main = [];
            $scope.location = null;

            $scope.daterangeDD = $('.date-range');
            $scope.daterangeValue = '';

            $scope.el = {
                loader: $('.cus-create-preloader')
            };

            //Initiate Date Range Drop down
            dateRangeDropDown($scope);

            $('.vehicle-drop-down').dropdown({
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    $scope.query.vanId = val;
                }
            });

            $scope.generate = function (vanId) {
                $scope.loading = true;
                $http.get(url + '?' + $.param($scope.query)).then(function (response) {
                    $scope.main = response.data;
                    $scope.items = $scope.main.items;
                    $scope.location = $scope.main.location;
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
                $('.vehicle-drop-down').dropdown('clear');
                $scope.query.vanId = null;
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