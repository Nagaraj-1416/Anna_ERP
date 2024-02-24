@extends('layouts.master')
@section('title', 'Add to Damage Store')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Stock') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Enter Damaged Stock Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($stock, ['url' => route('stock.store.damage', $stock), 'method' => 'POST']) !!}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group  required">
                                        <label for="available_stock" class="control-label form-control-label">Available Stock</label>
                                        <input class="form-control" placeholder="available stock" name="available_stock" type="text" value="{{ $stock->available_stock }}" id="available_stock">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    {!! form()->bsText('damaged_qty', 'Damaged Qty', null, ['placeholder' => 'enter damaged qty']) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter damaged related notes...', 'rows' => '3'], false) !!}
                                </div>
                            </div>
                        </div>
                        <hr>
                        {!! form()->bsSubmit('Add') !!}
                        {!! form()->bsCancel('Cancel', 'stock.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
