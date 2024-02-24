@extends('layouts.master')
@section('title', 'Edit Vehicle Type')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Vehicle Type Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($vehicleType, ['url' => route('setting.vehicle.type.update', $vehicleType), 'method' => 'PATCH']) !!}
                        @include('settings.vehicle.type._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Update') !!}
                        {!! form()->bsCancel('Cancel', 'setting.vehicle.type.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
