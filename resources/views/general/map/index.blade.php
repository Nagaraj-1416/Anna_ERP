@extends('layouts.master')
@section('title', 'Map')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Location') !!}
@endsection
@section('content')
    <div class="row">
        <div id="map"></div>
    </div>
@endsection
@section('script')
    @include('general.map.script')
@endsection
@section('style')
    <style>
        #map {
            height: 800px;
            width: 100%;
        }
    </style>
@endsection