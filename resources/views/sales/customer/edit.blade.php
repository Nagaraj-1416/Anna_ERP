@extends('layouts.master')
@section('title', 'Edit Customer')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Customer Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($customer, ['url' => route('sales.customer.update', $customer), 'method' => 'PATCH', 'enctype' => 'multipart/form-data']) !!}
                        @include('sales.customer._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Update') !!}
                        {!! form()->bsCancel('Cancel', 'sales.customer.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
