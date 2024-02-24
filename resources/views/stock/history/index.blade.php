@extends('layouts.master')
@section('title', 'Stock History')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Stock') !!}
@endsection
@section('content')
    <div class="row" ng-controller="StockHistoryController">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-b-0">
                    <div class="pull-left">
                        <h3 class="card-title"><i class="ti-package"></i> Stock history</h3>
                        <h6 class="card-subtitle">Available stocks as
                            at {{ carbon()->now()->format('F j, Y') }}</h6>
                    </div>
                </div>
                <hr>
                <div class="card-body" style="min-height: 200px">
                    <div  class="stock-preloader">
                        <svg class="circular" viewBox="25 25 50 50">
                            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                                    stroke-miterlimit="10"/>
                        </svg>
                    </div>
                    <div class="table-responsive">
                        <table class="table-nested table color-table muted-table borderless">
                            <thead>
                                <tr>
                                    <th>Company, van, store, shop, product details</th>
                                    <th style="width: 20%" class="text-left">Stock re-order level</th>
                                    <th style="width: 20%" class="text-left">Available stock</th>
                                </tr>
                            </thead>
                            <tbody ng-if="stokes.length == 0">
                            <tr>
                                <td colspan="8" class="child-row-table">
                                    <p class="pl-3 no-data-info text-danger">
                                        No records found.
                                    </p>
                                </td>
                            </tr>
                            </tbody>
                            <tbody ng-class="{opened: stoke.opened}"
                                   ng-include="&#39;/template/stokeTableTree.tpl.html&#39;"
                                   ng-repeat="stoke in stokes" stock-loop></tbody>
                        </table>
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
    @include('stock.history.script')
@endsection
