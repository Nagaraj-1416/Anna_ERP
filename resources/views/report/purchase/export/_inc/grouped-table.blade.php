<div>
    <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
        <thead>
        <tr style="background-color: #2f3d4a;">
            <th style="height: 35px;color: #fff;text-align: left;padding-left: 10px;">ORDER#</th>
            <th style="text-align: left;color: #fff;width: 12%;">DATE</th>
            <th style="text-align: left;color: #fff;width: 10%;">STATUS</th>
            <th style="text-align: left;color: #fff;width: 10%;">DELIVERY</th>
            <th style="text-align: right;color: #fff;width: 14%;">AMOUNT</th>
            <th style="text-align: right;color: #fff;width: 14%;">BILLED</th>
            <th style="text-align: right;color: #fff;width: 14%;">MADE</th>
            <th style="text-align: right;color: #fff;padding-right: 10px;width: 14%;">BALANCE</th>
        </tr>
        </thead>
        <tbody>
        @if($orders)
            @foreach($orders as $key => $values)
                <tr>
                    <td style="padding-left: 10px; padding-top: 10px;" colspan="8">
                        <b>{{ $model->find($key)->$get ?? 'None' }}</b></td>
                </tr>
                <tr>
                    <td colspan="8">
                        <hr>
                    </td>
                </tr>
                @foreach($values as $orderKey => $order)
                    <tr>
                        <td style="vertical-align: middle;height: 35px;padding-left: 10px;">
                            {{ $order->po_no }}
                        </td>
                        <td style="vertical-align: middle;text-align: left;width: 12%;">{{ $order->order_date }}</td>
                        <td style="vertical-align: middle;text-align: left;width: 10%;">{{ $order->status }}</td>
                        <td style="vertical-align: middle;text-align: left;width: 10%;">{{ $order->delivery_status }}</td>
                        <td style="vertical-align: middle;text-align: right;width: 14%;">{{ number_format($order->total) }}</td>
                        <td style="vertical-align: middle;text-align: right;width: 14%;">{{ number_format(poOutstanding($order)['billed']) }}</td>
                        <td style="vertical-align: middle;text-align: right;width: 14%;">{{ number_format(poOutstanding($order)['paid']) }}</td>
                        <td style="vertical-align: middle;text-align: right;width: 14%;padding-right: 10px;">{{ number_format(poOutstanding($order)['balance']) }}</td>
                    </tr>
                @endforeach
            @endforeach
        @endif
        <tr>
            <td colspan="4"
                style="text-align: right;border-top: 1px solid #D0D0D0;padding-right: 25px;border-right: 1px solid #D0D0D0;">
                <strong>Total</strong>
            </td>
            <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 14%;border-right: 1px solid #D0D0D0;">
                <strong>{{ number_format($order_total) }}</strong>
            </td>
            <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 14%;border-right: 1px solid #D0D0D0;">
                <strong>{{ number_format($bill_total) }}</strong>
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