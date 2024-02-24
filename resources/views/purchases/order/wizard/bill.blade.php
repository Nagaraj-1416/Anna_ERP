@extends('layouts.master')
@section('title', 'Generate Bill')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Purchase') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Bill Details</h3>
                </div>
                <div class="card-body wizard-content">
                    @include('purchases.order._inc.steps')
                    <hr class="hr-dark">
                    {!! form()->model($order, ['url' => route('purchase.order.generate.bill', $order), 'method' => 'POST']) !!}
                        @include('purchases.order.bill.form')
                        <hr>
                        <div class="clearfix">
                            <div class="pull-left">
                                {!! form()->bsCancel('Cancel', 'purchase.order.show', [$order]) !!}
                            </div>
                            <div class="pull-right">
                                {!! form()->bsSubmit('Generate Bill', 'btn btn-success waves-effect waves-light m-r-10', 'Save', 'submit') !!}
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