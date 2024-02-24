@extends('layouts.master')
@section('title', 'Edit Credit')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Credit Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($credit, ['url' => route('sales.credit.update', $credit), 'method' => 'PATCH']) !!}
                        @include('sales.credit._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Update') !!}
                        {!! form()->bsCancel('Cancel', 'sales.credit.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
