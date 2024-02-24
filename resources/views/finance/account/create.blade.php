@extends('layouts.master')
@section('title', 'Create Account')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Account Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'finance.account.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                    @include('finance.account._inc.form')
                    <hr>
                    {!! form()->bsSubmit('Submit') !!}
                    {!! form()->bsCancel('Cancel', 'finance.account.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection