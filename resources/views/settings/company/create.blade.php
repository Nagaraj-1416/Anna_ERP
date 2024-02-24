@extends('layouts.master')
@section('title', 'Create Company')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Company Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'setting.company.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @include('settings.company._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Submit') !!}
                        {!! form()->bsCancel('Cancel', 'setting.company.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
