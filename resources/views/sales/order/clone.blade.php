@extends('layouts.master')
@section('title', 'Clone Order')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Order Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($order, ['url' => route('sales.order.copy', $order), 'method' => 'POST']) !!}
                        @include('sales.order._inc.form', ['clone' => true])
                        <hr>
                        {!! form()->bsSubmit('Clone') !!}
                        {!! form()->bsCancel('Cancel', 'sales.order.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
