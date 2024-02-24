@extends('layouts.master')
@section('title', 'Sales Visits')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales Visits') !!}
@endsection
@section('content')
    <section ng-controller="VisitStatsController">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-b-0">
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Sales Visits</h3>
                        <h6 class="card-subtitle">
                            A <code>Company</code>, <code>Route</code> & <code>Date range</code> filters are required to
                            generate this stats report!
                        </h6>
                    </div>
                    <hr>
                    <div class="card-body">
                        <!-- from to filter -->
                    @include('dashboard.visit-stats._inc.filters')
                    @include('dashboard.visit-stats._inc.button')

                    <!-- heading section -->
                        <div class="heading-section">
                            <h2 class="text-center"><b>Sales Visits</b></h2>
                            <p class="text-center text-muted" ng-show="fromDataForDisplay"><b>From</b> @{{
                                fromDataForDisplay | date }}
                                <b>To</b> @{{ toDateForDisplay | date }}</p>
                        </div>
                        <div class="row">
                            <div class="loading" ng-show="loading">
                                <div class="loading-dot"></div>
                                <div class="loading-dot"></div>
                                <div class="loading-dot"></div>
                                <div class="loading-dot"></div>
                            </div>
                        </div>
                        <div ng-show="filterd" class="m-auto w-500">
                            <div class="text-center alert alert-warning m-t-15">
                                <h6 class="text-center text-muted ng-binding">
                                    No data to display
                                </h6>
                            </div>
                        </div>
                        <div ng-show="!filterd">
                            <div class="row">
                                <div class="loading" ng-show="loading">
                                    <p>Please wait report is generating</p>
                                </div>
                            </div>
                        </div>
                        <div ng-show="!filterd" id="map" style="width: 100%; height: 600px;"></div>

                        <hr ng-show="!filterd">
                        @include('dashboard.visit-stats._inc.customer')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection
@section('script')
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    @include('report.general.date-range-script')
    @include('general.distance-calculator.index')
    @include('dashboard.visit-stats.script')
@endsection
