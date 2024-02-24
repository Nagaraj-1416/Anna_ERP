@extends('layouts.master')
@section('title', 'Dashboard')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Dashboard') !!}
@endsection
@section('content')
    <section ng-controller="DashboardController">
        <div class="row">
            <div class="col-md-9">
                @if(isStoreLevelStaff())
                    @include('_inc.daily-stock.add', ['visible' => true])
                @endif
                @if(isDirectorLevelStaff() || isAccountLevelStaff())
                    @include('dashboard._inc.sales-summary')
                @endif
                {{--@if(isDirectorLevelStaff() || isAccountLevelStaff())
                    @include('dashboard._inc.sales-summary')
                    <div class="row">
                        <div class="col-md-4">
                            --}}{{--@include('dashboard._inc.payable-and-owing')--}}{{--
                            @include('dashboard._inc.overdue-invoice-bill')
                        </div>
                        <div class="col-md-8">--}}
                            {{--@include('dashboard._inc.cash-flow')--}}
                            {{--@include('dashboard._inc.profit-and-loss')--}}
                            {{--@include('dashboard._inc.net-income')--}}
                            {{--@include('dashboard._inc.rates')--}}
                            {{--<div class="row">
                                <div class="col-md-6">
                                    @include('dashboard._inc.sales-allocations')
                                </div>
                                <div class="col-md-6">
                                    @include('dashboard._inc.top-expenses')
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    @if(isShopLevelStaff() || isShopManagerLevelStaff())
                        @include('dashboard._inc.user-sales-summary')
                    @endif
                @endif--}}
            </div>
            <div class="col-md-3">
                @include('dashboard._inc.things-you-could-do')
                @include('dashboard._inc.transfers')
                {{--@if(isStoreLevelStaff())
                    @include('dashboard._inc.today-allocations')
                @endif--}}
                {{--@include('dashboard._inc.reminders')--}}
            </div>
        </div>
    </section>
    @include('general.stats.index')
    {{--@include('dashboard._inc.product.index')--}}
@endsection

@section('script')
    @include('dashboard._inc.script')
    @include('general.stats.script')
    @include('_inc.daily-stock._inc.script')
    @include('dashboard._inc.product.script')
    <script>
        $('.scrollable-widget').slimScroll({
            height: '240px'
        });
    </script>
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('css/vendor/collapse-table.css') }}">
    <style>
        .sidebar-btn {
            cursor: pointer;
        }
    </style>
@endsection
