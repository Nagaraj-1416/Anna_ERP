@extends('layouts.master')
@section('title', 'Edit Company')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Company Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($company, ['url' => route('setting.company.update', $company), 'method' => 'PATCH', 'enctype' => 'multipart/form-data']) !!}
                        @include('settings.company._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Update') !!}
                        {!! form()->bsCancel('Cancel', 'setting.company.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
