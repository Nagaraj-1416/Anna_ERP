@extends('layouts.master')
@section('title', 'Edit Invoice')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Invoice Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($invoice, ['url' => route('sales.invoice.update', $invoice), 'method' => 'PATCH']) !!}
                        @include('sales.invoice._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Update') !!}
                        {!! form()->bsCancel('Cancel', 'sales.invoice.show', [$invoice]) !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
