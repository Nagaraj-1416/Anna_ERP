<!-- customer statement preview page -->
<div style="font-family: sans-serif;font-size: 12px;" id="customer">

    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">CUSTOMER STATEMENT</span>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">#{{ $customer->code ?? '' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div style="border-top: 1px solid #D0D0D0; margin-top: 15px;"></div>

    <!-- customer, company order information -->
    <div style="padding-top: 15px;">
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;vertical-align: top;" align="left">
                    <span style="font-family: sans-serif;color: #6c757d; font-size: 12px;">
                        Generated on {{ carbon()->now()->format('F j, Y') }}
                    </span>
                </td>
                <td style="width: 50%;font-family: sans-serif;;vertical-align: top;" align="right">
                    <div style="font-family: sans-serif;">
                        <h4 style="font-weight: 600;font-family: sans-serif;"><b>{{ $company->name }}</b></h4>
                        <!-- company address -->
                        <span style="font-family: sans-serif;color: #6c757d; font-size: 10px;">
                            {{ $companyAddress->street_one }},
                            @if($companyAddress->street_two)
                                {{ $companyAddress->street_two }},
                            @endif
                            @if($companyAddress->city)
                                {{ $companyAddress->city }},
                            @endif
                            @if($companyAddress->province)
                                <br/> {{ $companyAddress->province }},
                            @endif
                            @if($companyAddress->postal_code)
                                {{ $companyAddress->postal_code }},
                            @endif
                            @if($companyAddress->country)
                                <br/> {{ $companyAddress->country->name }}.
                            @endif
                        </span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- account summary  -->
    <div style="padding-top: 15px;">
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;vertical-align: top;" align="left">
                    <div style="font-family: sans-serif;">
                        <h4 style="font-weight: 600;font-family: sans-serif;"><b>{{ $customer->display_name }}</b></h4>
                        <p style="font-family: sans-serif;color: #6c757d; font-size: 12px;">
                            {{ $customer->full_name }}
                        </p>
                        <!-- customer address -->
                        @if($address)
                            <span style="font-family: sans-serif;color: #6c757d; font-size: 10px;">
                            {{ $address->street_one }},
                                @if($address->street_two)
                                    {{ $address->street_two }},
                                @endif
                                @if($address->city)
                                    {{ $address->city }},
                                @endif
                                @if($address->province)
                                    <br/> {{ $address->province }},
                                @endif
                                @if($address->postal_code)
                                    {{ $address->postal_code }},
                                @endif
                                @if($address->country)
                                    <br/> {{ $address->country->name }}.
                                @endif
                        </span>
                        @endif
                    </div>
                </td>
                <td style="width: 50%;font-family: sans-serif;;vertical-align: top;" align="right">
                    <div style="font-family: sans-serif;">
                        <h6 style="font-weight: 600;font-family: sans-serif;"><b>Sales Summary</b></h6>
                        <table style="font-family: sans-serif;border-collapse: collapse;">
                            <tr>
                                <td style="text-align: right;">Sales:</td>
                                <td style="width: 70%; text-align: right;">{{ number_format(cusOutstanding($customer)['ordered'], 2) }}</td>
                            </tr>
                            {{--<tr>
                                <td style="text-align: right;">Invoiced:</td>
                                <td style="text-align: right;">{{ number_format(cusOutstanding2($customer)['invoiced'], 2) }}</td>
                            </tr>--}}
                            <tr>
                                <td style="text-align: right;">Received:</td>
                                <td style="text-align: right;">{{ number_format(cusOutstanding($customer)['paid'], 2) }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">Balance:</td>
                                <td style="text-align: right;">{{ number_format(cusOutstanding($customer)['balance'], 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;font-family: sans-serif;vertical-align: top;" align="left">
                    <div style="font-family: sans-serif; padding-top: 15px;">
                        <h6 style="font-weight: 600;font-family: sans-serif;"><b>Overdue Summary</b></h6>
                        <table style="font-family: sans-serif;border-collapse: collapse;">
                            <tr>
                                <td style="text-align: left;">1-30 days overdue:</td>
                                <td style="width: 50%; text-align: right;">{{ number_format(getDueData($dueInvoices, '1-30'), 2) }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">31-60 days overdue:</td>
                                <td style="text-align: right;">{{ number_format(getDueData($dueInvoices, '31-60'), 2) }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">61-90 days overdue:</td>
                                <td style="text-align: right;">{{ number_format(getDueData($dueInvoices, '61-90'), 2) }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">>90 days overdue:</td>
                                <td style="text-align: right;">{{ number_format(getDueData($dueInvoices, '91'), 2) }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;"><b>Total due:</b></td>
                                <td style="text-align: right;"><b>{{ number_format(getTotalDue($dueInvoices), 2) }}</b></td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td style="width: 50%;font-family: sans-serif;;vertical-align: top;" align="right">
                    <div style="font-family: sans-serif; padding-top: 15px;">
                        <h6 style="font-weight: 600;font-family: sans-serif;"><b>Credits Summary</b></h6>
                        <table style="font-family: sans-serif;border-collapse: collapse;">
                            <tr>
                                <td style="text-align: right;">Credits:</td>
                                <td style="width: 68%; text-align: right;">{{ number_format(cusCreditOutstanding($customer)['credits'], 2) }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">Refunded:</td>
                                <td style="text-align: right;">{{ number_format(cusCreditOutstanding($customer)['refunded'], 2) }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">Credited:</td>
                                <td style="text-align: right;">{{ number_format(cusCreditOutstanding($customer)['used'], 2) }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">Remaining:</td>
                                <td style="text-align: right;">{{ number_format(cusCreditOutstanding($customer)['balance'], 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>

    <!-- order panel -->
    <div style="padding-top: 15px;">
        <h4 style="font-weight: 600;font-family: sans-serif;"><b>Orders</b></h4>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
            <thead>
            <tr style="background-color: #2f3d4a;">
                <th style="height: 35px;width: 50px;color: #fff;text-align: left;padding-left: 10px;">#</th>
                <th style="color: #fff;text-align: left;">Order#</th>
                <th style="text-align: left;color: #fff;">Order date</th>
                <th style="text-align: left;color: #fff;">Cash/Credit</th>
                <th style="text-align: right;color: #fff;">Amount</th>
                <th style="text-align: right;color: #fff;">Cash</th>
                <th style="text-align: right;color: #fff;">Cheque</th>
                <th style="text-align: right;color: #fff;">Deposit</th>
                <th style="text-align: right;color: #fff;">Card</th>
                <th style="text-align: right;color: #fff;">Received</th>
                <th style="text-align: right;color: #fff;padding-right: 10px;">Balance</th>
            </tr>
            </thead>
            <tbody>
            @if(isset($orders))
                @foreach($orders as $orderKey => $order)
                    <tr>
                        <td style="height: 35px;width: 50px;text-align: left;vertical-align: middle;padding-left: 10px;">{{ ($orderKey+1) }}</td>
                        <td style="vertical-align: middle;">
                            <a target="_blank" href="{{ route('sales.order.show', [$order]) }}">{{ $order->ref }}</a>
                        </td>
                        <td style="vertical-align: middle;text-align: left;">{{ $order->created_at->format('F j, Y, g:i a') }}</td>
                        <td style="vertical-align: middle;text-align: left;">
                            @if($order->is_credit_sales == 'Yes')
                                {{ 'Credit' }}
                            @else
                                {{ 'Cash' }}
                            @endif
                        </td>
                        <td style="vertical-align: middle;text-align: right; background-color: #bbe3e8;">{{ number_format($order->total, 2) }}</td>
                        <td style="vertical-align: middle;text-align: right; background-color: #dee2e6;">{{ number_format(soOutstanding($order)['byCash'], 2) }}</td>
                        <td style="vertical-align: middle;text-align: right; background-color: #dee2e6;">{{ number_format(soOutstanding($order)['byCheque'], 2) }}</td>
                        <td style="vertical-align: middle;text-align: right; background-color: #dee2e6;">{{ number_format(soOutstanding($order)['byDeposit'], 2) }}</td>
                        <td style="vertical-align: middle;text-align: right; background-color: #dee2e6;">{{ number_format(soOutstanding($order)['byCard'], 2) }}</td>
                        <td style="vertical-align: middle;text-align: right; background-color: #bae0bb;">{{ number_format(soOutstanding($order)['paid'], 2) }}</td>
                        <td style="vertical-align: middle;text-align: right;padding-right: 10px; background-color: #ecdfb6;">{{ number_format(soOutstanding($order)['balance'], 2) }}</td>
                    </tr>
                @endforeach
            @endif
            <tr>
                <td colspan="4"
                    style="font-weight: 600; text-align: right;font-size: 14px;border-top: 1px solid #D0D0D0;">Total
                </td>
                <td style="width:8%;font-weight: 600; text-align: right;font-size: 14px;border-top: 1px solid #D0D0D0;">{{ number_format(cusOutstanding($customer)['ordered'], 2) }}</td>
                <td style="width:8%;font-weight: 600; text-align: right;font-size: 14px;border-top: 1px solid #D0D0D0;">{{ number_format(cusOutstanding($customer)['paidAsCash'], 2) }}</td>
                <td style="width:8%;font-weight: 600; text-align: right;font-size: 14px;border-top: 1px solid #D0D0D0;">{{ number_format(cusOutstanding($customer)['paidAsCheque'], 2) }}</td>
                <td style="width:8%;font-weight: 600; text-align: right;font-size: 14px;border-top: 1px solid #D0D0D0;">{{ number_format(cusOutstanding($customer)['paidAsDD'], 2) }}</td>
                <td style="width:8%;font-weight: 600; text-align: right;font-size: 14px;border-top: 1px solid #D0D0D0;">{{ number_format(cusOutstanding($customer)['paidAsCD'], 2) }}</td>
                <td style="width:8%;font-weight: 600; text-align: right;font-size: 14px;border-top: 1px solid #D0D0D0;">{{ number_format(cusOutstanding($customer)['paid'], 2) }}</td>
                <td style="width:8%;font-weight: 600; text-align: right;padding-right: 10px; font-size: 14px;border-top: 1px solid #D0D0D0;">{{ number_format(cusOutstanding($customer)['balance'], 2) }}</td>
            </tr>
            </tbody>
        </table>
    </div>

    <!-- invoices panel -->
    {{--<div style="padding-top: 15px;">
        <h4 style="font-weight: 600;font-family: sans-serif;"><b>Invoices</b></h4>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
            <thead>
            <tr style="background-color: #2f3d4a;">
                <th style="height: 35px;width: 50px;color: #fff;text-align: left;padding-left: 10px;">#</th>
                <th style="color: #fff;text-align: left;">Invoice no</th>
                <th style="text-align: left;color: #fff;">Invoice date</th>
                <th style="text-align: left;color: #fff;">Due date</th>
                <th style="text-align: left;color: #fff;">Status</th>
                <th style="text-align: right;color: #fff;">Amount</th>
                <th style="text-align: right;color: #fff;">Received</th>
                <th style="text-align: right;color: #fff;padding-right: 10px;">Balance</th>
            </tr>
            </thead>
            <tbody>
            @if(count($invoices))
                @foreach($invoices as $invoiceKey => $invoice)
                    <tr>
                        <td style="height: 35px;width: 50px;text-align: left;vertical-align: middle;padding-left: 10px;">{{ ($invoiceKey+1) }}</td>
                        <td style="vertical-align: middle;">
                            {{ $invoice->invoice_no }}
                        </td>
                        <td style="vertical-align: middle;text-align: left;">{{ $invoice->invoice_date }}</td>
                        <td style="vertical-align: middle;text-align: left;">{{ $invoice->due_date }}</td>
                        <td style="vertical-align: middle;text-align: left;">{{ $invoice->status }}</td>
                        <td style="vertical-align: middle;text-align: right;">{{ number_format($invoice->amount, 2) }}</td>
                        <td style="vertical-align: middle;text-align: right;">{{ number_format(invOutstanding($invoice)['paid'], 2) }}</td>
                        <td style="vertical-align: middle;text-align: right;padding-right: 10px;">{{ number_format(invOutstanding($invoice)['balance'], 2) }}</td>
                    </tr>
                @endforeach
            @endif
            <tr>
                <td colspan="5"
                    style="font-weight: 600; text-align: right;font-size: 14px;border-top: 1px solid #D0D0D0;">Total
                </td>
                <td style="width:15%;font-weight: 600; text-align: right;font-size: 14px;border-top: 1px solid #D0D0D0;">{{ number_format(cusOutstanding($customer)['invoiced'], 2) }}</td>
                <td style="width:15%;font-weight: 600; text-align: right;font-size: 14px;border-top: 1px solid #D0D0D0;">{{ number_format(cusOutstanding($customer)['paid'], 2) }}</td>
                <td style="width:15%;font-weight: 600; text-align: right;padding-right: 10px; font-size: 14px;border-top: 1px solid #D0D0D0;">{{ number_format(cusOutstanding($customer)['balance'], 2) }}</td>
            </tr>
            </tbody>
        </table>
    </div>--}}

    <!-- payments panel -->
    {{--<div style="padding-top: 15px;">
        <h4 style="font-weight: 600;font-family: sans-serif;"><b>Payments</b></h4>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
            <thead>
            <tr style="background-color: #2f3d4a;">
                <th style="height: 35px;width: 50px;color: #fff;text-align: left;padding-left: 10px;">#</th>
                <th style="color: #fff;text-align: left;">Date</th>
                <th style="text-align: left;color: #fff;">Type</th>
                <th style="text-align: left;color: #fff;">Mode</th>
                <th style="text-align: left;color: #fff;">Deposited to</th>
                <th style="text-align: left;color: #fff;">Status</th>
                <th style="text-align: right;color: #fff;padding-right: 10px;">Payment</th>
            </tr>
            </thead>
            <tbody>
            @if(count($payments))
                @foreach($payments as $paymentKey => $payment)
                    <tr>
                        <td style="height: 35px;width: 50px;text-align: left;vertical-align: middle;padding-left: 10px;">{{ ($paymentKey+1) }}</td>
                        <td style="vertical-align: middle;">
                            {{ $payment->payment_date }}
                        </td>
                        <td style="vertical-align: middle;text-align: left;">{{ $payment->payment_type }}</td>
                        <td style="vertical-align: middle;text-align: left;">{{ $payment->payment_mode }}</td>
                        <td style="vertical-align: middle;text-align: left;">{{ $payment->depositedTo->name or 'None' }}</td>
                        <td style="vertical-align: middle;text-align: left;">{{ $payment->status }}</td>
                        <td style="vertical-align: middle;text-align: right;padding-right: 10px;">{{ number_format($payment->payment, 2) }}</td>
                    </tr>
                @endforeach
            @endif
            <tr>
                <td colspan="6"
                    style="font-weight: 600; text-align: right;font-size: 14px;border-top: 1px solid #D0D0D0;">Total
                </td>
                <td style="width:15%;font-weight: 600; text-align: right;padding-right: 10px; font-size: 14px;border-top: 1px solid #D0D0D0;">{{ number_format(cusOutstanding($customer)['paid'], 2) }}</td>
            </tr>
            </tbody>
        </table>
    </div>--}}

    <!-- Estimates panel -->
    {{--<div style="padding-top: 15px;">
        <h4 style="font-weight: 600;font-family: sans-serif;"><b>Estimates</b></h4>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
            <thead>
            <tr style="background-color: #2f3d4a;">
                <th style="height: 35px;width: 50px;color: #fff;text-align: left;padding-left: 10px;">#</th>
                <th style="color: #fff;text-align: left;">Estimate no</th>
                <th style="text-align: left;color: #fff;">Estimate date</th>
                <th style="text-align: left;color: #fff;">Expiry date</th>
                <th style="text-align: left;color: #fff;">Status</th>
                <th style="text-align: right;color: #fff;padding-right: 10px;">Amount</th>
            </tr>
            </thead>
            <tbody>
            @if(count($estimates))
                @foreach($estimates as $estimateKey => $estimate)
                    <tr>
                        <td style="height: 35px;width: 50px;text-align: left;vertical-align: middle;padding-left: 10px;">{{ ($estimateKey+1) }}</td>
                        <td style="vertical-align: middle;">
                            {{ $estimate->estimate_no }}
                        </td>
                        <td style="vertical-align: middle;text-align: left;">{{ $estimate->estimate_date }}</td>
                        <td style="vertical-align: middle;text-align: left;">{{ $estimate->expiry_date }}</td>
                        <td style="vertical-align: middle;text-align: left;">{{ $estimate->status }}</td>
                        <td style="vertical-align: middle;text-align: right;padding-right: 10px;">{{ number_format($estimate->total, 2) }}</td>
                    </tr>
                @endforeach
            @endif
            <tr>
                <td colspan="5"
                    style="font-weight: 600; text-align: right;font-size: 14px;border-top: 1px solid #D0D0D0;">Total
                </td>
                <td style="width:15%;font-weight: 600; text-align: right;padding-right: 10px; font-size: 14px;border-top: 1px solid #D0D0D0;">{{ number_format(cusEstimateSummary($customer)['estimation'], 2) }}</td>
            </tr>
            </tbody>
        </table>
    </div>--}}

    <!-- Credits panel -->
    {{--<div style="padding-top: 15px;">
        <h4 style="font-weight: 600;font-family: sans-serif;"><b>Credits</b></h4>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
            <thead>
            <tr style="background-color: #2f3d4a;">
                <th style="height: 35px;width: 50px;color: #fff;text-align: left;padding-left: 10px;">#</th>
                <th style="color: #fff;text-align: left;">Credit no</th>
                <th style="text-align: left;color: #fff;">Credit date</th>
                <th style="text-align: left;color: #fff;">Status</th>
                <th style="text-align: right;color: #fff;padding-right: 10px;">Amount</th>
                <th style="text-align: right;color: #fff;padding-right: 10px;">Refunded</th>
                <th style="text-align: right;color: #fff;padding-right: 10px;">Credited</th>
                <th style="text-align: right;color: #fff;padding-right: 10px;">Remaining</th>
            </tr>
            </thead>
            <tbody>
            @if(count($credits))
                @foreach($credits as $creditKey => $credit)
                    <tr>
                        <td style="height: 35px;width: 50px;text-align: left;vertical-align: middle;padding-left: 10px;">{{ ($creditKey+1) }}</td>
                        <td style="vertical-align: middle;">
                            {{ $credit->code }}
                        </td>
                        <td style="vertical-align: middle;text-align: left;">{{ $credit->date }}</td>
                        <td style="vertical-align: middle;text-align: left;">{{ $credit->status }}</td>
                        <td style="vertical-align: middle;text-align: right;padding-right: 10px;">{{ number_format($credit->amount, 2) }}</td>
                        <td style="vertical-align: middle;text-align: right;padding-right: 10px;">{{ number_format($credit->refunds->sum('amount'), 2) }}</td>
                        <td style="vertical-align: middle;text-align: right;padding-right: 10px;">{{ number_format($credit->payments->sum('payment'), 2) }}</td>
                        <td style="vertical-align: middle;text-align: right;padding-right: 10px;">{{ number_format(getCustomerCreditLimit($credit), 2) }}</td>
                    </tr>
                @endforeach
            @endif
            <tr>
                <td colspan="7"
                    style="font-weight: 600; text-align: right;font-size: 14px;border-top: 1px solid #D0D0D0;">Total
                </td>
                <td style="width:15%;font-weight: 600; text-align: right;padding-right: 10px; font-size: 14px;border-top: 1px solid #D0D0D0;">{{ number_format(cusCreditOutstanding($customer)['balance'], 2) }}</td>
            </tr>
            </tbody>
        </table>
    </div>--}}

    <!-- signatures panel -->
    <div style="margin-top: 50px;">
        @include('sales.general.signature')
    </div>
</div>