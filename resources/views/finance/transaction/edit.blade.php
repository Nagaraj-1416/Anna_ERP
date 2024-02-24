@extends('layouts.master')
@section('title', 'Edit Company')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Edit Company') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Company Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($trans, ['url' => route('finance.trans.update', $trans), 'method' => 'PATCH', 'enctype' => 'multipart/form-data']) !!}
                        @include('finance.transaction._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Update') !!}
                        {!! form()->bsCancel('Cancel', 'finance.trans.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
