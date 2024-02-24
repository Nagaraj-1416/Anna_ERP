<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="salesStats">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">SALES STATS</span>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 700;color: #455a64;font-family: sans-serif;font-size: 13px;">From </span>
                    <span style="font-weight: 500;color: #455a64;font-family: sans-serif;font-size: 13px;">
                        {{  carbon($request['fromDate'])->format('M d, Y') }}
                    </span>
                    <span style="font-weight: 700;color: #455a64;font-family: sans-serif;font-size: 13px;">To </span>
                    <span style="font-weight: 500;color: #455a64;font-family: sans-serif;font-size: 13px;">
                        {{  carbon($request['toDate'])->format('M d, Y') }}
                    </span>
                </td>
            </tr>
        </table>
    </div>
    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>
    <table style="font-family: sans-serif;border-collapse: collapse; width: 60%;font-size: 12px; margin-top: 10px;">
        <tr>
            <td style="width: 100%;font-family: sans-serif;vertical-align: top;" >
                <b>Company:</b> {{ array_get($request, 'company')->name ?? 'None'  }} <br />
                <b>Sales rep:</b> {{ array_get($request, 'rep')->name ?? 'None'  }}
            </td>
        </tr>
    </table>
    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>
    <div style="padding-top: 10px;">
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 25%;font-family: sans-serif;vertical-align: top;" >
                    <div style="font-family: sans-serif; padding-top: 15px;">
                        <h6 style="font-weight: 600;font-family: sans-serif;font-size: 13px;"><b>Orders Summary</b></h6>
                        <table style="font-family: sans-serif;border-collapse: collapse; width: 80%;font-size: 12px;">
                            <tr>
                                <td style="text-align: left;">Orders:</td>
                                <td style="width: 60%; text-align: right;">{{ array_get($orderData, 'totalSalesOrders') }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Sales:</td>
                                <td style="text-align: right;">{{ number_format(array_get($orderData, 'totalSales')) }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Invoiced:</td>
                                <td style="text-align: right;">{{ number_format(array_get($orderData, 'totalInvoiced')) }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Received:</td>
                                <td style="text-align: right;">({{ number_format(array_get($orderData, 'totalPaid')) }})</td>
                            </tr>
                            <tr>
                                <td style="text-align: left; border-top: 1px solid #5c6a71;"><strong>Balance:</strong></td>
                                <td style="text-align: right; border-top: 1px solid #5c6a71;">{{ number_format(array_get($orderData, 'balance')) }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td style="width: 25%;font-family: sans-serif;vertical-align: top;">
                    <div style="font-family: sans-serif; padding-top: 15px;">
                        <h6 style="font-weight: 600;font-family: sans-serif;font-size: 13px;"><b>Payments Summary</b>
                        </h6>
                        <table style="font-family: sans-serif;border-collapse: collapse; width: 80%;font-size: 12px;">
                            <tr>
                                <td style="text-align: left;">Cash:</td>
                                <td style="width: 60%; text-align: right;">{{ number_format(array_get($paymentsData, 'cash')) }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Cheque:</td>
                                <td style="text-align: right;">{{ number_format(array_get($paymentsData, 'cheque')) }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Deposit:</td>
                                <td style="text-align: right;">{{ number_format(array_get($paymentsData, 'deposit')) }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Card:</td>
                                <td style="text-align: right;">{{ number_format(array_get($paymentsData, 'card')) }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left; border-top: 1px solid #5c6a71;"><strong>Received:</strong></td>
                                <td style="text-align: right; border-top: 1px solid #5c6a71;">{{ number_format(array_get($paymentsData, 'total')) }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td style="width: 25%;font-family: sans-serif; vertical-align: top;">
                    <div style="font-family: sans-serif; padding-top: 15px;">
                        <h6 style="font-weight: 600;font-family: sans-serif;font-size: 13px;"><b>Sales Visits Summary</b></h6>
                        <table style="font-family: sans-serif;border-collapse: collapse; width: 80%;font-size: 12px;">
                            <tr>
                                <td style="text-align: left;">Allocated:</td>
                                <td style="width: 30%; text-align: right;">{{ array_get($salesVisitData, 'allocated') ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Visited:</td>
                                <td style="text-align: right;">{{ array_get($salesVisitData, 'visited') ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left; border-top: 1px solid #5c6a71;"><strong>Not Visited:</strong></td>
                                <td style="text-align: right; border-top: 1px solid #5c6a71;">{{ array_get($salesVisitData, 'notVisited') ?? 0  }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td style="width: 25%;font-family: sans-serif;vertical-align: top;" >
                    <div style="font-family: sans-serif; padding-top: 15px;">
                        <h6 style="font-weight: 600;font-family: sans-serif;font-size: 13px;"><b>Expenses Summary</b></h6>
                        <table style="font-family: sans-serif;border-collapse: collapse; width: 80%;font-size: 12px;">
                            <tr>
                                <td style="text-align: left;">Allowance:</td>
                                <td style="width: 30%; text-align: right;">{{  number_format(array_get($salesExpensesData, 'allowance')) }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">General:</td>
                                <td style="text-align: right;">{{  number_format(array_get($salesExpensesData, 'general')) }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Parking:</td>
                                <td style="text-align: right;">{{ number_format(array_get($salesExpensesData, 'parking'))}}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Repairs:</td>
                                <td style="text-align: right;">{{ number_format(array_get($salesExpensesData, 'repairs'))}}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Mileage:</td>
                                <td style="text-align: right;">{{ number_format(array_get($salesExpensesData, 'mileage'))}}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Fuel:</td>
                                <td style="text-align: right;">{{ number_format(array_get($salesExpensesData, 'fuel'))}}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left; border-top: 1px solid #5c6a71;"><strong>Total:</strong></td>
                                <td style="text-align: right; border-top: 1px solid #5c6a71;">{{ number_format(array_get($salesExpensesData, 'total'))  }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>
    @if(isset($masterData['orders']))
        <div>
            <h4 style="font-weight: 600;font-family: sans-serif;font-size: 15px;"><b>ORDERS</b></h4>
            <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
                <thead>
                <tr style="background-color: #2f3d4a;">
                    <th style="text-align: left;color: #fff;">Order date</th>
                    <th style="color: #fff;text-align: left;">Order no</th>
                    <th style="text-align: right;color: #fff;">Sales</th>
                    <th style="text-align: right;color: #fff;">Cash</th>
                    <th style="text-align: right;color: #fff;">Cheque</th>
                    <th style="text-align: right;color: #fff;padding-right: 10px;">Deposit</th>
                    <th style="text-align: right;color: #fff;padding-right: 10px;">Card</th>
                    <th style="text-align: right;color: #fff;padding-right: 10px;">Credit</th>
                </tr>
                </thead>
                <tbody>
                @if(array_get($masterData, 'orders'))
                    @foreach(array_get($masterData, 'orders') as $orderKey => $order)
                        <tr>
                            <td style="vertical-align: middle;text-align: left;">{{ $order->order_date }}</td>
                            <td style="vertical-align: middle;">
                                {{ $order->ref }}
                            </td>
                            <td style="vertical-align: middle;text-align: right;">{{ number_format($order->total) }}</td>
                            <td style="vertical-align: middle;text-align: right;">{{ number_format(soOutstanding($order)['byCash']) }}</td>
                            <td style="vertical-align: middle;text-align: right;">{{ number_format(soOutstanding($order)['byCheque']) }}</td>
                            <td style="vertical-align: middle;text-align: right;padding-right: 10px;">{{ number_format(soOutstanding($order)['byDeposit']) }}</td>
                            <td style="vertical-align: middle;text-align: right;padding-right: 10px;">{{ number_format(soOutstanding($order)['byCard']) }}</td>
                            <td style="vertical-align: middle;text-align: right;padding-right: 10px;">{{ number_format(soOutstanding($order)['balance']) }}</td>
                        </tr>
                    @endforeach
                @endif
                <tr>
                    <td colspan="2"
                        style="text-align: right;border-top: 1px solid #D0D0D0;padding-right: 25px;">
                        <strong>TOTAL</strong>
                    </td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;">
                        <strong>{{ number_format(array_get($orderData, 'totalSales')) }}</strong>
                    </td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;">
                        <strong>{{ number_format(array_get($paymentsData, 'cash')) }}</strong>
                    </td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;">
                        <strong>{{ number_format(array_get($paymentsData, 'cheque')) }}</strong>
                    </td>
                    <td style="text-align: right;padding-right: 10px;border-top: 1px solid #D0D0D0;">
                        <strong>{{ number_format(array_get($paymentsData, 'deposit')) }}</strong>
                    </td>
                    <td style="text-align: right;padding-right: 10px;border-top: 1px solid #D0D0D0;">
                        <strong>{{ number_format(array_get($paymentsData, 'card')) }}</strong>
                    </td>
                    <td style="text-align: right;padding-right: 10px;border-top: 1px solid #D0D0D0;">
                        <strong>{{ number_format(array_get($orderData, 'balance')) }}</strong>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    @endif
</div>