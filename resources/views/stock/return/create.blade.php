@extends('layouts.master')
@section('title', 'Return Stock')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Stock') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Return Stocks</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($store, ['url' => route('stock.return.store', $store), 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                        @include('stock.return._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Submit') !!}
                        {!! form()->bsCancel('Cancel', 'stock.return.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
