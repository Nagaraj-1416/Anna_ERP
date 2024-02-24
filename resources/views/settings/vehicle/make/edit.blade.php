@extends('layouts.master')
@section('title', 'Edit Vehicle Make')
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
                    {!! form()->model($vehicleMake, ['url' => route('setting.vehicle.make.update', $vehicleMake), 'method' => 'PATCH']) !!}
                        @include('settings.vehicle.make._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Update') !!}
                        {!! form()->bsCancel('Cancel', 'setting.vehicle.make.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
