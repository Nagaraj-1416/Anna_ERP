@extends('layouts.master')
@section('title', 'Create Customer')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Customer Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'sales.customer.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @include('sales.customer._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Submit') !!}
                        {!! form()->bsCancel('Cancel', 'sales.customer.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
