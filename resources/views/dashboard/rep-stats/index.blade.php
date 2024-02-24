@extends('layouts.master')
@section('title', 'Rep Stats')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Rep Stats') !!}
@endsection
@section('content')
<section ng-controller="RepStatsController">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-b-0">
                    <h3 class="card-title"><i class="ti-bar-chart"></i> Rep Stats</h3>
                    <h6 class="card-subtitle">
                        A <code>Route</code> & <code>Date range</code> filters are required to
                        generate this stats report!
                    </h6>
                </div>
                <hr>
                <div class="card-body p-b-5">
                    <div class="form-filter">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group required @{{ hasError('route') ? 'has-danger' : '' }}">
                                    <label class="control-label">Route</label>
                                    <div class="ui fluid  search selection dropdown route-drop-down {{ $errors->has('route') ? 'error' : '' }}">
                                        <input type="hidden" name="route">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose a route</div>
                                        <div class="menu">
                                            @foreach(routeDropDown() as $key => $route)
                                                <div class="item" data-value="{{ $key }}">{{ $route }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="form-control-feedback">@{{ hasError('route') ? hasError('route') : '' }}</p>
                                </div>
                            </div>
                        </div>
                        @include('report.general.date-range')
                    </div>
                    <div class="clearfix m-t-10">
                        <div class="pull-left">
                            <button ng-click="generate(true)" class="btn btn-info" type="button">
                                <i class="ti-filter"></i>
                                Generate
                            </button>
                            <button class="btn btn-inverse" ng-click="resetFilters(true)">
                                <i class="ti-eraser"></i>
                                Reset
                            </button>
                        </div>
                        {{--<div class="pull-right">
                            <a target="_blank" href="@{{ getExportRoute() }}" class="btn btn-danger">
                                <i class="fa fa-file-pdf-o"></i> Export to PDF
                            </a>
                            <a target="_blank" href="@{{ getPrintRoute() }}" class="btn btn-inverse">
                                <i class="fa fa-print"></i> Print View
                            </a>
                        </div>--}}
                    </div>

                    <hr class="hr-dark">

                    <!-- heading section -->
                    <div class="heading-section">
                        <h2 class="text-center"><b>Rep Stats</b></h2>
                        <p class="text-center text-muted" style="padding-bottom: 0 !important;"><b>From</b> @{{ query.fromDate | date}}
                            <b>To</b> @{{ query.toDate | date}}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="loading" ng-show="loading">
                        <div class="loading-dot"></div>
                        <div class="loading-dot"></div>
                        <div class="loading-dot"></div>
                        <div class="loading-dot"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="loading" ng-show="loading">
                        <p>Please wait report is generating</p>
                    </div>
                </div>

                <div class="card-body" ng-hide="loading">
                    <div class="row" ng-show="length">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <table class="table color-table muted-table">
                                <thead>
                                    <tr>
                                        <th colspan="4">@{{ routeName }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center"><b>Total Ran</b></td>
                                        <td class="text-center"><b>Allocated</b></td>
                                        <td class="text-center"><b>Visited</b></td>
                                        <td class="text-center"><b>Not Visited</b></td>
                                    </tr>
                                    <tr style="border-bottom: 2px solid #5c6a71;">
                                        <td class="text-center">@{{ allocations }}</td>
                                        <td class="text-center">@{{ totalAllocated }}</td>
                                        <td class="text-center">@{{ totalVisited }}</td>
                                        <td class="text-center">@{{ totalNotVisited }}</td>
                                    </tr>
                                    <tr>
                                        <td width="15%"><div class="td-bg-warning p-10"></div></td>
                                        <td colspan="3"><cite>Total visited not equal to zero and less than not visited total</cite></td>
                                    </tr>
                                    <tr>
                                        <td width="15%"><div class="td-bg-danger p-10"></div></td>
                                        <td colspan="3"><cite>Total allocated not equal to zero and equal to not visited total</cite></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4"></div>
                    </div>

                    <table class="ui celled structured table collapse-table">
                        <thead>
                            <tr>
                                <th>CUSTOMER DETAILS</th>
                                <th class="text-center table-info" width="10%">ALLOCATED</th>
                                <th class="text-center table-success" width="10%">VISITED</th>
                                <th class="text-center table-danger" width="10%">NOT VISITED</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="visit in visits" ng-show="length"
                                ng-class="visit.total_visited < visit.total_not_visited && visit.total_visited != 0 ? 'td-bg-warning' : ''
                                || visit.total_allocated != 0 && visit.total_allocated == visit.total_not_visited ? 'td-bg-danger' : ''">
                                <td>
                                    <a href="/sales/customer/@{{ visit.id }}" target="_blank">@{{ visit.display_name }}</a>
                                </td>
                                <td class="text-center">@{{ visit.total_allocated }}</td>
                                <td class="text-center">@{{ visit.total_visited }}</td>
                                <td class="text-center">@{{ visit.total_not_visited }}</td>
                            </tr>
                            <tr ng-show="!length">
                                <td colspan="4">No records to display...</td>
                            </tr>
                        </tbody>
                    </table>
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
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    @include('dashboard.rep-stats.script')
@endsection