@extends('layouts.master')
@section('title', 'Create Store')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Store Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'setting.store.store', 'method' => 'POST']) }}
                        @include('settings.store._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Submit') !!}
                        {!! form()->bsCancel('Cancel', 'setting.store.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
