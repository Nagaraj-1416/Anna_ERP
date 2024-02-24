@extends('layouts.master')
@section('title', 'Create Rep')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Rep Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'setting.rep.store', 'method' => 'POST']) }}
                        @include('settings.rep._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Submit') !!}
                        {!! form()->bsCancel('Cancel', 'setting.rep.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
