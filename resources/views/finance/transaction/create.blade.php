@extends('layouts.master')
@section('title', 'Create Transaction')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Create Transaction') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Transaction Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'finance.trans.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @include('finance.transaction._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Submit') !!}
                        {!! form()->bsCancel('Cancel', 'finance.trans.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
