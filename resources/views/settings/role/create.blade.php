@extends('layouts.master')
@section('title', 'Create Role')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Role Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'setting.role.store', 'method' => 'POST']) }}
                        @include('settings.role._inc.form')
                        @include('settings.role._inc.permissions')
                        <hr>
                        {!! form()->bsSubmit('Submit') !!}
                        {!! form()->bsCancel('Cancel', 'setting.role.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
