@extends('layouts.master')
@section('title', 'Create Sales Location')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Sales Location Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'setting.sales.location.store', 'method' => 'POST']) }}
                        @include('settings.sales-location._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Submit') !!}
                        {!! form()->bsCancel('Cancel', 'setting.sales.location.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
