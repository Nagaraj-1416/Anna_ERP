@extends('layouts.master')
@section('title', 'Make Payment')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Make Payment</h3>
                </div>
                <div class="card-body">
                    {{ form()->open(['url' => route('finance.return.cheques.store.payment', $chequeKey), 'method' => 'POST', 'files' => true]) }}
                        @include('finance.banking.returned-cheques.payment.form')
                        <hr>
                        {!! form()->bsSubmit('Submit', 'btn btn-success waves-effect waves-light m-r-10', 'Save', 'submit') !!}
                        {!! form()->bsCancel('Cancel', 'finance.return.cheques.show', $chequeKey) !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
