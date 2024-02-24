@extends('layouts.master')
@section('title', 'Search')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Search') !!}
@endsection
@section('content')
    <div class="row" ng-controller="SearchController">
        <div class="col-md-12">
            <div class="card border-warning">
                <div class="card-body p-b-0">
                    <h3 class="card-title"><i class="ti-search"></i> Results for
                        "{{ isset($_GET['keyword']) && $_GET['keyword'] != null ? $_GET['keyword'] : '' }}"</h3>
                    <h6 class="card-subtitle">About @{{ totalResults | number }} results found for this given
                        keyword</h6>
                </div>
                <hr>

                <div class="card-body">
                    <div ng-show="checkSales()">
                        <div class="m-b-10">
                            <h3>Sales</h3>
                        </div>
                        @include('general.search._inc.sales')
                        <hr>
                    </div>
                    <div ng-show="checkPurchase()">
                        <div class="m-b-10">
                            <h3>Purchase</h3>
                        </div>
                        @include('general.search._inc.purchase')
                        <hr>
                    </div>
                    <div ng-show="checkExpense()">
                        <div class="m-b-10">
                            <h3>Expense</h3>
                        </div>
                        @include('general.search._inc.expense')
                    </div>
                    <div ng-show="checkSetting()">
                        <div class="m-b-10">
                            <h3>Setting</h3>
                        </div>
                        @include('general.search._inc.settings')
                    </div>
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
    @include('general.search.script')
@endsection