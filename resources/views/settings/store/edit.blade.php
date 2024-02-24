@extends('layouts.master')
@section('title', 'Edit Store')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Store Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($store, ['url' => route('setting.store.update', $store), 'method' => 'PATCH']) !!}
                        @include('settings.store._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Update') !!}
                        {!! form()->bsCancel('Cancel', 'setting.store.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
