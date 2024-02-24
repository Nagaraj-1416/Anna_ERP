@extends('layouts.master')
@section('title', 'Edit Staff')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Staff Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->model($staff, [ 'route' => ['setting.staff.update', $staff->id], 'method' => 'PATCH', 'enctype' => 'multipart/form-data']) }}
                    @include('settings.staff._inc.form')
                    <hr>
                    {!! form()->bsSubmit('Update') !!}
                    {!! form()->bsCancel('Cancel', 'setting.staff.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
