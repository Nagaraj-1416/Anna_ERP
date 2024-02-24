@extends('layouts.master')
@section('title', 'Create GRN')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Purchase') !!}
@endsection
@section('content')
<div class="row" ng-controller="GrnController">
    <div class="col-12">
        <div class="card card-outline-info">
            <div class="card-header">
                <h3 class="text-white">GRN Details</h3>
            </div>
            <div class="card-body">
                {{ form()->open([ 'route' => 'purchase.grn.store', 'method' => 'POST', 'files' => true]) }}
                @include('purchases.grn._inc.form')
                <hr>
                <div class="clearfix">
                    <div class="pull-left">
                        {!! form()->bsCancel('Cancel', 'purchase.order.index') !!}
                    </div>
                    <div class="pull-right">
                        {!! form()->bsSubmit('Create GRN', 'btn btn-success waves-effect waves-light m-r-10', 'Save', 'submit') !!}
                    </div>
                </div>
                {{ form()->close() }}
            </div>
        </div>
    </div>
</div>
@endsection
