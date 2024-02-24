@extends('layouts.master')
@section('title', 'Route Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row" ng-controller="RouteController">
        <div class="col-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">{{ $route->code }}</h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                <button class="btn waves-effect waves-light btn-info btn-sm" id="add_new_location">
                                    <i class="fa fa-map-marker"></i> Associate location
                                </button>
                                <button class="btn waves-effect waves-light btn-info btn-sm" id="assign_products">
                                    <i class="fa fa-product-hunt"></i> Assign Products
                                </button>
                                <a href="{{ route('setting.route.edit', [$route]) }}"
                                   class="btn waves-effect waves-light btn-primary btn-sm" target="_blank">
                                    <i class="fa fa-pencil"></i> Edit
                                </a>
                                <a href="{{ route('setting.route.edit.qty', [$route]) }}"
                                   class="btn waves-effect waves-light btn-primary btn-sm" target="_blank">
                                    <i class="fa fa-edit"></i> Update Default Qty
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- route location -->
                    @include('settings.route._inc.location.index', ['modal' => $route])

                    <!-- route targets -->
                    @include('_inc.targets.route.index', ['route' => $route])

                    <!-- route products -->
                    @include('_inc.assign-product.index', [
                        'actionURL' => route('setting.route.assign.product', $route->id),
                        'searchURL' => route('setting.sales.product.search', ['ids' => $route->products->pluck('id')->toJson()])
                    ])

                    <div class="row custom-top-margin">
                        <div class="col-md-9">
                            <div class="card card-body">
                                <h3>
                                    <b>{{ $route->name }}</b>
                                    <span class="pull-right text-muted">
                                        @if($route->is_active == 'Yes')
                                            {{ 'Active' }}
                                        @else
                                            {{ 'Inactive' }}
                                        @endif
                                    </span>
                                </h3>
                                <hr>
                                <p class="text-muted">{{ $route->notes }}</p>
                            </div>

                            <!-- customers -->
                            @include('settings.route._inc.customer.anquler',
                             ['customers' => $customers,
                              'exportRoute' => route('setting.route.export.customers', [$route]),
                              'excelExport' => route('setting.route.export.customers', ['route' => $route, 'type' => 'excel'])
                              ])

                            <!-- products -->
                            @include('settings.route._inc.product.anquler',
                             ['products' => $products,
                               'exportRoute' => route('setting.route.export.products', [$route]),
                               'excelExport' => route('setting.route.export.products', ['route' => $route, 'type' => 'excel']),
                               ])

                            <!-- locations -->
                            @include('settings.route._inc.location.list', ['locations' => $route->locations])
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Route Credit Limit</h4>
                                    <hr>
                                    <div>
                                        <h3 class="card-title"><b>{{ number_format($route->cl_amount, 2) }}</b></h3>
                                        <h6 class="card-subtitle">CL used notification at
                                            <b>{{ $route->cl_notify_rate }}%</b></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('settings.route._inc.location.location-form-temp')
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection

@section('script')
    <script src="{{ asset('js/vendor/object-helper.js') }}"></script>
    @include('settings.route._inc.location.script')
    <script>
        app.controller('RouteController', ['$scope', '$http', function ($scope, $http) {
            $scope.customers = @json($route->customers()->get()->toArray());
            $scope.products = @json($route->products()->get()->toArray());

            $scope.removeProductUrl = '{{ route('setting.route.remove.product', ['route' => $route->id, 'product' => 'PRODUCT']) }}';
            $scope.removeProduct = function (product) {
                var deleteUrl = $scope.removeProductUrl.replace('PRODUCT', product.id);
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this action!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DB2828',
                    confirmButtonText: 'Yes, Delete!'
                }).then(function (isConfirm) {
                    if (isConfirm.value) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            data: {'_token': '{{ csrf_token() }}'},
                            success: function (result) {
                                swal(
                                    'Deleted!',
                                    'Product deleted successfully!',
                                    'success'
                                );
                                setTimeout(location.reload(), 300);
                            }
                        });
                    }
                });
            };
        }]);
    </script>
@endsection
