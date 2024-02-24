<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME').' | Estimate ('.$estimate->estimate_no.')' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="estimate">

    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">ESTIMATE</span>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">#{{ $estimate->estimate_no ?? '' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div style="border-top: 1px solid #D0D0D0; margin-top: 15px;"></div>

    <!-- customer, company order information -->
    <div style="padding-top: 15px;">
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
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
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <div style="font-family: sans-serif;">
                        <h4 style="font-weight: 600;font-family: sans-serif;"><b>{{ $customer->display_name }}</b></h4>
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
            </tr>
        </table>
    </div>

    <!-- order line items -->
    <div style="padding-top: 15px;">
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <span style="font-weight: 700;color: #455a64;font-family: sans-serif;font-size: 11px;">
                        <b>Order date: </b>{{ $estimate->estimate_date }}
                    </span>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-family: sans-serif;font-size: 11px;">
                        <b>Expiry date: </b>{{ $estimate->expiry_date }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <!-- order payment and balance summary -->
    <div style="padding-top: 15px;">
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
                        <td style="height: 35px;width: 50px;text-align: left;vertical-align: top;padding-left: 10px;">{{ ($itemKey+1) }}</td>
                        <td style="vertical-align: top;">
                            {{ $item->name }}<br/>
                            <span style="font-size: 10px;color: #818181">{{ $item->pivot->notes }}</span>
                        </td>
                        <td style="vertical-align: top;text-align: center;">{{ $item->pivot->quantity }}</td>
                        <td style="vertical-align: top;text-align: right;">{{ $item->pivot->rate }}</td>
                        <td style="vertical-align: top;text-align: right;">{{ $item->pivot->discount }}</td>
                        <td style="vertical-align: top;text-align: right;padding-right: 10px;">{{ $item->pivot->amount }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6">No Items Found.</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>

    <div style="border-top: 1px solid #D0D0D0; margin-top: 15px;"></div>

    <!-- payment summary panel -->
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 40%;padding-left: 10px;padding-top: 5px;">
                </td>
                <td style="width: 60%;text-align: right; vertical-align: top;">
                    <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
                        <tr>
                            <td style="width: 75%;text-align: right;font-weight: 700;height: 25px;">Sub total</td>
                            <td style="text-align: right;padding-right: 10px;">{{ $estimate->sub_total }}</td>
                        </tr>
                        <tr>
                            <td style="width: 75%;text-align: right;font-weight: 700;height: 25px;">Discount</td>
                            <td style="text-align: right;padding-right: 10px;">{{ $estimate->discount }}</td>
                        </tr>
                        <tr>
                            <td style="width: 75%;text-align: right;font-weight: 700;height: 25px;">Adjustment</td>
                            <td style="text-align: right;padding-right: 10px;">{{ $estimate->adjustment }}</td>
                        </tr>
                        <tr>
                            <td style="width: 75%;text-align: right;font-weight: 700;height: 25px;">
                                <strong>Total</strong></td>
                            <td style="text-align: right;padding-right: 10px;"><strong>{{ $estimate->total }}</strong>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- terms and notes panel -->
    <div style="margin-top: 20px;">
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td>
                    Terms & Conditions<br/>
                    <span style="font-size: 10px;color: #818181">{{ $estimate->terms }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    Notes<br/>
                    <span style="font-size: 10px;color: #818181">{{ $estimate->notes }}</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- signatures panel -->
    <div style="margin-top: 20px;">
        @include('sales.general.signature')
    </div>

</div>
</body>
</html>