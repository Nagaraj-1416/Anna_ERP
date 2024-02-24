@extends('layouts.master')
@section('title', 'Create Allocation')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row" ng-controller="AllocationController">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Allocation Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'sales.allocation.store', 'method' => 'POST', 'files' => true, 'id' => 'allocationForm']) }}
                        @include('sales.allocation._inc._form')
                        <hr>
                        {!! form()->bsSubmit('Submit', 'btn btn-success waves-effect waves-light m-r-10', 'Save', 'submit') !!}
                        {!! form()->bsSubmit('Save as Draft', 'btn btn-success waves-effect waves-light m-r-10', 'Draft', 'submit') !!}
                        {!! form()->bsCancel('Cancel', 'sales.allocation.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
