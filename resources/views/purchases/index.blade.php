@extends('layouts.master')
@section('title', 'Purchase')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Purchase') !!}
@endsection
@section('content')
<section ng-controller="purchaseController" ng-cloak>
    <div class="row">
        <div class="col-lg-3 col-md-6">
            @include('purchases._inc.thing-you-could-do')
            @include('purchases._inc.pos-by-delivery-due')
            @include('purchases._inc.bills-by-settlement-due')

        </div>
        <div class="col-lg-6 col-md-6">
            @include('purchases._inc.widget')
            @include('purchases._inc.prev-year-purchase-compare')
            @include('purchases._inc.monthly-purchase-progress')

        </div>
        <div class="col-lg-3 col-md-6">
            @include('purchases._inc.order-summary')
            @include('purchases._inc.bill-summary')
            @include('purchases._inc.top-ten-suppliers-by-purchase')
            @include('purchases._inc.top-ten-products-by-purchase')

        </div>
    </div>
</section>
@endsection
@section('script')
    @parent
    @include('purchases._inc.script')
@endsection