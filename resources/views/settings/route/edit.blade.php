@extends('layouts.master')
@section('title', 'Edit Route')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Route Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($route, ['url' => route('setting.route.update', $route), 'method' => 'PATCH']) !!}
                    @include('settings.route._inc.form')
                    <hr>
                    {!! form()->bsSubmit('Update') !!}
                    {!! form()->bsCancel('Cancel', 'setting.route.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
