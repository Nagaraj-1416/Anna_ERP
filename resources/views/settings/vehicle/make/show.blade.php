@extends('layouts.master')
@section('title', 'Vehicle Make Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">{{ $vehicleMake->name }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-xs-6 b-r"> <strong>Name</strong>
                            <br>
                            <p class="text-muted">{{ $vehicleMake->name }}</p>
                        </div>
                        <div class="col-md-3 col-xs-6 b-r"> <strong>Is Active</strong>
                            <br>
                            <p class="text-muted">{{ $vehicleMake->is_active }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
