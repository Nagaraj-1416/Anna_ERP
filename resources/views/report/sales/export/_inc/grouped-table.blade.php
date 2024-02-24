<div>
    <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
        <thead>
        <tr style="background-color: #2f3d4a;">
            <th style="height: 35px;color: #fff;text-align: left;width: 14%;padding-left: 10px;">ORDER#</th>
            <th style="text-align: left;color: #fff;width: 12%;">DATE</th>
            <th style="text-align: left;color: #fff;width: 10%;">STATUS</th>
            {{--<th style="text-align: left;color: #fff;width: 8%;">DELIVERY</th>--}}
            <th style="text-align: right;color: #fff;width: 14%;">AMOUNT</th>
            <th style="text-align: right;color: #fff;width: 14%;">INVOICED</th>
            <th style="text-align: right;color: #fff;width: 14%;">RECEIVED</th>
            <th style="text-align: right;color: #fff;padding-right: 10px;width: 14%;">BALANCE</th>
        </tr>
        </thead>
        <tbody>
        @if($orders)
            @foreach($orders as $key => $values)
                <tr>
                    <td style="padding-left: 10px; padding-top: 10px;" colspan="8">
                        <b>{{ $model->find($key)->$get ?? 'Others' }}</b></td>
                </tr>
                <tr>
                    <td colspan="8">
                        <hr>
                    </td>
                </tr>
                @foreach($values as $orderKey => $order)
                    <tr>
                        <td style="vertical-align: middle;width: 14%;height: 35px;padding-left: 10px;">
                            {{ $order->ref }}
                        </td>
                        <td style="vertical-align: middle;text-align: left;width: 12%;">{{ $order->order_date }}</td>
                        <td style="vertical-align: middle;text-align: left;width: 10%;">{{ $order->status }}</td>
                        {{--<td style="vertical-align: middle;text-align: left;width: 8%;">{{ $order->delivery_status }}</td>--}}
                        <td style="vertical-align: middle;text-align: right;width: 14%;">{{ number_format($order->total) }}</td>
                        <td style="vertical-align: middle;text-align: right;width: 14%;">{{ soOutstanding($order)['invoiced'] }}</td>
                        <td style="vertical-align: middle;text-align: right;width: 14%;">{{ soOutstanding($order)['paid'] }}</td>
                        <td style="vertical-align: middle;text-align: right;width: 14%;padding-right: 10px;">{{ soOutstanding($order)['balance'] }}</td>
                    </tr>
                @endforeach
                <?php
                    $invoices = $values->pluck('invoices')->collapse()->sum('amount');
                    $payments = $values->pluck('payments')->collapse()->sum('payment');
                ?>
                <tr>
                    <td colspan="3"
                        style="text-align: right;border-top: 1px solid #D0D0D0;padding-right: 25px;border-right: 1px solid #D0D0D0;">
                        <strong>Total</strong>
                    </td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 14%;border-right: 1px solid #D0D0D0;">
                        <strong>{{ number_format($values->sum('total')) }}</strong>
                    </td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 14%;border-right: 1px solid #D0D0D0;">
                        <strong>{{ number_format($invoices) }}</strong>
                    </td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 14%;border-right: 1px solid #D0D0D0;">
                        <strong>{{ number_format($payments) }}</strong>
                    </td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 14%;padding-right: 10px;">
                        <strong>{{ number_format($invoices - $payments) }}</strong>
                    </td>
                </tr>
            @endforeach
        @endif
        <tr>
            <td colspan="3"
                style="text-align: right;border-top: 1px solid #D0D0D0;padding-right: 25px;border-right: 1px solid #D0D0D0;">
                <strong>Grand Total</strong>
            </td>
            <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 14%;border-right: 1px solid #D0D0D0;">
                <strong>{{ number_format($order_total) }}</strong>
            </td>
            <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 14%;border-right: 1px solid #D0D0D0;">
                <strong>{{ number_format($invoice_total) }}</strong>
            </td>
            <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 14%;border-right: 1px solid #D0D0D0;">
                <strong>{{ number_format($payment_total) }}</strong>
            </td>
            <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 14%;padding-right: 10px;">
                <strong>{{ number_format($balance) }}</strong>
            </td>
        </tr>
        </tbody>
    </table>
</div>