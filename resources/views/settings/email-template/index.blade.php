@extends('layouts.master')
@section('title', 'Email templates')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Email templates') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        @foreach($templates as $template)
                            <div class="col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">{{ $template->name or '' }}</h4>
                                        <p class="card-text">{{ $template->description or '' }}</p>
                                        <a href="{{ route('setting.email.template.edit', $template->id) }}" class="btn btn-primary">Edit Template</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
@endsection

@section('script')
@endsection