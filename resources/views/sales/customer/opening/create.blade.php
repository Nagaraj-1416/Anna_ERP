@extends('layouts.master')
@section('title', 'Add Opening Balance and References')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, $customer->display_name) !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h4 class="text-white">Enter Opening Balance Details</h4>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => ['sales.customer.opening.store', $customer->id], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'class' => 'form opining-form']) }}
                        @include('sales.customer.opening._inc.form')
                        <hr>
                    <button type="submit" class="btn btn-btn btn-success waves-effect waves-light m-r-10 opining-submit">
                        <i class="ti-check"></i>
                        Submit
                    </button>
                        {!! form()->bsCancel('Cancel', 'sales.customer.show', [$customer->id], ['class' => 'opening-submit']) !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
