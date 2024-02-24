@extends('layouts.master')
@section('title', 'Update Daily Stock')
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
                    {!! form()->model($dailyStock, ['url' => route('daily.stock.update.shop', $dailyStock), 'method' => 'PATCH', 'enctype' => 'multipart/form-data']) !!}
                        @include('sales.allocation.stock.shop.form')
                        <hr>
                        {!! form()->bsSubmit('Submit') !!}
                        {!! form()->bsCancel('Cancel', 'daily.stock.show', $dailyStock) !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
