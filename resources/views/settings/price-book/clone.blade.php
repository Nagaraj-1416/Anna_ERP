@extends('layouts.master')
@section('title', 'Clone Price Book')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h3 class="text-white">Price Book Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($priceBook, ['url' => route('setting.price.book.do.clone', $priceBook), 'method' => 'POST']) !!}
                        @include('settings.price-book._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Clone') !!}
                        {!! form()->bsCancel('Cancel', 'setting.price.book.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
