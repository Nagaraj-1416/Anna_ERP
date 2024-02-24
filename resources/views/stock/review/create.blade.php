@extends('layouts.master')
@section('title', 'review Stock')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Stock') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">{{ $store->name }} | Review Stock Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($store, ['url' => route('stock.review.store', $store), 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                        @include('stock.review._inc.form')
                        <hr>
                        {!! form()->bsSubmit('review') !!}
                        {!! form()->bsCancel('Cancel', 'stock.review.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
