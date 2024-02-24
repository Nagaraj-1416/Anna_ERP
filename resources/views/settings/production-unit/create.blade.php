@extends('layouts.master')
@section('title', 'Create Production Unit')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Production Unit Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'setting.production.unit.store', 'method' => 'POST']) }}
                        @include('settings.production-unit._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Submit') !!}
                        {!! form()->bsCancel('Cancel', 'setting.production.unit.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
