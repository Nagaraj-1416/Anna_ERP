@extends('layouts.master')
@section('title', 'Stocks Out')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Stock') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Stocks Out</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'stock.out.store', 'method' => 'POST']) }}
                    @include('stock.out._inc.form')
                    <hr>
                    {!! form()->bsSubmit('Submit') !!}
                    {!! form()->bsCancel('Cancel', 'stock.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection