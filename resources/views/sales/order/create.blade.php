@extends('layouts.master')
@section('title', 'Create Order')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Order Details</h3>
                </div>
                <div class="card-body">
                    @include('sales.order._inc.steps')
                    <hr class="hr-dark">
                    {{ form()->open([ 'route' => 'sales.order.store', 'method' => 'POST', 'files' => true]) }}
                        @include('sales.order._inc.form')
                        <hr>
                        <div class="clearfix">
                            <div class="pull-left">
                                {!! form()->bsCancel('Cancel', 'sales.order.index') !!}
                            </div>
                            <div class="pull-right">
                                {!! form()->bsSubmit('Save as Draft', 'btn btn-primary waves-effect waves-light m-r-10', 'Draft', 'submit') !!}
                                {!! form()->bsSubmit('Create Order', 'btn btn-success waves-effect waves-light m-r-10', 'Save', 'submit') !!}
                            </div>
                        </div>
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
    <div class="hidden po-line-item-cloneable"></div>
@endsection