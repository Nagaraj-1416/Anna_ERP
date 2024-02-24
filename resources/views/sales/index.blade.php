@extends('layouts.master')
@section('title', 'Sales')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
<section ng-controller="SalesController" ng-cloak>
    <div class="row">
        <div class="col-lg-3 col-md-6">
            @include('sales._inc.thing-you-could-do')
            @include('sales._inc.top-5-customers-by-sales')
            @include('sales._inc.top-5-rep-by-sales')
        </div>
        <div class="col-lg-6 col-md-6">
            @include('sales._inc.widget')
            @include('sales._inc.prev-year-income-compare')
            @include('sales._inc.monthly-sales-progress')
            {{--@include('sales._inc.sales-invoice-by-due-date')--}}
        </div>
        <div class="col-lg-3 col-md-6">
            @include('sales._inc.sales-order-summary')
            @include('sales._inc.sales-invoice-summary')
            @include('sales._inc.top-5-products-by-sales')
        </div>
    </div>
</section>
@endsection

@section('script')
    @parent
    @include('sales._inc.script')
@endsection
