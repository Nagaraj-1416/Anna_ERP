@extends('layouts.master')
@section('title', 'Edit Credit')
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
                    {!! form()->model($credit, ['url' => route('purchase.credit.update', $credit), 'method' => 'PATCH']) !!}
                        @include('purchases.credit._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Update') !!}
                        {!! form()->bsCancel('Cancel', 'purchase.credit.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
