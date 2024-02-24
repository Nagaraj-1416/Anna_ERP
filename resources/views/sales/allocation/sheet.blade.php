@extends('layouts.master')
@section('title', 'Sales Sheet')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="heading-section m-t-20">
                    <h2 class="text-center"><b>Sales Sheet</b></h2>
                    <p class="text-center text-muted ng-binding" style="margin-bottom: 0.5rem !important;">
                        <a target="_blank" href="{{ route('setting.rep.show', [$allocation->rep]) }}">
                            {{ $allocation->rep->name }}
                        </a>
                        @if($allocation->day_type == 'Multiple')
                            <b> | </b> {{ carbon($allocation->from_date)->format('F j, Y') }} - {{ carbon($allocation->to_date)->format('F j, Y') }}
                        @else
                            <b> | </b> {{ carbon($allocation->from_date)->format('F j, Y') }}
                        @endif
                    </p>
                </div>
                <div class="card-body">
                    <div class="card-body td-bg-default" style="padding-bottom: 0 !important;">
                        <div class="row">
                            {{--<div class="col-md-3">--}}
                                {{--<a href="{{ route('sales.allocation.export.sales.sheet', $allocation) }}" class="btn btn-sm btn-excel">Export to Excel</a>--}}
                            {{--</div>--}}
                            <div class="col-md-3">
                                <p><b>Allocation :</b>
                                    <a target="_blank"
                                       href="{{ route('sales.allocation.show', [$allocation]) }}">
                                        {{ $allocation->code }}
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="row m-t-5">
                            @if($allocation->sales_location == 'Van')
                                <div class="col-md-3">
                                    <p><b>Route :</b>
                                        <a target="_blank"
                                           href="{{ route('setting.route.show', [$allocation->route]) }}">
                                            {{ $allocation->route->name }}
                                        </a>
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <p><b>Driver :</b>
                                        <a target="_blank"
                                           href="{{ route('setting.staff.show', [$allocation->driver]) }}">
                                            {{ $allocation->driver->short_name }}
                                        </a>
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <p><b>Labours :</b>
                                        @foreach(getAllocationLabours($allocation) as $labour)
                                            {{ $labour->short_name }}@if(!$loop->last), @endif
                                        @endforeach
                                    </p>
                                </div>
                            @endif
                        </div>
                        @if($allocation->sales_location == 'Van')
                        <div class="row">
                            <div class="col-md-3">
                                <p><b>Vehicle :</b>
                                    <a target="_blank"
                                       href="{{ route('setting.vehicle.show', [$allocation->vehicle]) }}">
                                        {{ $allocation->vehicle->vehicle_no  ?? 'None'}}
                                    </a>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p><b>ODO starts at :</b>
                                    {{ $allocation->odoMeterReading->starts_at  ?? 'None'}}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p><b>ODO ends at :</b>
                                    {{ $allocation->odoMeterReading->ends_at  ?? 'None'}}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p><b>Total travel :</b>
                                    {{ $allocation->odoMeterReading->ends_at ? ($allocation->odoMeterReading->ends_at - $allocation->odoMeterReading->starts_at).' KM' : 'None' }}
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <p class="text-info"><b>PRODUCTS :
                                    {{ $allocation->items()->count() }}</b>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p><b>Sales starts at :</b>
                                    {{ $allocation->sales_starts_at }}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p><b>Sales ends at :</b>
                                    {{ $allocation->sales_ends_at  }}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p><b>Total sales time :</b>
                                    {{ $allocation->sales_ends_at != 'None' ? $allocation->sales_time : 'None' }}
                                </p>
                            </div>
                        </div>
                        @endif
                        @if($allocation->start_time)
                            <div class="row">
                                <div class="col-md-3">
                                    <p class="text-megna"><b>ORDERS :
                                        {{ $allocation->orders()->count() }}</b>
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <p class="text-warning"><b>ALLOCATED :
                                        {{ $allocation->customers()->count() }}</b>
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <p class="text-green"><b>VISITED :
                                        {{ $allocation->visited_customers }}</b>
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <p class="text-danger"><b>NOT VISITED :
                                        {{ $allocation->not_visited_customers }}</b>
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div style="overflow-x: auto; width: 100%;" class="m-t-10">
                        <table class="table table-bordered" style="width: 100%; max-width: 100%;">
                            <tr>
                                <td rowspan="2" style="vertical-align: bottom;"><h5><b>Customers</b></h5></td>
                                <td rowspan="2" style="vertical-align: bottom;"><h5><b>Order#</b></h5></td>
                                <td rowspan="2" style="vertical-align: bottom;"><h5><b>Order date & time</b></h5></td>
                                <td rowspan="2" style="vertical-align: bottom;"><h5><b>Printed?</b></h5></td>
                                <td rowspan="2" style="vertical-align: bottom;"><h5><b>Cash/Credit</b></h5></td>
                                <td rowspan="2" style="vertical-align: bottom;"><h5><b>Distance</b></h5></td>
                                <td colspan="{{ count($products) }}" class="text-center" style="vertical-align: middle;">
                                    <h5><b>Products</b></h5>
                                </td>
                                <td rowspan="2" style="vertical-align: bottom;" class="text-right"><h5><b>Amount</b></h5></td>

                                <td rowspan="2" style="vertical-align: bottom;" class="text-right"><h5><b>Cash</b></h5></td>
                                <td rowspan="2" style="vertical-align: bottom;" class="text-right"><h5><b>Cheque</b></h5></td>
                                <td rowspan="2" style="vertical-align: bottom;" class="text-right"><h5><b>Deposit</b></h5></td>
                                <td rowspan="2" style="vertical-align: bottom;" class="text-right"><h5><b>Card</b></h5></td>
                                <td rowspan="2" style="vertical-align: bottom;" class="text-right"><h5><b>Return</b></h5></td>

                                <td rowspan="2" style="vertical-align: bottom;" class="text-right"><h5><b>Received</b></h5></td>
                                <td rowspan="2" style="vertical-align: bottom;" class="text-right"><h5><b>Balance</b></h5></td>
                            </tr>
                            <tr>
                                @foreach($products as $product)
                                <td class="rotate" style="height: 300px;">
                                    <div style="font-weight: 600;">
                                        <span>
                                            <a target="_blank" href="{{ route('setting.product.show', $product->product->id) }}">{{ $product->product->name }}</a>
                                        </span>
                                    </div>
                                </td>
                                @endforeach
                            </tr>
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <div style="width: 300px !important;">
                                            <a target="_blank" href="/sales/customer/{{ $order->customer->id }}/ledger">
                                                <i class="fa fa-book"></i>
                                            </a>
                                            |
                                            <a target="_blank" href="/sales/customer/{{ $order->customer->id }}">
                                                {{ $order->customer->display_name }}
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="width: 150px !important;">
                                            <a target="_blank" href="/sales/order/{{ $order->id }}">{{ $order->ref }}</a>
                                        </div>
                                    </td>
                                    <td><div style="width: 180px !important;">{{ $order->createdAt }}</div></td>
                                    <td><div style="width: 70px !important;" class="{{ $order->is_order_printed == 'Yes' ? 'text-green': 'test-danger'}}">{{ $order->is_order_printed }}</div></td>
                                    <td><div style="width: 90px !important;" class="{{ $order->is_credit_sales == 'Yes' ? 'text-danger' : 'text-green'}}">{{ $order->is_credit_sales == 'Yes' ? 'Credit' : 'Cash' }}</div></td>
                                    <td>
                                        <div style="width: 80px !important;">
                                            <a target="_blank" href="{{ $order->distance_show_route }}">{{ $order->distance }}</a>
                                        </div>
                                    </td>
                                    @foreach($products as $product)
                                        <td class="text-center" width="60%" class="{{ getProductSoldQty($order, $product) != null ? 'td-bg-default' : '' }}">
                                            {{ getProductSoldQty($order, $product) }}
                                        </td>
                                    @endforeach
                                    <td class="text-right td-bg-info">{{ number_format($order->total, 2) }}</td>

                                    <td class="text-right td-bg-default">{{ number_format(soOutstandingByAllocation($order, $allocation)['byCash'], 2) }}</td>
                                    <td class="text-right td-bg-default">{{ number_format(soOutstandingByAllocation($order, $allocation)['byCheque'], 2) }}</td>
                                    <td class="text-right td-bg-default">{{ number_format(soOutstandingByAllocation($order, $allocation)['byDeposit'], 2) }}</td>
                                    <td class="text-right td-bg-default">{{ number_format(soOutstandingByAllocation($order, $allocation)['byCard'], 2) }}</td>
                                    <td class="text-right td-bg-default">{{ number_format(soOutstandingByAllocation($order, $allocation)['byReturn'], 2) }}</td>

                                    <td class="text-right td-bg-success">{{ number_format(soOutstandingByAllocation($order, $allocation)['paid'], 2) }}</td>
                                    <td class="text-right td-bg-danger">{{ number_format(soOutstandingByAllocation($order, $allocation)['balance'], 2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="6"></td>
                                @foreach($products as $product)
                                <td class="text-right td-bg-warning"></td>
                                @endforeach
                                <td class="text-right td-bg-info" style="border-top: 3px solid #a0a0a0 !important; border-bottom: 3px solid #a0a0a0 !important;"><b>{{ number_format($total, 2) }}</b></td>

                                <td class="text-right td-bg-default" style="border-top: 3px solid #a0a0a0 !important; border-bottom: 3px solid #a0a0a0 !important;"><b>{{ number_format($cashReceived, 2) }}</b></td>
                                <td class="text-right td-bg-default" style="border-top: 3px solid #a0a0a0 !important; border-bottom: 3px solid #a0a0a0 !important;"><b>{{ number_format($chequeReceived, 2) }}</b></td>
                                <td class="text-right td-bg-default" style="border-top: 3px solid #a0a0a0 !important; border-bottom: 3px solid #a0a0a0 !important;"><b>{{ number_format($depositReceived, 2) }}</b></td>
                                <td class="text-right td-bg-default" style="border-top: 3px solid #a0a0a0 !important; border-bottom: 3px solid #a0a0a0 !important;"><b>{{ number_format($cardReceived, 2) }}</b></td>
                                <td class="text-right td-bg-default" style="border-top: 3px solid #a0a0a0 !important; border-bottom: 3px solid #a0a0a0 !important;"><b>{{ number_format($customerCredit, 2) }}</b></td>

                                <td class="text-right td-bg-success" style="border-top: 3px solid #a0a0a0 !important; border-bottom: 3px solid #a0a0a0 !important;"><b>{{ number_format($received, 2) }}</b></td>
                                <td class="text-right td-bg-danger" style="border-top: 3px solid #a0a0a0 !important; border-bottom: 3px solid #a0a0a0 !important;"><b>{{ number_format($balance, 2) }}</b></td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-right"><b>CF</b></td>
                                @foreach($products as $product)
                                    <td class="text-center td-bg-default">
                                        {{ getProductQtyStats($allocation, $product)['cf'] }}
                                    </td>
                                @endforeach
                                <td colspan="8"></td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-right"><b>Issued</b></td>
                                @foreach($products as $product)
                                    <td class="text-center td-bg-default">
                                        {{ getProductQtyStats($allocation, $product)['issued'] }}
                                    </td>
                                @endforeach
                                <td colspan="8"></td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-right td-bg-default"><b>TOTAL ALLOCATED</b></td>
                                @foreach($products as $product)
                                    <td class="text-center td-bg-info" style="border-top: 3px solid #a0a0a0 !important;">
                                        {{ getProductQtyStats($allocation, $product)['allocated'] }}
                                    </td>
                                @endforeach
                                <td colspan="8"></td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-right"><b>Sold</b></td>
                                @foreach($products as $product)
                                    <td class="text-center td-bg-warning" style="border-top: 3px solid #a0a0a0 !important;">
                                        {{ getProductQtyStats($allocation, $product)['sold'] }}
                                    </td>
                                @endforeach
                                <td colspan="8"></td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-right"><b>Returned</b></td>
                                @foreach($products as $product)
                                    <td class="text-center td-bg-info">
                                        {{ getProductQtyStats($allocation, $product)['returned'] }}
                                    </td>
                                @endforeach
                                <td colspan="8"></td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-right"><b>Replaced</b></td>
                                @foreach($products as $product)
                                    <td class="text-center td-bg-info">
                                        {{ getProductQtyStats($allocation, $product)['replaced'] }}
                                    </td>
                                @endforeach
                                <td colspan="8"></td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-right"><b>Shortage</b></td>
                                @foreach($products as $product)
                                    <td class="text-center td-bg-danger">
                                        {{ getProductQtyStats($allocation, $product)['shortage'] }}
                                    </td>
                                @endforeach
                                <td colspan="8"></td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-right"><b>Damaged</b></td>
                                @foreach($products as $product)
                                    <td class="text-center td-bg-danger">
                                        {{ getProductQtyStats($allocation, $product)['damaged'] }}
                                    </td>
                                @endforeach
                                <td colspan="8"></td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-right"><b>Excess</b></td>
                                @foreach($products as $product)
                                    <td class="text-center td-bg-info">
                                        {{ getProductQtyStats($allocation, $product)['excess'] }}
                                    </td>
                                @endforeach
                                <td colspan="8"></td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-right"><b>Restored</b></td>
                                @foreach($products as $product)
                                    <td class="text-center td-bg-warning">
                                        {{ getProductQtyStats($allocation, $product)['restored'] }}
                                    </td>
                                @endforeach
                                <td colspan="8"></td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-right td-bg-default"><b>AVAILABLE</b></td>
                                @foreach($products as $product)
                                    <td class="text-center td-bg-success" style="border-top: 3px solid #a0a0a0 !important;">
                                        {{ getProductQtyStats($allocation, $product)['available'] }}
                                    </td>
                                @endforeach
                                <td colspan="8"></td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-right"><b>Stocks Confirmed By Rep</b></td>
                                @foreach($products as $product)
                                    <td class="text-center td-bg-default" style="border-top: 3px solid #a0a0a0 !important; border-bottom: 3px solid #a0a0a0 !important;">
                                        {{ getProductQtyStats($allocation, $product)['actual'] }}
                                    </td>
                                @endforeach
                                <td colspan="8"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    <h3 class="text-primary">Collection from Old Orders</h3>
                    <table class="table table-bordered m-t-10">
                        <tr>
                            <td rowspan="2" style="vertical-align: bottom"><b>Customer</b></td>
                            <td rowspan="2" style="vertical-align: bottom"><b>Order#</b></td>
                            <td rowspan="2" style="vertical-align: bottom"><b>Order date & time</b></td>
                            <td rowspan="2" style="vertical-align: bottom"><b>Collection date & time</b></td>
                            <td rowspan="2" style="vertical-align: bottom"><b>Distance</b></td>
                            <td rowspan="2" style="vertical-align: bottom; text-align: right;"><b>Amount</b></td>
                            <td rowspan="2" style="vertical-align: bottom; text-align: right;"><b>Collected</b></td>
                            <td rowspan="2" style="vertical-align: bottom; text-align: right;"><b>Collected today</b></td>
                            <td colspan="5" style="vertical-align: bottom; text-align: center;"><b>Collected today by</b></td>
                            <td rowspan="2" style="vertical-align: bottom; text-align: right;"><b>Balance</b></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;"><b>Cash</b></td>
                            <td style="text-align: right;"><b>Cheque</b></td>
                            <td style="text-align: right;"><b>Deposit</b></td>
                            <td style="text-align: right;"><b>Card</b></td>
                            <td style="text-align: right;"><b>Return</b></td>
                        </tr>
                        @if($oldSales)
                            @foreach($oldSales as $oldSale)
                                <tr>
                                    <td>
                                        <a target="_blank" href="/sales/customer/{{ $oldSale->customer->id }}/ledger">
                                            <i class="fa fa-book"></i>
                                        </a>
                                        |
                                        <a target="_blank" href="/sales/customer/{{ $oldSale->customer->id }}">
                                            {{ $oldSale->customer->display_name }}
                                        </a>
                                    </td>
                                    <td>
                                        <a target="_blank" href="/sales/order/{{ $oldSale->order->id }}">
                                            {{ $oldSale->order->ref }}
                                        </a>
                                    </td>
                                    <td>{{ $oldSale->orderCreatedAt }}</td>
                                    <td>{{ $oldSale->paymentCreatedAt }}</td>
                                    <td>
                                        <a target="_blank" href="{{ $oldSale->distance_show_route }}">{{ $oldSale->distance }}KM</a>
                                    </td>
                                    <td style="text-align: right;" class="td-bg-info">{{ number_format($oldSale->order->total, 2) }}</td>
                                    <td style="text-align: right;" class="td-bg-warning">{{ number_format((soOutstandingByAllocation($oldSale->order, $allocation)['paid'] - $oldSale->payment), 2) }} </td>
                                    <td style="text-align: right;" class="td-bg-success">{{ number_format($oldSale->payment, 2) }} </td>
                                    <td style="text-align: right;" class="td-bg-default">
                                        @if($oldSale->payment_mode == 'Cash')
                                            {{ number_format($oldSale->payment, 2) }}
                                        @else
                                            {{ number_format(0, 2) }}
                                        @endif
                                    </td>
                                    <td style="text-align: right;" class="td-bg-default">
                                        @if($oldSale->payment_mode == 'Cheque')
                                            {{ number_format($oldSale->payment, 2) }}
                                        @else
                                            {{ number_format(0, 2) }}
                                        @endif
                                    </td>
                                    <td style="text-align: right;" class="td-bg-default">
                                        @if($oldSale->payment_mode == 'Direct Deposit')
                                            {{ number_format($oldSale->payment, 2) }}
                                        @else
                                            {{ number_format(0, 2) }}
                                        @endif
                                    </td>
                                    <td style="text-align: right;" class="td-bg-default">
                                        @if($oldSale->payment_mode == 'Credit Card')
                                            {{ number_format($oldSale->payment, 2) }}
                                        @else
                                            {{ number_format(0, 2) }}
                                        @endif
                                    </td>
                                    <td style="text-align: right;" class="td-bg-default">
                                        @if($oldSale->payment_mode == 'Customer Credit')
                                            {{ number_format($oldSale->payment, 2) }}
                                        @else
                                            {{ number_format(0, 2) }}
                                        @endif
                                    </td>
                                    <td style="text-align: right;" class="td-bg-danger">{{ number_format(soOutstandingByAllocation($oldSale->order, $allocation)['balance'], 2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="7" class="text-right">
                                    <b>TOTAL RECEIVED</b>
                                </td>
                                <td class="text-right td-bg-success" style="border-top: 3px solid #a0a0a0 !important;">
                                    <b>{{ number_format($oldReceived, 2) }}</b>
                                </td>
                                <td class="text-right td-bg-default" style="border-top: 3px solid #a0a0a0 !important;">
                                    <b>{{ number_format($oldCashReceived, 2) }}</b>
                                </td>
                                <td class="text-right td-bg-default" style="border-top: 3px solid #a0a0a0 !important;">
                                    <b>{{ number_format($oldChequeReceived, 2) }}</b>
                                </td>
                                <td class="text-right td-bg-default" style="border-top: 3px solid #a0a0a0 !important;">
                                    <b>{{ number_format($oldDepositReceived, 2) }}</b>
                                </td>
                                <td class="text-right td-bg-default" style="border-top: 3px solid #a0a0a0 !important;">
                                    <b>{{ number_format($oldCardReceived, 2) }}</b>
                                </td>
                                <td class="text-right td-bg-default" style="border-top: 3px solid #a0a0a0 !important;">
                                    <b>{{ number_format($oldCustomerCredit, 2) }}</b>
                                </td>
                            </tr>
                        @else
                            <tr><td>No collection for today</td></tr>
                        @endif
                    </table>
                </div>
                <hr>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="text-info">Sales Visits Stats</h3>
                            <table class="table table-bordered m-t-10">
                                @foreach($reasons as $reasonKey => $reason)
                                    <tr>
                                        <td>
                                            <h4 class="m-t-10">{{ $reason.': ' }} <code style="font-size: 20px;"><b>{{ getVisitCounts($allocation->id, $reason) }}</b></code></h4>
                                            <div class="clearfix m-t-10">
                                                <div class="pull-left"><b>Customers details</b></div>
                                                <div class="pull-right"><a onclick="printDiv('customer-details-{{ $reasonKey }}')" href="" class="btn btn-inverse btn-sm">Print</a></div>
                                            </div>
                                            <div id="customer-details-{{ $reasonKey }}">
                                                <table class="table table-bordered m-t-5">
                                                    <thead>
                                                        <tr>
                                                            <th>Display name</th>
                                                            <th style="width: 15%">Phone</th>
                                                            <th style="width: 15%">Mobile</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if(getVisitDetails($allocation->id, $reason))
                                                        @foreach(getVisitDetails($allocation->id, $reason) as $customerData)
                                                            <tr>
                                                                <td>
                                                                    <a target="_blank" href="{{ route('sales.customer.show', $customerData->customer) }}">
                                                                        {{ $customerData->customer->display_name }}
                                                                    </a>
                                                                </td>
                                                                <td style="width: 15%">{{ $customerData->customer->phone }}</td>
                                                                <td style="width: 15%">{{ $customerData->customer->mobile }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h3 class="text-danger">Returns</h3>
                            <table class="table table-bordered m-t-10">
                                <thead>
                                <tr>
                                    <th style="width: 20%;">Code</th>
                                    <th>Customer</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($returns))
                                    @foreach($returns as $return)
                                        <tr>
                                            <td>
                                                <a target="_blank" href="{{ route('sales.return.show', $return) }}">{{ $return->code }}</a>
                                            </td>
                                            <td>{{ $return->customer->display_name }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <table class="table color-table inverse-table">
                                                    <thead>
                                                    <tr>
                                                        <th style="width: 50%;">Items & Description</th>
                                                        <th class="text-center">Qty</th>
                                                        <th class="text-right">Sold</th>
                                                        <th class="text-right">Returned</th>
                                                        <th class="text-right">Amount</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if(count($return->items))
                                                        @foreach($return->items as $itemKey => $item)
                                                            <tr>
                                                                <td>
                                                                    {{ $item->product->name }}<br>
                                                                    <small class="text-muted">
                                                                        <b>Reason:</b> {{ $item->reason }}
                                                                    </small>
                                                                    <br />
                                                                    <small><u><b>Resolutions</b></u></small><br />
                                                                    @foreach($return->resolutions as $keyRes => $resolution)
                                                                        <small class="text-muted">
                                                                            <b>Type: </b> {{ $resolution->resolution }}<br />
                                                                            <b>Amount: </b> {{ number_format($resolution->amount, 2) }}
                                                                        </small>
                                                                    @endforeach
                                                                </td>
                                                                <td class="text-center">{{ $item->qty }}</td>
                                                                <td class="text-right">{{ number_format($item->sold_rate, 2) }}</td>
                                                                <td class="text-right">{{ number_format($item->returned_rate, 2) }}</td>
                                                                <td class="text-right">{{ number_format($item->returned_amount, 2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @else

                                                    @endif
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4">No returns for today...!</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="clearfix">
                                <div class="pull-left">
                                    <h3 class="text-warning">Credit Orders from this Allocation</h3>
                                </div>
                                <div class="pull-right">
                                    <a href="{{ route('sales.allocation.export.credit.orders', $allocation) }}"
                                       class="btn waves-effect waves-light btn-inverse btn-sm">
                                        <i class="fa fa-file-pdf-o"></i> Export to PDF
                                    </a>
                                </div>
                            </div>
                            <table class="table table-bordered m-t-10">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;">Customer</th>
                                        <th>Order#</th>
                                        <th>Order Date</th>
                                        <th class="text-right">Amount</th>
                                        <th class="text-right">Paid</th>
                                        <th class="text-right">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($creditOrders))
                                        @foreach($creditOrders as $creditOrder)
                                            <tr>
                                                <td>
                                                    <a href="">
                                                        {{ $creditOrder->customer->display_name }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="">
                                                        {{ $creditOrder->ref }}
                                                    </a>
                                                </td>
                                                <td>{{ $creditOrder->order_date }}</td>
                                                <td class="text-right">{{ number_format($creditOrder->total, 2) }}</td>
                                                <td class="text-right">{{ number_format($creditOrder->paid, 2) }}</td>
                                                <td class="text-right">{{ number_format($creditOrder->balance, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="3" class="text-right">
                                                <b>TOTAL</b>
                                            </td>
                                            <td class="text-right td-bg-default" style="border-top: 3px solid #a0a0a0 !important;">
                                                <b>{{ number_format($creditOrders->sum('total'), 2) }}</b>
                                            </td>
                                            <td class="text-right td-bg-success" style="border-top: 3px solid #a0a0a0 !important;">
                                                <b>{{ number_format($creditOrders->sum('paid'), 2) }}</b>
                                            </td>
                                            <td class="text-right td-bg-warning" style="border-top: 3px solid #a0a0a0 !important;">
                                                <b>{{ number_format($creditOrders->sum('balance'), 2) }}</b>
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="4">No credit orders for this allocation so far...!</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="clearfix">
                                <div class="pull-left">
                                    <h3 class="text-warning">Expenses from this Allocation</h3>
                                </div>
                            </div>
                            <table class="table table-bordered m-t-10">
                                <thead>
                                <tr>
                                    <th style="width: 20%;">Expense Type</th>
                                    <th style="width: 20%;">Date</th>
                                    <th>Expense Details</th>
                                    <th class="text-right" style="width: 10%;">Amount</th>
                                    <th style="width: 10%;">Map</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($expenses))
                                    @foreach($expenses as $expense)
                                        <tr>
                                            <td>
                                                {{ $expense->type->name }}
                                            </td>
                                            <td>
                                                {{ $expense->expense_date }}
                                                {{ $expense->expense_time ? 'at '.$expense->expense_time : '' }}
                                            </td>
                                            <td>
                                                @if($expense->type_id == 2)
                                                    <br />
                                                    <b>Ltr:</b> {{ $expense->liter }}<br />
                                                    <b>ODO Reading: </b>{{ $expense->odometer }}<br />
                                                    {{ $expense->notes }}
                                                @else
                                                    {{ $expense->notes }}
                                                @endif
                                            </td>
                                            <td class="text-right">{{ number_format($expense->amount, 2) }}</td>
                                            <td>
                                                @if($expense->gps_lat && $expense->gps_long)
                                                    <a target="_blank" href="{{ route('map.index', [
                                                            'startLat' => $expense->gps_lat,
                                                            'startLng' => $expense->gps_long,
                                                            'startInfo' => json_encode(['heading' => $expense->code, 'code' => $expense->expense_date]),
                                                            ]) }}">View in Map</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4">No expenses recorded for this allocation so far...!</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('general.distance-calculator.index')
    <script>

        var distanceRoute = '{{ route('sales.distance.order.update', ['ID']) }}';

        function getOrdersDistance(order) {
            if (!order.distance && order.gps_lat && order.gps_long && order.customer && order.customer.gps_lat && order.customer.gps_long) {
                var distance = getDistance(order.gps_lat, order.gps_long, order.customer.gps_lat, order.customer.gps_long, distanceRoute.replace('ID', order.id));
                var name = '#distance-' + order.id;
                $(name).text(distance.toFixed(2) + 'KM')
            }
        }

        var orders = @json(allocationOrders($allocation));
        $(document).ready(function () {
            $.each(orders, function (k, order) {
                getOrdersDistance(order);
            })
        });

        /** get collection distance */
        var payments = @json($oldSales);
        $(document).ready(function () {
            $.each(payments, function (k, payment) {
                getPaymentDistance(payment);
            })
        });

        function getPaymentDistance(payment) {
            if (payment.gps_lat && payment.gps_long && payment.customer && payment.customer.gps_lat && payment.customer.gps_long) {
                var distance = getDistanceNoRoute(payment.gps_lat, payment.gps_long, payment.customer.gps_lat, payment.customer.gps_long);

            }
        }

        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
@endsection
