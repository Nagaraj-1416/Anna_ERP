@extends('layouts.master')
@section('title', 'Draft Commission')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ form()->open(['url' => route('finance.commission.store', [$rep, $year, $month]), 'method' => 'POST', 'files' => true]) }}
                        @include('finance.commission._inc.form')
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
