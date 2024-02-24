<!-- Today Sales Summary -->
<div class="card bg-light-success border-default">
    <div class="card-body">
        <div class="d-flex no-block">
            <h3 class="card-title text-megna">Today's Sales Summary</h3>
            <div class="ml-auto"></div>
        </div>
        <h6 class="card-subtitle">Sales collection stats for <span class="text-purple">{{ carbon()->now()->format('F j, Y') }}</span></h6>
        <div class="ribbon-wrapper card m-t-15">
            <div class="ribbon ribbon-success">Collection from Today's Orders</div>
            <div class="table-responsive">
                <table class="ui celled structured table">
                    <thead>
                    <tr>
                        <th>Order #</th>
                        {{--<th>Order Date</th>--}}
                        {{--<th>Status</th>--}}
                        {{--<th>Delivery</th>--}}
                        <th class="text-right" style="width: 15%;">Amount</th>
                        <th class="text-right" style="width: 15%;">Received</th>
                        <th class="text-right" style="width: 15%;">Change Given</th>
                        {{--<th class="text-right">Balance</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($todaySales as $sales)
                            <tr>
                                <td><a target="_blank" href="{{ route('sales.order.show', $sales->id) }}">{{ $sales->ref }}</a> </td>
                                {{--<td>{{ $sales->order_date }}</td>--}}
                                {{--<td>{{ $sales->status }}</td>--}}
                                {{--<td>{{ $sales->delivery_status }}</td>--}}
                                <td class="text-right">{{ number_format($sales->total, 2) }}</td>
                                <td class="text-right text-blue">{{ number_format($sales->received_cash, 2) }}</td>
                                <td class="text-right text-warning">{{ number_format($sales->change_given, 2) }}</td>
                                {{--<td class="text-right text-green">{{ number_format($sales->invoices->sum('amount') - $sales->payments->sum('payment'), 2) }}</td>--}}
                            </tr>
                        @endforeach
                        @php
                            $totalInvoiced = $todaySales->pluck('invoices')->collapse()->sum('amount');
                            $totalPayment = $todaySales->pluck('payments')->collapse()->sum('payment');
                        @endphp
                        <tr>
                            <td class="text-right td-bg-info"><b>TOTAL</b></td>
                            <td class="text-right td-bg-success"><b>{{ number_format($todaySales->sum('total'), 2) }}</b></td>
                            <td></td>
                            <td></td>
                            {{--<td class="text-right td-bg-success"><b>{{ number_format($totalInvoiced, 2) }}</b></td>--}}
                            {{--<td class="text-right td-bg-success"><b>{{ number_format($totalPayment, 2) }}</b></td>--}}
                            {{--<td class="text-right td-bg-success"><b>{{ number_format($totalInvoiced - $totalPayment, 2) }}</b></td>--}}
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        {{--<div class="ribbon-wrapper card">
            <div class="ribbon ribbon-primary">Collection from Old Orders</div>
            <div class="table-responsive">
                <table class="ui celled structured table" >
                    <thead>
                    <tr class="parent-row">
                        <th>Order</th>
                        <th>Invoice</th>
                        <th>Payment</th>
                        <th>Payment Method</th>
                        <th class="text-right">Total Received</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($oldSales as $payment)
                            <tr class="parent-row">
                                <td><a target="_blank" href="{{ route('sales.order.show', $payment->sales_order_id) }}"><b>{{ $payment->order ? $payment->order->order_no : 'None' }}</b></a></td>
                                <td><a target="_blank" href="{{ route('sales.invoice.show', $payment->invoice_id) }}"><b>{{ $payment->invoice ? $payment->invoice->invoice_no: 'None' }}</b></a></td>
                                <td>{{ $payment->payment_date }}</td>
                                <td>{{ $payment->payment_mode }}</td>
                                <td class="text-right text-green">{{ number_format($payment->payment, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-right td-bg-info"><b>TOTAL</b></td>
                            <td class="text-right td-bg-success"> </td>
                            <td class="text-right td-bg-success"></td>
                            <td class="text-right td-bg-success"></td>
                            <td class="text-right td-bg-success"><b>{{ number_format($oldSales->sum('payment'), 2) }}</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>--}}

        {{--<div class="ribbon-wrapper card">
            <div class="ribbon ribbon-warning">Total Collection from Today's and old Orders</div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12"></div>
                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                    <table class="ui celled structured table">
                        <tfoot>
                        <tr>
                            <th class="right aligned"><b>Collection from today's Orders</b></th>
                            <td class="right aligned td-bg-success"><b>{{ number_format($totalPayment, 2) }}</b></td>
                        </tr>
                        <tr>
                            <th class="right aligned"><b>Collection from old Orders</b></th>
                            <td class="right aligned td-bg-success"><b>{{ number_format($oldSales->sum('payment'), 2) }}</b></td>
                        </tr>
                        <tr>
                            <th class="right aligned"><b>Total Collection</b></th>
                            <td class="right aligned td-bg-info"><b>{{ number_format($totalPayment + $oldSales->sum('payment'), 2) }}</b></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>--}}

    </div>
</div>