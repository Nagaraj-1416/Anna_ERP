@extends('layouts.master')
@section('title', 'Clone Credit')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Purchase') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Credit Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($credit, ['url' => route('purchase.credit.copy', $credit), 'method' => 'POST']) !!}
                    @include('purchases.credit._inc.form', ['clone' => true])
                    <hr>
                    {!! form()->bsSubmit('Clone') !!}
                    {!! form()->bsCancel('Cancel', 'purchase.credit.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
