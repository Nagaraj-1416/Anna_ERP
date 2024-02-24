@extends('layouts.master')
@section('title', 'Restore Stocks')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Restore Stocks | {{ $item->product->name }}</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($allocation, ['url' => route('sales.allocation.item.do.restore.stock', ['allocation' => $allocation, 'item' => $item]), 'method' => 'PATCH', 'enctype' => 'multipart/form-data']) !!}

                    <div class="row">
                        <div class="col-md-3">
                            {!! form()->bsText('actual_av_stock', 'Actual available stock', getAvailableQty($item), ['placeholder' => 'actual available stock', 'class' => 'form-control', 'readonly' => 'readonly']) !!}
                        </div>
                        <div class="col-md-3">
                            {!! form()->bsText('shortage_qty', 'Additional shortage qty', $item->shortage_qty ?? 0, ['placeholder' => 'shortage qty', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-3">
                            {!! form()->bsText('excess_qty', 'Additional excess qty', $item->excess_qty ?? 0, ['placeholder' => 'excess qty', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-3">
                            {!! form()->bsText('restored_qty', 'Restoring qty', null, ['placeholder' => 'restoring qty', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <p><code style="font-size: 18px;">NOTE:</code> Actual available stock is included with already occurred <b>Shortages & Excess</b> during the sales. If you have additional <b>Shortages & Excess</b> add them into above fields and restore.</p>
                    <hr>
                    {!! form()->bsSubmit('Restore', 'btn btn-success waves-effect waves-light m-r-10', 'Restore', 'submit') !!}
                    {!! form()->bsCancel('Cancel', 'sales.allocation.show', [$allocation]) !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
