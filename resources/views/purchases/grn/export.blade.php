<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ 'PURCHASE ORDER ('.$order->po_no.')' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="purchaseOrder">

    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">PURCHASE ORDER</span>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">#{{ $order->po_no ?? '' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div style="border-top: 1px solid #D0D0D0; margin-top: 15px;"></div>

    <!-- Supplier, company order information -->
    <div style="padding-top: 15px;">
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <div style="font-family: sans-serif;">
                        <h4 style="font-weight: 600;font-family: sans-serif;"><b>{{ $company->name }}</b></h4>
                        <!-- company address -->
                        <span style="font-family: sans-serif;color: #6c757d; font-size: 10px;">
                            {{ $companyAddress->street_one or '' }},
                            @if($companyAddress && $companyAddress->street_two)
                                {{ $companyAddress->street_two }},
                            @endif
                            @if($companyAddress && $companyAddress->city)
                                {{ $companyAddress->city }},
                            @endif
                            @if($companyAddress && $companyAddress->province)
                                <br/> {{ $companyAddress->province }},
                            @endif
                            @if($companyAddress && $companyAddress->postal_code)
                                {{ $companyAddress->postal_code }},
                            @endif
                            @if($companyAddress && $companyAddress->country)
                                <br/> {{ $companyAddress->country->name }}.
                            @endif
                        </span>
                    </div>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <div style="font-family: sans-serif;">
                        <h4 style="font-weight: 600;font-family: sans-serif;"><b>{{ $supplier->display_name }}</b></h4>
                        <!-- Supplier address -->
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
            </tr>
        </table>
    </div>

    <!-- order line items -->
    <div style="padding-top: 15px;">
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <span style="font-weight: 700;color: #455a64;font-family: sans-serif;font-size: 12px;">
                        <b>Order date: </b>{{ $order->order_date }}
                    </span>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-family: sans-serif;font-size: 12px;">
                        <b>Delivery date: </b>{{ $order->delivery_date }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <!-- order payment and balance summary -->
    <div style="padding-top: 5px;">
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
            <thead>
            <tr style="background-color: #2f3d4a;">
                <th style="height: 35px;width: 50px;color: #fff;text-align: left;padding-left: 10px;">#</th>
                <th style="color: #fff;text-align: left;width: 250px;">Items & Description</th>
                <th style="text-align: center;color: #fff;">Quantity</th>
                <th style="text-align: right;color: #fff;">Rate</th>
                <th style="text-align: right;color: #fff;">Discount</th>
                <th style="text-align: right;color: #fff;padding-right: 10px;">Total</th>
            </tr>
            </thead>
            <tbody>
            @if(count($items))
                @foreach($items as $itemKey => $item)
                    <tr>
                        <td style="height: 35px;width: 50px;text-align: left;vertical-align: middle;padding-left: 10px;">{{ ($itemKey+1) }}</td>
                        <td style="vertical-align: middle;">
                            {{ $item->name }}<br/>
                            <span style="font-size: 10px;color: #818181">{{ $item->pivot->notes }}</span>
                        </td>
                        <td style="vertical-align: middle;text-align: center;">{{ $item->pivot->quantity }}</td>
                        <td style="vertical-align: middle;text-align: right;">{{ number_format($item->pivot->rate, 2) }}</td>
                        <td style="vertical-align: middle;text-align: right;">{{ number_format($item->pivot->discount, 2) }}</td>
                        <td style="vertical-align: middle;text-align: right;padding-right: 10px;">{{ number_format($item->pivot->amount, 2) }}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>

    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>

    <!-- payment summary panel -->
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 30%;padding-left: 10px;padding-top: 5px; vertical-align: top;"></td>
                <td style="width: 70%;text-align: right; vertical-align: top;">
                    <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
                        @if($order->adjustment || $order->discount)
                        <tr>
                            <td style="width: 75%;text-align: right;font-weight: 700;height: 25px;">Sub total</td>
                            <td style="text-align: right;padding-right: 10px;">{{ $order->sub_total }}</td>
                        </tr>
                        @endif
                        @if($order->discount)
                        <tr>
                            <td style="width: 75%;text-align: right;font-weight: 700;height: 25px;">Discount</td>
                            <td style="text-align: right;padding-right: 10px;">{{ $order->discount }}</td>
                        </tr>
                        @endif
                        @if($order->adjustment)
                        <tr>
                            <td style="width: 75%;text-align: right;font-weight: 700;height: 25px;">Adjustment</td>
                            <td style="text-align: right;padding-right: 10px;">{{ $order->adjustment }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td style="width: 75%;text-align: right;font-weight: 700;height: 25px;"><strong>Total</strong></td>
                            <td style="text-align: right;padding-right: 10px;"><strong>{{ number_format($order->total, 2) }}</strong></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- bills details -->
    @if(count($bills))
        <div style="margin-top: 5px;">
            <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
                <thead>
                <tr>
                    <th colspan="5" style="font-weight: 600;font-family: sans-serif;font-size: 14px; text-align: left;">
                        Bill Details
                    </th>
                </tr>
                <tr style="background-color: #2f3d4a;">
                    <th style="height: 35px;width: 50px;color: #fff;text-align: left;padding-left: 10px;">#</th>
                    <th style="color: #fff;width: 250px;text-align: left;">Bill no</th>
                    <th style="color: #fff;text-align: left;">Bill date</th>
                    <th style="color: #fff;text-align: left;">Due date</th>
                    <th style="text-align: right;color: #fff;padding-right: 10px;">Amount</th>
                </tr>
                </thead>
                <tbody>
                @foreach($bills as $billKey => $bill)
                    <tr>
                        <td style="height: 35px;width: 50px;text-align: left;vertical-align: middle;padding-left: 10px;">{{ ($billKey+1) }}</td>
                        <td style="vertical-align: middle;padding-right: 10px;">{{ $bill->bill_no }}</td>
                        <td style="vertical-align: middle;padding-right: 10px;">{{ $bill->bill_date }}</td>
                        <td style="vertical-align: middle;padding-right: 10px;">{{ $bill->due_date }}</td>
                        <td style="vertical-align: middle;text-align: right;padding-right: 10px;">{{ number_format($bill->amount, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>
        <div>
            <table style="font-family: sans-serif;width: 100%;">
                <tr>
                    <td style="width: 30%;padding-left: 10px;padding-top: 5px; vertical-align: top;"></td>
                    <td style="width: 70%;text-align: right; vertical-align: top;">
                        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
                            <tr>
                                <td style="width: 75%;text-align: right;font-weight: 700;height: 25px;">
                                    <strong>Total Billed</strong></td>
                                <td style="text-align: right;padding-right: 10px;"><strong>{{ number_format(poOutstanding($order)['billed'], 2) }}</strong></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    @endif

    <!-- payments details -->
    @if(count($payments))
        <div style="margin-top: 5px;">
            <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
                <thead>
                <tr>
                    <th colspan="5" style="font-weight: 600;font-family: sans-serif;font-size: 14px; text-align: left;">
                        Payment Details
                    </th>
                </tr>
                <tr style="background-color: #2f3d4a;">
                    <th style="height: 35px;width: 50px;color: #fff;text-align: left;padding-left: 10px;">#</th>
                    <th style="color: #fff;width: 250px;text-align: left;">Payment date</th>
                    <th style="color: #fff;text-align: left;">Payment type</th>
                    <th style="color: #fff;text-align: left;">Payment mode</th>
                    <th style="text-align: right;color: #fff;padding-right: 10px;">Amount</th>
                </tr>
                </thead>
                <tbody>
                @foreach($payments as $paymentKey => $payment)
                    <tr>
                        <td style="height: 35px;width: 50px;text-align: left;vertical-align: middle;padding-left: 10px;">{{ ($paymentKey+1) }}</td>
                        <td style="vertical-align: middle;padding-right: 10px;">{{ $payment->payment_date }}</td>
                        <td style="vertical-align: middle;padding-right: 10px;">{{ $payment->payment_type }}</td>
                        <td style="vertical-align: middle;padding-right: 10px;">{{ $payment->payment_mode }}</td>
                        <td style="vertical-align: middle;text-align: right;padding-right: 10px;">{{ number_format($payment->payment, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>

        <div>
            <table style="font-family: sans-serif;width: 100%;">
                <tr>
                    <td style="width: 30%;padding-top: 5px; vertical-align: top;"></td>
                    <td style="width: 70%;text-align: right; vertical-align: top;">
                        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
                            <tr>
                                <td style="width: 75%;text-align: right;font-weight: 700;height: 25px;">
                                    <strong>Total Made</strong></td>
                                <td style="text-align: right;padding-right: 10px;"><strong>{{ number_format(poOutstanding($order)['paid'], 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td style="width: 75%;text-align: right;font-weight: 700;height: 25px;">
                                    <strong>Remaining Payment</strong></td>
                                <td style="text-align: right;padding-right: 10px;"><strong>{{ number_format(poOutstanding($order)['balance'], 2) }}</strong></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    @endif

    {{--<div>
        <table style="font-family: sans-serif;width: 100%;">
            <tr>
                <td style="width: 50%;padding-top: 5px; vertical-align: top;">
                    <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 14px; background-color: #eaeaea;padding: 10px;">
                        <tr>
                            <td style="width: 75%;text-align: left;font-weight: 700;height: 35px;padding: 5px;">
                                <strong>Total Outstanding</strong></td>
                            <td style="text-align: right;padding: 5px;font-weight: 700;">
                                <strong>{{ number_format(supOutstanding($supplier)['balance'], 2) }}</strong></td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%;text-align: right; vertical-align: top;"></td>
            </tr>
        </table>
    </div>--}}

    <!-- terms and notes panel -->
    <div style="margin-top: 20px;">
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td>
                    Terms & Conditions<br/>
                    <span style="font-size: 10px;color: #818181">{{ $order->terms }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    Notes<br/>
                    <span style="font-size: 10px;color: #818181">{{ $order->notes }}</span>
                </td>
            </tr>
        </table>
    </div>
    <!-- signatures panel -->
    <div style="margin-top: 50px;">
        @include('purchases.general.signature')
    </div>
</div>
</body>
</html>