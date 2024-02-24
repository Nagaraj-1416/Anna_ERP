@extends('layouts.master')
@section('title', 'Edit Business Type')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Business Type Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($businessType, ['url' => route('setting.business.type.update', $businessType), 'method' => 'PATCH']) !!}
                        @include('settings.business-type._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Update') !!}
                        {!! form()->bsCancel('Cancel', 'setting.business.type.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
