@extends('layouts.master')
@section('title', 'Edit Account')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Account Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($account, ['url' => route('finance.account.update', $account), 'method' => 'PATCH', 'enctype' => 'multipart/form-data']) !!}
                    @include('finance.account._inc.form')
                    <hr>
                    {!! form()->bsSubmit('Update') !!}
                    {!! form()->bsCancel('Cancel', 'finance.account.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection