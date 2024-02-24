@extends('layouts.master')
@section('title', 'Create Product')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Product Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'setting.product.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @include('settings.product._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Submit') !!}
                        {!! form()->bsCancel('Cancel', 'setting.product.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
