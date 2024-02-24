@extends('layouts.master')
@section('title', 'Create Estimate')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Estimate Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'sales.estimate.store', 'method' => 'POST', 'files' => true]) }}
                        @include('sales.estimate._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Submit', 'btn btn-success waves-effect waves-light m-r-10', 'Save', 'submit') !!}
                        {!! form()->bsCancel('Cancel', 'sales.order.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
    <div class="hidden po-line-item-cloneable"></div>
@endsection
