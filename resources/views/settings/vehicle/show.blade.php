@extends('layouts.master')
@section('title', 'Vehicle Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row" ng-controller="VehicleController">
        <div class="col-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">{{ $vehicle->vehicle_no }}</h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                <a href="{{ route('setting.vehicle.edit', [$vehicle]) }}"
                                   class="btn waves-effect waves-light btn-primary btn-sm" target="_blank">
                                    <i class="fa fa-pencil"></i> Edit
                                </a>
                                {{--<a href="#" class="btn waves-effect waves-light btn-info btn-sm" target="_blank">
                                    <i class="fa fa-user-circle-o"></i> Assign Driver
                                </a>--}}
                                <button class="btn waves-effect waves-light btn-info btn-sm" id="renewalBtn">
                                    <i class="fa fa-plus"></i> Add Renewal
                                </button>
                            </div>
                            <div class="pull-right"></div>
                        </div>
                    </div>
                    @include('settings.vehicle._inc.renewal.index', ['model' => $vehicle])
                    <div class="row custom-top-margin">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card card-body">
                                        <h3>
                                            <b>{{ $vehicle->vehicle_no }}</b>
                                            <span class="pull-right text-muted">
                                    @if($vehicle->is_active == 'Yes')
                                                    {{ 'Active' }}
                                                @else
                                                    {{ 'Inactive' }}
                                                @endif
                                </span>
                                        </h3>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="card blog-widget">
                                                    <div class="card-body">
                                                        <div class="blog-image">
                                                            <img src="{{ route('setting.vehicle.image', [$vehicle])}}"
                                                                 alt="img"
                                                                 class="img-responsive">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="row">
                                                    <div class="col-md-3 col-xs-6 b-r"><strong>Company</strong>
                                                        <br>
                                                        <p class="text-muted">
                                                            <a target="_blank"
                                                               href="{{ route('setting.company.show', [$vehicle->company]) }}">{{ $vehicle->company->code.' - '.$vehicle->company->name }}</a>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6"><strong>Register date</strong>
                                                        <br>
                                                        <p class="text-muted">{{ $vehicle->reg_date or 'None' }}</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3 col-xs-6 b-r"><strong>Engine no</strong>
                                                        <br>
                                                        <p class="text-muted">{{ $vehicle->engine_no or 'None' }}</p>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6 b-r"><strong>Chassis no</strong>
                                                        <br>
                                                        <p class="text-muted">{{ $vehicle->chassis_no or 'None' }}</p>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6 b-r"><strong>Fuel type</strong>
                                                        <br>
                                                        <p class="text-muted">{{ $vehicle->fuel_type or 'None' }}</p>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6"><strong>Vehicle color</strong>
                                                        <br>
                                                        <p class="text-muted">{{ $vehicle->color or 'None' }}</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3 col-xs-6 b-r"><strong>Vehicle year</strong>
                                                        <br>
                                                        <p class="text-muted">{{ $vehicle->year or 'None' }}</p>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6 b-r"><strong>Vehicle type</strong>
                                                        <br>
                                                        <p class="text-muted">{{ $vehicle->type->name or 'None' }}</p>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6 b-r"><strong>Vehicle make</strong>
                                                        <br>
                                                        <p class="text-muted">{{ $vehicle->make->name or 'None' }}</p>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6"><strong>Vehicle model</strong>
                                                        <br>
                                                        <p class="text-muted">{{ $vehicle->model->name or 'None' }}</p>
                                                    </div>
                                                </div>

                                                <h5 class="box-title box-title-with-margin">Vehicle Specifications</h5>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-3 col-xs-6 b-r"><strong>Body
                                                            type</strong>
                                                        <br>
                                                        <p class="text-muted">{{ $vehicle->type_of_body or 'None' }}</p>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6 b-r"><strong>Seating
                                                            capacity</strong>
                                                        <br>
                                                        <p class="text-muted">{{ $vehicle->seating_capacity or 'None' }}</p>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6 b-r"><strong>Weight
                                                            (Kg)</strong>
                                                        <br>
                                                        <p class="text-muted">{{ $vehicle->weight or 'None' }}</p>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6"><strong>Gross (Kg)</strong>
                                                        <br>
                                                        <p class="text-muted">{{ $vehicle->gross or 'None' }}</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3 col-xs-6 b-r"><strong>Length</strong>
                                                        <br>
                                                        <p class="text-muted">{{ $vehicle->length or 'None' }}</p>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6 b-r"><strong>Width</strong>
                                                        <br>
                                                        <p class="text-muted">{{ $vehicle->width or 'None' }}</p>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6 b-r"><strong>Height</strong>
                                                        <br>
                                                        <p class="text-muted">{{ $vehicle->height or 'None' }}</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3 col-xs-6 b-r"><strong>Front tyre
                                                            size</strong>
                                                        <br>
                                                        <p class="text-muted">{{ $vehicle->tyre_size_front or 'None' }}</p>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6 b-r"><strong>Rear tyre
                                                            size</strong>
                                                        <br>
                                                        <p class="text-muted">{{ $vehicle->tyre_size_rear or 'None' }}</p>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6 b-r"><strong>Front
                                                            wheel</strong>
                                                        <br>
                                                        <p class="text-muted">{{ $vehicle->wheel_front or 'None' }}</p>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6 b-r"><strong>Rear
                                                            wheel</strong>
                                                        <br>
                                                        <p class="text-muted">{{ $vehicle->wheel_rear or 'None' }}</p>
                                                    </div>
                                                </div>
                                                <h5 class="box-title box-title-with-margin">Notes</h5>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-12 col-xs-6">
                                                        <p class="text-muted">{{ $vehicle->notes or 'None' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    @include('settings.vehicle._inc.renewal.table')
                                </div>

                                <div class="col-md-12">
                                    @include('settings.vehicle._inc.reading')
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">

                            <!-- recent comments -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.comment.index', ['model' => $vehicle])
                                </div>
                            </div>

                            <!-- recent audit logs -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.log.index', ['model' => $vehicle, 'modelName' => 'Vehicle'])
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
    @include('general.comment.script', ['modelId' => $vehicle->id])
    <script>
        app.controller('VehicleController', ['$scope', '$http', function ($scope, $http) {
            $scope.renewals = @json($vehicle->renewals->toArray());
            $scope.readings = @json($vehicle->odoMeterReadings()->with('dailySale')->get()->toArray());
        }]);
    </script>
@endsection