@extends('layouts.master')
@section('title', 'Edit Production Unit')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Production Unit Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($productionUnit, ['url' => route('setting.production.unit.update', $productionUnit), 'method' => 'PATCH']) !!}
                        @include('settings.production-unit._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Update') !!}
                        {!! form()->bsCancel('Cancel', 'setting.production.unit.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
