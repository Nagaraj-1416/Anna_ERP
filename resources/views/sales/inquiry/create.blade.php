@extends('layouts.master')
@section('title', 'Create Inquiry')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Inquiry Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'sales.inquiries.store', 'method' => 'POST', 'files' => true]) }}
                        @include('sales.inquiry._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Submit', 'btn btn-success waves-effect waves-light m-r-10', 'Save', 'submit') !!}
                        {!! form()->bsCancel('Cancel', 'sales.inquiries.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
    <div class="hidden po-line-item-cloneable"></div>
@endsection
