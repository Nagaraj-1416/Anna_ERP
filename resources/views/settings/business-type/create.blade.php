@extends('layouts.master')
@section('title', 'Create Business Type')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Business Type Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'setting.business.type.store', 'method' => 'POST']) }}
                        @include('settings.business-type._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Submit') !!}
                        {!! form()->bsCancel('Cancel', 'setting.business.type.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
