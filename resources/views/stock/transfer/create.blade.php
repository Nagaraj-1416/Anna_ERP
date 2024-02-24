@extends('layouts.master')
@section('title', 'Transfer Stock')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Stock') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Transfer Stocks</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($store, ['url' => route('stock.transfer.store', $store), 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                        @include('stock.transfer._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Submit') !!}
                        {!! form()->bsCancel('Cancel', 'stock.transfer.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
