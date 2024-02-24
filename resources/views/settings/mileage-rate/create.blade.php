@extends('layouts.master')
@section('title', 'Create Mileage Rate')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Create Mileage Rate') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Mileage Rate Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'setting.mileage.rate.store', 'method' => 'POST']) }}
                        @include('settings.mileage-rate._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Submit') !!}
                        {!! form()->bsCancel('Cancel', 'setting.mileage.rate.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
