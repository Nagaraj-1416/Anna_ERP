@extends('layouts.master')
@section('title', 'Edit Product category')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Product category Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($category, ['url' => route('setting.product.category.update', $category), 'method' => 'PATCH', 'enctype' => 'multipart/form-data']) !!}
                        @include('settings.product.category._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Update') !!}
                        {!! form()->bsCancel('Cancel', 'setting.product.category.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
