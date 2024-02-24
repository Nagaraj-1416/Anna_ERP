@extends('layouts.master')
@section('title', 'Create Stock')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Stock') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Stock Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'stock.store', 'method' => 'POST']) }}
                        @include('stock.list._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Submit') !!}
                        {!! form()->bsCancel('Cancel', 'stock.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
