@extends('layouts.master')
@section('title', 'Edit Role')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Role Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->model($role, [ 'route' => ['setting.role.update', $role->id], 'method' => 'PATCH']) }}
                        @include('settings.role._inc.form')
                        @include('settings.role._inc.permissions')
                        <hr>
                        {!! form()->bsSubmit('Update') !!}
                        {!! form()->bsCancel('Cancel', 'setting.role.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>

@endsection
