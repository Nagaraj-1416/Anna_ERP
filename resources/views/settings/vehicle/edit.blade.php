@extends('layouts.master')
@section('title', 'Edit Vehicle')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Vehicle Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($vehicle, ['url' => route('setting.vehicle.update', $vehicle), 'method' => 'PATCH', 'enctype' => 'multipart/form-data']) !!}
                    @include('settings.vehicle._inc.form')
                    <hr>
                    {!! form()->bsSubmit('Update') !!}
                    {!! form()->bsCancel('Cancel', 'setting.vehicle.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
