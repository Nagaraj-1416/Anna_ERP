@extends('layouts.master')
@section('title', 'Edit Vehicle model')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Vehicle Make Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($vehicleModel, ['url' => route('setting.vehicle.model.update', $vehicleModel), 'method' => 'PATCH']) !!}
                        @include('settings.vehicle.model._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Update') !!}
                        {!! form()->bsCancel('Cancel', 'setting.vehicle.model.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
