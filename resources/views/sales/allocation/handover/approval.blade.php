@extends('layouts.master')
@section('title', 'Allocation Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row" ng-controller="HandoverController">
        <div class="col-md-12">
            <div class="card card-body">
                {{ form()->open([ 'url' => route('sales.allocation.handover.save', [$allocation, $handover]), 'method' => 'POST', 'files' => true]) }}
                @if(isCashierLevelStaff() || isDirectorLevelStaff() || isAccountLevelStaff())
                    <div class="row">
                        <div class="col-md-12">
                            @include('sales.allocation.handover._inc.detail')
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @include('sales.allocation.handover._inc.cheque')
                        </div>
                    </div>
                    @if($allocation->sales_location == 'Van')
                        <div class="row">
                            <div class="col-md-12">
                                @include('sales.allocation.handover._inc.expense')
                            </div>
                        </div>
                    @endif
                @endif

                <!-- cash breakdown and collection summary -->
                <div class="row">
                    <div class="col-md-4">
                        @include('sales.allocation.handover._inc.cash')
                    </div>
                    <div class="col-md-8">
                        @include('sales.allocation.handover._inc.summary')
                    </div>
                </div>

                @if(isStoreLevelStaff() || isDirectorLevelStaff() || isAccountLevelStaff())
                    <div class="row">
                        <div class="col-md-12">
                            @include('sales.allocation.handover._inc.product')
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-12">
                        @include('sales.allocation.handover._inc.next-day-allocation')
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ route('sales.allocation.show', [$allocation]) }}"
                           class="btn btn-inverse waves-effect waves-light">
                            <i class="fa fa-remove"></i> Cancel
                        </a>
                        {!! form()->bsSubmit('Approve', 'btn btn-success waves-effect waves-light m-r-10 pull-right', 'Save', 'submit') !!}
                    </div>
                </div>
                {{ form()->close() }}
            </div>
        </div>
    </div>
@endsection
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
    <style>
        .error {
            color: red;
        }

        .pad-t-20 {
            padding-top: 20px;
        }
    </style>
@endsection

@section('script')
    @parent
    @include('sales.allocation.handover._inc.script')

    <script>
        $("form").submit(function() {
            $('input[type="checkbox"]').removeAttr("disabled");
        });
    </script>
@endsection