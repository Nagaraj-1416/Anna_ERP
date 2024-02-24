@extends('layouts.master')
@section('title', 'Edit Department')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Department Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($department, ['url' => route('setting.department.update', $department), 'method' => 'PATCH']) !!}
                        @include('settings.department._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Update') !!}
                        {!! form()->bsCancel('Cancel', 'setting.department.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
