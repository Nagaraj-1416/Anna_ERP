@extends('layouts.master')
@section('title', 'Create Staff')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Staff Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'setting.staff.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @include('settings.staff._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Submit') !!}
                        {!! form()->bsCancel('Cancel', 'setting.staff.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
