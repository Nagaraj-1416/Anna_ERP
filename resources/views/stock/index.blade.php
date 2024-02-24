@extends('layouts.master')
@section('title', 'Stock')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Stock') !!}
@endsection
@section('content')
    <section>
        <div class="row" ng-controller="StockSummaryController">
            <div class="col-lg-3 col-md-6">
                <div class="card border-info">
                    <div class="card-body">
                        <h4 class="card-title text-info">Things You Could Do</h4>
                        <hr/>
                        <ul class="feeds">
                            <li>
                                <div class="bg-light-info">
                                    <i class="ti-package"></i>
                                </div>
                                <a href="{{ route('stock.create') }}">New Stock (Manual)</a>
                            </li>
                            {{--<li>
                                <div class="bg-light-success">
                                    <i class="ti-receipt"></i>
                                </div>
                                <a href="#">New Goods Receipt Note</a>
                            </li>
                            <li>
                                <div class="bg-light-primary">
                                    <i class="ti-receipt"></i>
                                </div>
                                <a href="#">New Goods Issue Note</a>
                            </li>--}}
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="d-flex flex-row">
                                <div class="p-10 bg-primary">
                                    <h3 class="text-white box m-b-0"><i class="ti-wallet"></i></h3></div>
                                <div class="align-self-center m-l-20">
                                    <h3 class="m-b-0 text-primary">{{ $stocks }}</h3>
                                    <h6 class="text-muted m-b-0">Total Items</h6></div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="d-flex flex-row">
                                <div class="p-10 bg-warning">
                                    <h3 class="text-white box m-b-0"><i class="ti-wallet"></i></h3></div>
                                <div class="align-self-center m-l-20">
                                    <h3 class="m-b-0 text-warning">@{{ lowStockItemsCount }}</h3>
                                    <h6 class="text-muted m-b-0">Total Low Stock Items</h6></div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="d-flex flex-row">
                                <div class="p-10 bg-danger">
                                    <h3 class="text-white box m-b-0"><i class="ti-wallet"></i></h3></div>
                                <div class="align-self-center m-l-20">
                                    <h3 class="m-b-0 text-danger">@{{ outOfStockItemsCount }}</h3>
                                    <h6 class="text-muted m-b-0">Total Out Of Stock Items</h6></div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>

                @include('stock._inc.low-stock')
                @include('stock._inc.out-of-stock')
            </div>
            <div class="col-lg-3 col-md-6">
                {{--<div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Stock Summary</h4>
                        <h6 class="card-subtitle m-b-0">{{ carbon()->now()->format('F j, Y') }}</h6>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h3 class="text-success">254</h3>
                                <h6 class="text-muted">Quantity in Hand</h6>
                                <hr>
                                <h3 class="text-green">450</h3>
                                <h6 class="text-muted">Quantity to be Received</h6>
                            </div>
                        </div>
                    </div>
                </div>--}}
            </div>
        </div>
    </section>
@endsection
@section('script')
    @parent
    <script src="{{ asset('js/vendor/object-helper.js') }}"></script>
    <script>
        app.controller('StockSummaryController', ['$scope', '$http', function ($scope, $http) {
            /** get customer count */
            var lowStockItemsRoute = '{{ route('stock.summary.low.stock') }}';
            $scope.lowStockItems = [];
            $scope.lowStockItemsCount = 0;
            $http.get(lowStockItemsRoute + '?ajax=true').then(function (response) {
                $scope.lowStockItems = response.data;
                $scope.lowStockItemsCount = _.toArray($scope.lowStockItems).length;
            });


            var OutOfStockItemsRoute = '{{ route('stock.summary.out.of.stock') }}';
            $scope.outOfStockItems = [];
            $scope.outOfStockItemsCount = 0;
            $http.get(OutOfStockItemsRoute + '?ajax=true').then(function (response) {
                $scope.outOfStockItems = response.data;
                $scope.outOfStockItemsCount = _.toArray($scope.outOfStockItems).length;
            });

            /** get all sales order count */
            var StockCountRoute = '{{ route('stock.summary.index', ['model' => 'Stock']) }}';
            $http.get(StockCountRoute + '?ajax=true').then(function (response) {
                $scope.stockCount = response.data ? response.data.count : 0;
            });
        }]);
    </script>
@endsection

