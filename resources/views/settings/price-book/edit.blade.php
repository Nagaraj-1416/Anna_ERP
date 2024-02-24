@extends('layouts.master')
@section('title', 'Edit Price Book')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Edit Price Book') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Price Book Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($priceBook, ['url' => route('setting.price.book.update', $priceBook), 'method' => 'PATCH']) !!}
                        @include('settings.price-book._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Update') !!}
                        {!! form()->bsCancel('Cancel', 'setting.price.book.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
