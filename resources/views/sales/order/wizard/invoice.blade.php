@extends('layouts.master')
@section('title', 'Generate Invoice')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Invoice Details</h3>
                </div>
                <div class="card-body wizard-content">
                    @include('sales.order._inc.steps')
                    <hr class="hr-dark">
                    {!! form()->model($order, ['url' => route('sales.order.generate.invoice', $order), 'method' => 'POST']) !!}
                        @include('sales.order.invoice.form')
                        <hr>
                        <div class="clearfix">
                            <div class="pull-left">
                                {!! form()->bsCancel('Cancel', 'sales.order.show', [$order]) !!}
                            </div>
                            <div class="pull-right">
                                {!! form()->bsSubmit('Generate Invoice', 'btn btn-success waves-effect waves-light m-r-10', 'Save', 'submit') !!}
                            </div>
                        </div>
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection

@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
@endsection