@extends('layouts.master')
@section('title', 'Edit Sales Location')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Sales Location Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($salesLocation, ['url' => route('setting.sales.location.update', $salesLocation), 'method' => 'PATCH']) !!}
                        @include('settings.sales-location._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Update') !!}
                        {!! form()->bsCancel('Cancel', 'setting.sales.location.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
