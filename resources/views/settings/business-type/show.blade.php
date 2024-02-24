@extends('layouts.master')
@section('title', 'Business Type Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">{{ $businessType->code.' - '.$businessType->name }}</h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                <a href="{{ route('setting.business.type.edit', [$businessType]) }}" class="btn waves-effect waves-light btn-primary btn-sm" target="_blank">
                                    <i class="fa fa-pencil"></i> Edit
                                </a>
                            </div>
                            <div class="pull-right"></div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-3 col-xs-6"> <strong>Is business type active?</strong>
                            <br>
                            <p class="text-muted">{{ $businessType->is_active }}</p>
                        </div>
                    </div>

                    <h5 class="box-title box-title-with-margin">Notes</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-12 col-xs-6">
                            <p class="text-muted">{{ $businessType->notes or 'None' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
