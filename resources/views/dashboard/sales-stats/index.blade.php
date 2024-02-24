@extends('layouts.master')
@section('title', 'Sales Stats')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales Stats') !!}
@endsection
@section('content')
    <section ng-controller="SalesStatController">
        {{--<div class="preloader d-none">--}}
        {{--<svg class="circular" viewBox="25 25 50 50">--}}
        {{--<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>--}}
        {{--</svg>--}}
        {{--</div>--}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-b-0">
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Sales Stats</h3>
                        <h6 class="card-subtitle">
                            A <code>Company</code>, <code>Rep</code> & <code>Date range</code> filters are required to
                            generate this stats report!
                        </h6>
                    </div>
                    <hr>
                    <div class="card-body">

                        <!-- from to filter -->
                        <div class="form-filter">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group required @{{ hasError('company') ? 'has-danger' : '' }}">
                                        <label class="control-label">Company</label>
                                        <div class="ui fluid  search selection dropdown company-drop-down {{ $errors->has('company') ? 'error' : '' }}">
                                            <input type="hidden" name="company">
                                            <i class="dropdown icon"></i>
                                            <div class="default text">choose a company</div>
                                            <div class="menu">
                                                @foreach(companyDropDown() as $key => $company)
                                                    <div class="item" data-value="{{ $key }}">{{ $company }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <p class="form-control-feedback">@{{ hasError('company') ? hasError('company') :
                                            '' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group required @{{ hasError('rep') ? 'has-danger' : '' }}">
                                        <label class="control-label">Sales rep</label>
                                        <div class="ui fluid  search selection dropdown rep-drop-down {{ $errors->has('rep') ? 'error' : '' }}">
                                            <input type="hidden" name="rep">
                                            <i class="dropdown icon"></i>
                                            <div class="default text">choose a sales rep</div>
                                            <div class="menu"></div>
                                        </div>
                                        <p class="form-control-feedback">@{{ hasError('rep') ? hasError('rep') : ''
                                            }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    {!! form()->bsText('from_date', 'From date', null, ['placeholder' => 'pick a from date', 'class' => 'form-control datepicker', 'ng-model' => 'query.fromDate']) !!}
                                </div>
                                <div class="col-md-3">
                                    {!! form()->bsText('to_date', 'To date', null, ['placeholder' => 'pick a to date', 'class' => 'form-control datepicker', 'ng-model' => 'query.toDate']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="clearfix m-t-10">
                            <div class="pull-left">
                                <button ng-click="generate(true)" class="btn btn-info" type="button"><i
                                            class="ti-filter"></i>
                                    Generate
                                </button>
                                <button class="btn btn-inverse" ng-click="resetFilters(true)"><i class="ti-eraser"></i>
                                    Reset
                                </button>
                            </div>
                            <div class="pull-right">
                                <a target="_blank" href="@{{ getExportRoute() }}" class="btn btn-danger"><i
                                            class="fa fa-file-pdf-o"></i> Export to PDF</a>
                                <a target="_blank" href="@{{ getPrintRoute() }}" class="btn btn-inverse"><i
                                            class="fa fa-print"></i> Print View</a>
                            </div>
                        </div>
                        <hr class="hr-dark">

                        <!-- heading section -->
                        <div class="heading-section">
                            <h2 class="text-center"><b>Sales Stats</b></h2>
                            <p class="text-center text-muted"><b>From</b> @{{ query.fromDate | date}}
                                <b>To</b> @{{ query.toDate | date}}</p>
                        </div>
                        <hr ng-show="!filterd">
                        <div class="row">
                            <div class="loading" ng-show="loading">
                                <div class="loading-dot"></div>
                                <div class="loading-dot"></div>
                                <div class="loading-dot"></div>
                                <div class="loading-dot"></div>
                            </div>
                        </div>
                        <div ng-show="filterd" class="m-auto w-500">
                            <div class="text-center alert alert-warning">
                                <h6 class="text-center text-muted ng-binding">
                                    No data to display
                                </h6>
                            </div>
                        </div>
                        <!-- summary section -->
                        <div ng-show="!filterd" class="sales-summary">
                            <div class="row">
                                <div class="col-md-3">
                                    <p><b>Company:</b> @{{ companyName ? companyName : 'None' }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p><b>Sales rep:</b> @{{ repName ? repName : 'None' }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <table class="table color-table muted-table">
                                        <thead>
                                        <tr>
                                            <th colspan="2">Orders Summary</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="text-left">No of orders</td>
                                            <td class="text-right">@{{ orderDetails.totalSalesOrders }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Sales</td>
                                            <td class="text-right">@{{ orderDetails.totalSales | number:2 }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Invoiced</td>
                                            <td class="text-right">@{{ orderDetails.totalInvoiced | number:2 }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Received</td>
                                            <td class="text-right">(@{{ orderDetails.totalPaid | number:2 }})</td>
                                        </tr>
                                        <tr style="border-top: 2px solid #5c6a71;">
                                            <td class="text-left"><b>Balance</b></td>
                                            <td class="text-right"><b>@{{ orderBalance() | number:2}}</b></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-3">
                                    <table class="table color-table muted-table">
                                        <thead>
                                        <tr>
                                            <th colspan="2">Payments Summary</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="text-left">Cash</td>
                                            <td class="text-right">@{{ paymentsData.cash | number:2 }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Cheque</td>
                                            <td class="text-right">@{{ paymentsData.cheque | number:2}}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Deposit</td>
                                            <td class="text-right">@{{ paymentsData.deposit | number:2}}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Credit Card</td>
                                            <td class="text-right">@{{ paymentsData.card | number:2}}</td>
                                        </tr>
                                        <tr style="border-top: 2px solid #5c6a71;">
                                            <td class="text-left"><b>Received</b></td>
                                            <td class="text-right"><b>@{{ paymentTotal() | number:2 }}</b></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-3">
                                    <table class="table color-table muted-table">
                                        <thead>
                                        <tr>
                                            <th colspan="2">Sales Visits Summary</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="text-left text-info">Allocated</td>
                                            <td class="text-right text-info">@{{ salesVisitData.allocated }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left text-green">Visited</td>
                                            <td class="text-right text-green">@{{ salesVisitData.visited }}</td>
                                        </tr>
                                        <tr style="border-top: 2px solid #5c6a71;">
                                            <td class="text-left text-danger"><b>Not visited</b></td>
                                            <td class="text-right text-danger"><b>@{{ salesVisitData.notVisited }}</b></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-3">
                                    <table class="table color-table muted-table">
                                        <thead>
                                            <tr>
                                                <th colspan="2">Expenses Summary</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-left">Allowance</td>
                                                <td class="text-right">@{{ salesExpensesData.allowance | number:2 }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-left">General</td>
                                                <td class="text-right">@{{ salesExpensesData.general | number:2 }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-left">Parking</td>
                                                <td class="text-right">@{{ salesExpensesData.parking | number:2 }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-left">Repairs</td>
                                                <td class="text-right">@{{ salesExpensesData.repairs | number:2 }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-left">Mileage</td>
                                                <td class="text-right">@{{ salesExpensesData.mileage | number:2 }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-left">Fuel</td>
                                                <td class="text-right">@{{ salesExpensesData.fuel| number:2 }}</td>
                                            </tr>
                                            <tr style="border-top: 2px solid #5c6a71;">
                                                <td class="text-left"><b>Total</b></td>
                                                <td class="text-right"><b>@{{ salesExpensesData.total | number:2}}</b></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr ng-show="!filterd">

                        <!-- Orders -->
                        <div ng-show="!filterd" class="orders-list m-t-40">
                            <h3><b>ORDERS SUMMARY</b></h3>
                            <hr>
                            <div class="table-responsive">
                                <table class="table color-table muted-table table-scroll">
                                    <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>Order no</th>
                                            <th>Order date & time</th>
                                            <th class="text-right" style="width: 10%;">Sales</th>
                                            <th class="text-right" style="width: 10%;">Cash</th>
                                            <th class="text-right" style="width: 10%;">Cheque</th>
                                            <th class="text-right" style="width: 10%;">Deposit</th>
                                            <th class="text-right" style="width: 10%;">Card</th>
                                            <th class="text-right" style="width: 10%;">Credit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-show="masterData.orders.length" ng-repeat="order in masterData.orders">
                                            <td>
                                                <a target="_blank"
                                                   href="{{ url('/') }}/sales/customer/@{{ order.customer.id }}">
                                                    @{{ order.customer.display_name }}
                                                </a>
                                            </td>
                                            <td>
                                                <a target="_blank" href="{{ url('/') }}/sales/order/@{{ order.id }}">
                                                    @{{ order.ref }}
                                                </a>
                                            </td>
                                            <td>@{{ order.created_on }}</td>
                                            <td class="text-right" style="width: 10%;">@{{ order.total | number:2}}</td>
                                            <td class="text-right" style="width: 10%;">@{{ order.by_cash | number:2}}</td>
                                            <td class="text-right" style="width: 10%;">@{{ order.by_cheque | number:2 }}</td>
                                            <td class="text-right" style="width: 10%;">@{{ order.by_deposit | number:2}}</td>
                                            <td class="text-right" style="width: 10%;">@{{ order.by_card | number:2}}</td>
                                            <td class="text-right" style="width: 10%;">@{{ order.credit | number:2}}</td>
                                        </tr>
                                        <tr ng-show="!masterData.orders.length" content="9">
                                            <td>No Orders Found...</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr style="border-top: 2px solid #5c6a71;" ng-show="masterData.orders.length">
                                            <td style="width: 40%;" class="text-right td-bg-info"><b>TOTAL</b></td>
                                            <td style="width: 10%;" class="text-right td-bg-success"><b>@{{
                                                    orderDetails.totalSales
                                                    |number:2 }}</b>
                                            </td>
                                            <td style="width: 10%;" class="text-right td-bg-success"><b>@{{ cashTotal() |number:2 }}</b>
                                            </td>
                                            <td style="width: 10%;" class="text-right td-bg-success"><b>@{{ chequeTotal() |number:2 }}</b>
                                            </td>
                                            <td style="width: 10%;" class="text-right td-bg-success"><b>@{{ depositTotal() |number:2 }}</b>
                                            </td>
                                            <td style="width: 10%;" class="text-right td-bg-success"><b>@{{ cardTotal() |number:2 }}</b>
                                            </td>
                                            <td style="width: 10%;" class="text-right td-bg-success"><b>@{{ orderDetails.balance |number:2 }}</b>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="clearfix"></div>
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
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    @include('dashboard.sales-stats.script')
@endsection