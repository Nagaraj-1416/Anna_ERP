@extends('layouts.master')
@section('title', 'Create Stock')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row" ng-controller="DailyStockController">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Allocation Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'daily.stock.store.shop', 'method' => 'POST']) }}
                        @include('sales.allocation.stock.shop.form')
                        <hr>
                        {!! form()->bsSubmit('Submit') !!}
                        {!! form()->bsCancel('Cancel', 'daily.stock.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
