@extends('layouts.master')
@section('title', 'Sales Location Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row" ng-controller="SalesLocationController">
        <div class="col-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">{{ $salesLocation->code }}</h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                <button id="assign_staff_btn" class="btn waves-effect waves-light btn-info btn-sm">
                                    <i class="fa fa-user-circle-o"></i> Assign Staff
                                </button>
                                <button class="btn waves-effect waves-light btn-info btn-sm" id="assign_products">
                                    <i class="fa fa-product-hunt"></i> Assign Products
                                </button>
                                <a href="{{ route('setting.sales.location.edit', [$salesLocation]) }}"
                                   class="btn waves-effect waves-light btn-primary btn-sm" target="_blank">
                                    <i class="fa fa-pencil"></i> Edit
                                </a>
                            </div>
                            <div class="pull-right"></div>
                        </div>
                    </div>
                    @include('_inc.assign-staff.index', [
                       'actionURL' => route('setting.sales.location.assign.staff', $salesLocation->id),
                       'searchURL' => route('setting.sales.location.staff.search', $salesLocation->id)
                    ])
                    @include('_inc.assign-product.index', [
                                            'actionURL' => route('setting.sales.location.assign.product', $salesLocation->id),
                                            'searchURL' => route('setting.sales.product.search', ['ids' => $salesLocation->products->pluck('id')->toJson()]),
                                        ])
                    <div class="row custom-top-margin">
                        <div class="col-md-9">
                            <div class="card card-body">
                                <h3>
                                    <b>{{ $salesLocation->name }}</b>
                                    <span class="pull-right text-muted">
                                    @if($salesLocation->is_active == 'Yes')
                                        {{ 'Active' }}
                                    @else
                                        {{ 'Inactive' }}
                                    @endif
                                </span>
                                </h3>
                                <hr>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Company</strong>
                                                <br>
                                                <p class="text-muted">
                                                    <a target="_blank"
                                                       href="{{ route('setting.company.show', ['company' => $salesLocation->company]) }}">
                                                        {{ $salesLocation->company->name }}
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Phone</strong>
                                                <br>
                                                <p class="text-muted">{{ $salesLocation->phone or 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Fax</strong>
                                                <br>
                                                <p class="text-muted">{{ $salesLocation->fax or 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Mobile</strong>
                                                <br>
                                                <p class="text-muted">{{ $salesLocation->mobile or 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6"><strong>Email</strong>
                                                <br>
                                                <p class="text-muted">{{ $salesLocation->email or 'None' }}</p>
                                            </div>
                                        </div>
                                        <h5 class="box-title box-title-with-margin">Notes</h5>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12 col-xs-6">
                                                <p class="text-muted">{{ $salesLocation->notes or 'None' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @include('_inc.relation-table.staff', ['model' => $salesLocation])

                        <!-- products -->
                            @include('settings.sales-location._inc.product.anquler', ['products' => $salesLocation->products])
                        </div>
                        <div class="col-md-3">

                            <!-- recent comments -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.comment.index', ['model' => $salesLocation])
                                </div>
                            </div>

                            <!-- recent audit logs -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.log.index', ['model' => $salesLocation, 'modelName' => 'Sales location'])
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('general.comment.script', ['modelId' => $salesLocation->id])
    <script>
        app.controller('SalesLocationController', ['$scope', '$http', function ($scope, $http) {
            $scope.products = @json($salesLocation->products()->with('stock')->get()->toArray());
        }]);
    </script>
@endsection