@extends('layouts.master')
@section('title', 'Edit Bill')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Purchase') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Bill Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($bill, ['url' => route('purchase.bill.update', $bill), 'method' => 'PATCH']) !!}
                        @include('purchases.bill._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Update') !!}
                        {!! form()->bsCancel('Cancel', 'purchase.bill.show', [$bill]) !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
