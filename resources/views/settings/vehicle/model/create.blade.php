@extends('layouts.master')
@section('title', 'Create Vehicle Model')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Vehicle Make Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'setting.vehicle.model.store', 'method' => 'POST']) }}
                        @include('settings.vehicle.model._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Submit') !!}
                        {!! form()->bsCancel('Cancel', 'setting.vehicle.model.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
