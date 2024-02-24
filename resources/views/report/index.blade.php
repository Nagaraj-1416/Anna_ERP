@extends('layouts.master')
@section('title', 'Reports')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Reports') !!}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body p-b-0">
                <h3 class="card-title"><i class="mdi mdi-chart-areaspline"></i> Reports</h3>
                <h6 class="card-subtitle">Need to place some description about reports module</h6> </div>
                <hr>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs customtab" role="tablist">
                    {{--<li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#reportGeneral" role="tab">
                            <span class="hidden-sm-up"><i class="ti-home"></i></span>
                            <span class="hidden-xs-down" style="font-size: 16px;"><b>General</b></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#reportDaily" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down" style="font-size: 16px;"><b>Daily</b></span>
                        </a>
                    </li>--}}
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#reportPurchase" role="tab">
                            <span class="hidden-sm-up"><i class="ti-email"></i></span>
                            <span class="hidden-xs-down" style="font-size: 16px;"><b>Purchase</b></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#reportSales" role="tab">
                            <span class="hidden-sm-up"><i class="ti-email"></i></span>
                            <span class="hidden-xs-down" style="font-size: 16px;"><b>Sales</b></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#reportExpense" role="tab">
                            <span class="hidden-sm-up"><i class="ti-email"></i></span>
                            <span class="hidden-xs-down" style="font-size: 16px;"><b>Expense</b></span>
                        </a>
                    </li>
                    {{--<li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#reportStock" role="tab">
                            <span class="hidden-sm-up"><i class="ti-email"></i></span>
                            <span class="hidden-xs-down" style="font-size: 16px;"><b>Stock</b></span>
                        </a>
                    </li>--}}
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane" id="reportGeneral" role="tabpanel">
                        @include('report._inc.general')
                    </div>
                    <div class="tab-pane" id="reportDaily" role="tabpanel">
                        @include('report._inc.daily')
                    </div>
                    <div class="tab-pane active" id="reportPurchase" role="tabpanel">
                        @include('report._inc.purchase')
                    </div>
                    <div class="tab-pane" id="reportSales" role="tabpanel">
                        @include('report._inc.sales')
                    </div>
                    <div class="tab-pane" id="reportExpense" role="tabpanel">
                        @include('report._inc.expense')
                    </div>
                    {{--<div class="tab-pane" id="reportStock" role="tabpanel">--}}
                        {{--@include('report._inc.stock')--}}
                    {{--</div>--}}
                </div>
            </div>
        </div>
</div>
@endsection

@section('script')
    <script>

    </script>
@endsection
