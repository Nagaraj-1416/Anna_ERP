@extends('layouts.master')
@section('title', 'Edit Stock')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Stock') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Stock Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($stock, ['url' => route('stock.update', $stock), 'method' => 'PATCH']) !!}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    {!! form()->bsText('minimum_stock_level', 'Minimum stock level', $stock->min_stock_level, ['placeholder' => 'enter mim stock level']) !!}
                                </div>
                            </div>
                        </div>
                        <hr>
                        {!! form()->bsSubmit('Update') !!}
                        {!! form()->bsCancel('Cancel', 'stock.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
