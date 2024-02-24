@extends('layouts.master')
@section('title', 'Edit Mileage Rate')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Edit Mileage Rate') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Mileage Rate Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->model($mileageRate, [ 'route' => ['setting.mileage.rate.update', $mileageRate->id], 'method' => 'PATCH']) }}
                        @include('settings.mileage-rate._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Update') !!}
                        {!! form()->bsCancel('Cancel', 'setting.mileage.rate.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>

@endsection
