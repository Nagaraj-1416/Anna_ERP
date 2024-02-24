@extends('layouts.master')
@section('title', 'Edit Estimate')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Estimate Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($estimate, ['url' => route('sales.estimate.update', $estimate), 'method' => 'PATCH']) !!}
                        @include('sales.estimate._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Update') !!}
                        {!! form()->bsCancel('Cancel', 'sales.order.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
