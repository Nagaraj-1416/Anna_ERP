@extends('layouts.master')
@section('title', 'Create Route')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Route Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'setting.route.store', 'method' => 'POST']) }}
                        @include('settings.route._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Submit') !!}
                        {!! form()->bsCancel('Cancel', 'setting.route.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
