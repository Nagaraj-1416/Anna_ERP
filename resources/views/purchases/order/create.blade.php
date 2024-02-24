@extends('layouts.master')
@section('title', 'Create Order')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Purchase') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Order Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'purchase.order.store', 'method' => 'POST', 'files' => true]) }}
                        @include('purchases.order._inc.form')
                        <hr>
                        <div class="clearfix">
                            <div class="pull-left">
                                {!! form()->bsCancel('Cancel', 'purchase.order.index') !!}
                            </div>
                            <div class="pull-right">
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
