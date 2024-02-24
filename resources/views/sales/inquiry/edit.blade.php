@extends('layouts.master')
@section('title', 'Edit Inquiry')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Inquiry Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($inquiry, ['url' => route('sales.inquiries.update', $inquiry), 'method' => 'PATCH']) !!}
                        @include('sales.inquiry._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Update') !!}
                        {!! form()->bsCancel('Cancel', 'sales.inquiries.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
