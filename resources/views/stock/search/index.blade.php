@extends('layouts.master')
@section('title', 'Stock Search')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Stock') !!}
@endsection
@section('content')
    <div class="row" ng-controller="StockSearchController">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-b-0">
                    <div class="row">
                        <div class="pull-left col-md-9">
                            <h3 class="card-title"><i class="ti-package"></i> Stock Search</h3>
                            <h6 class="card-subtitle">Available stocks as
                                at {{ carbon()->now()->format('F j, Y') }}</h6>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group required @{{ hasError('shop') ? 'has-danger' : '' }}">
                                <div class="ui fluid  search selection dropdown product-drop-down @{{ hasError('shop') ? 'error' : '' }}">
                                    <input type="hidden" name="shop">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a product / item</div>
                                    <div class="menu">
                                        @foreach(productDropDown() as $key => $shop)
                                            <div class="item" data-value="{{ $key }}">{{ $shop }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                <p class="form-control-feedback">@{{ hasError('shop') ? hasError('shop') : ''
                                    }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="loading" ng-show="loading">
                            <div class="loading-dot"></div>
                            <div class="loading-dot"></div>
                            <div class="loading-dot"></div>
                            <div class="loading-dot"></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="loading" ng-show="loading">
                            <p>Please wait, stock lists loading...</p>
                        </div>
                    </div>
                </div>
                <div class="card-body" ng-show="!loading">
                    @include('stock.search._inc.search')
                </div>
            </div>
        </div>
    </div>
@endsection
@section('style')
    <style>
    </style>
@endsection
@section('script')
    <script src="{{ asset('js/vendor/object-helper.js') }}"></script>
    @include('general.helpers')
    @include('stock.search.script')
@endsection