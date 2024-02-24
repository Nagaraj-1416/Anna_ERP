@extends('layouts.master')
@section('title', 'Edit Account Group')
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
                    {!! form()->model($accountGroup, ['url' => route('setting.account.group.update', $accountGroup), 'method' => 'PATCH', 'enctype' => 'multipart/form-data']) !!}
                        @include('settings.account-group._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Update') !!}
                        {!! form()->bsCancel('Cancel', 'setting.account.group.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
