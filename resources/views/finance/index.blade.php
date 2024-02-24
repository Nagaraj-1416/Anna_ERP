@extends('layouts.master')
@section('title', 'Finance')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
<section ng-controller="FinanceController" ng-cloak>
    <div class="row">
        <div class="col-lg-3 col-md-6">
            @include('finance._inc.thing-you-could-do')
        </div>
        <div class="col-lg-6 col-md-6">
            @include('finance._inc.cash-flow')
            @include('finance._inc.profit-loss')
        </div>
        <div class="col-lg-3 col-md-6">
            @include('finance._inc.receivable-summary')
            @include('finance._inc.payable-summary')
        </div>
    </div>
</section>
@endsection
