@extends('layouts.master')
@section('title', 'Edit Supplier')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Purchase') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Supplier Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($supplier, ['url' => route('purchase.supplier.update', $supplier), 'method' => 'PATCH', 'enctype' => 'multipart/form-data']) !!}
                        @include('purchases.supplier._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Update') !!}
                        {!! form()->bsCancel('Cancel', 'purchase.supplier.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
