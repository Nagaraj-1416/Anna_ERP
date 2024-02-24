@extends('layouts.master')
@section('title', 'Create Department')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Department Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'setting.department.store', 'method' => 'POST']) }}
                        @include('settings.department._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Submit') !!}
                        {!! form()->bsCancel('Cancel', 'setting.department.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
