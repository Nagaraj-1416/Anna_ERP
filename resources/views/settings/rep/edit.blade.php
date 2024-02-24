@extends('layouts.master')
@section('title', 'Edit Rep')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Rep Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($rep, ['url' => route('setting.rep.update', $rep), 'method' => 'PATCH']) !!}
                    @include('settings.rep._inc.form')
                    <hr>
                    {!! form()->bsSubmit('Update') !!}
                    {!! form()->bsCancel('Cancel', 'setting.rep.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
