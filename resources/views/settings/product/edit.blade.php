@extends('layouts.master')
@section('title', 'Edit Product')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Product Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($product, ['url' => route('setting.product.update', $product), 'method' => 'PATCH', 'enctype' => 'multipart/form-data']) !!}
                        @include('settings.product._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Update') !!}
                        {!! form()->bsCancel('Cancel', 'setting.product.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
