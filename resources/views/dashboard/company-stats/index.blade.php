@extends('layouts.master')
@section('title', 'Company Stats')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Company Stats') !!}
@endsection
@section('content')
    <section ng-controller="CompanyStatsController">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-b-0">
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Company Stats</h3>
                        <h6 class="card-subtitle">
                            A <code>Company</code> & <code>Date range</code> filters are required to generate this stats report!
                        </h6>
                    </div>
                    <hr>
                    <div class="card-body">
                        <!-- from to filter -->
                    @include('dashboard.company-stats._inc.filters')
                    @include('dashboard.company-stats._inc.button')

                    <!-- heading section -->
                        <div class="heading-section">
                            <h2 class="text-center"><b>Company Stats</b></h2>
                            <p class="text-center text-muted" ng-show="fromDataForDisplay"><b>From</b> @{{ fromDataForDisplay | date }}
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
                            <!-- Purchase Details -->
                            <div class="row" ng-show="!loading">
                                @include('dashboard.company-stats._inc.purchase')
                            </div>

                            <!-- Sales Details -->
                            <div class="row" ng-show="!loading">
                                @include('dashboard.company-stats._inc.sales')
                            </div>

                            <!-- Expense Details -->
                            <div class="row" ng-show="!loading">
                                @include('dashboard.company-stats._inc.expense')
                            </div>
                            <div ng-show="!loading">
                                @include('dashboard.company-stats._inc.widgets')
                            </div>
                        </div>
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
    @include('dashboard.company-stats.script')
@endsection
