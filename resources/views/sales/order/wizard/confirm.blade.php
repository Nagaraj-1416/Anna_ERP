@extends('layouts.master')
@section('title', 'Confirm Order')
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
                <div class="card-body wizard-content">
                    @include('sales.order._inc.steps')
                    <hr class="hr-dark">
                    {!! form()->model($order, ['url' => route('sales.order.do.confirm', $order), 'method' => 'POST']) !!}
                        <div class="container">
                            @include('sales.order.export')
                        </div>
                        <hr>
                        <div class="clearfix">
                            <div class="pull-left">
                                {!! form()->bsCancel('Cancel', 'sales.order.show', [$order]) !!}
                            </div>
                            <div class="pull-right">
                                {!! form()->bsSubmit('Confirm Order', 'btn btn-success waves-effect waves-light m-r-10', 'Save', 'submit') !!}
                            </div>
                        </div>
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection