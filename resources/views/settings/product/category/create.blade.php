@extends('layouts.master')
@section('title', 'Create Product Category')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Product Category Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'setting.product.category.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                    @include('settings.product.category._inc.form')
                    <hr>
                    {!! form()->bsSubmit('Submit') !!}
                    {!! form()->bsCancel('Cancel', 'setting.product.category.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
