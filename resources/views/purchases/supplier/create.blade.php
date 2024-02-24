@extends('layouts.master')
@section('title', 'Create Supplier')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Purchase') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Supplier Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'purchase.supplier.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @include('purchases.supplier._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Submit') !!}
                        {!! form()->bsCancel('Cancel', 'purchase.supplier.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
