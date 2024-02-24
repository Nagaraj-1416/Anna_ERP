@extends('layouts.master')
@section('title', 'Create Cheque')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Cheque Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'finance.cheques.hand.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                    @include('finance.banking.cheques-in-hand._inc.form')
                    <hr>
                    {!! form()->bsSubmit('Submit') !!}
                    {!! form()->bsCancel('Cancel', 'finance.banking.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection