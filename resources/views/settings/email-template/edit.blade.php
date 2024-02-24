@extends('layouts.master')
@section('title', 'Edit email template')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Edit email template') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Available legends</h3>
                </div>
                <div class="card-body">
                    @include('settings.email-template._inc.variable')
                    @include('settings.email-template._inc.links')
                    @include('settings.email-template._inc.loops')
                </div>
            </div>
        </div>
        <div class="col-md-8">
            {{ form()->open(['id' => 'email_template_form', 'method' => 'patch', 'url' => route('setting.email.template.update', [$template])])}}
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Email Content</h3>
                </div>
                <div class="card-body" id="email-template">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <h4><label for="subject" class="control-label form-control-label">Subject</label></h4>
                                <textarea name="subject" id="subject">{!! render_email_template($template)->getAttribute('subject') !!}</textarea>
                            </div>
                            <br/>
                            <div class="form-group">
                                <h4><label for="content" class="control-label form-control-label">Body</label></h4>
                                <textarea name="content" id="content">{!! render_email_template($template)->getAttribute('content') !!}</textarea>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-btn btn-success waves-effect waves-light m-r-10">
                        <i class="fa fa-pencil"></i>
                        Update
                    </button>
                    <a href="{{ route('setting.email.template.index') }}" class="btn btn-inverse waves-effect waves-light">
                        <i class="fa fa-times-circle-o"></i> Cancel
                    </a>
                </div>
                <div class="hidden" id="clones">
                    <code class="code"></code>
                </div>
            </div>
            {{ form()->close() }}
        </div>
    </div>
    @include('settings.email-template._inc.modals')
@endsection

@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('plugins/ckeditor/styles.js') }}">
    @include('settings.email-template._inc.styles')
@endsection

@section('script')
    @parent
    <script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
    @include('settings.email-template._inc.scripts')
@endsection