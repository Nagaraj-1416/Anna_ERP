@extends('layouts.master')
@section('title', 'Create Price Book')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Create Price Book') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Price Book Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'setting.price.book.store', 'method' => 'POST']) }}
                        @include('settings.price-book._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Submit') !!}
                        {!! form()->bsCancel('Cancel', 'setting.price.book.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
