<div class="card">
    <div class="card-body">
        <h3><b>SALES ORDERS</b> <span class="pull-right">Total Sales Orders: {{ count($orders) }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Order no</th>
                    <th>Order date</th>
                    <th>Delivery date</th>
                    <th>Status</th>
                    <th class="text-right">Amount</th>
                    <th class="text-right">Invoiced</th>
                    <th class="text-right">Paid</th>
                    <th class="text-right">Balance</th>
                </tr>
                </thead>
                <tbody>
                    @if(count($orders))
                        @foreach($orders as $orderKey => $order)
                        <tr>
                            <td>
                                <a target="_blank" href="{{ route('sales.order.show', [$order]) }}">{{ $order->order_no }}</a>
                            </td>
                            <td>{{ $order->order_date }}</td>
                            <td>{{ $order->delivery_date }}</td>
                            <td>
                                <span class="{{ statusLabelColor($order->status) }}">{{ $order->status }}</span>
                            </td>
                            <td class="text-right">{{ number_format($order->total, 2) }}</td>
                            <td class="text-right text-success">{{ number_format(soOutstanding($order)['invoiced'], 2) }}</td>
                            <td class="text-right text-green">{{ number_format(soOutstanding($order)['paid'], 2) }}</td>
                            <td class="text-right text-warning">{{ number_format(soOutstanding($order)['balance'], 2) }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>No Orders Found...</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>