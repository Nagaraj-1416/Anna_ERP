@extends('layouts.master')
@section('title', 'Create Account Group')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Account Group') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Account Group Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'setting.account.group.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @include('settings.account-group._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Submit') !!}
                        {!! form()->bsCancel('Cancel', 'setting.account.group.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
