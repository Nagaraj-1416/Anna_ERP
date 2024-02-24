<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ 'SUPPLIER CREDIT ('.$credit->code.')' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="credit">

    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">SUPPLIER CREDIT</span>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">#{{ $credit->code ?? '' }}</span>
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
                    <span style="font-weight: 700;color: #455a64;font-family: sans-serif;font-size: 11px;">
                        <b>Credit date: </b>#{{ $credit->date }}
                    </span>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-family: sans-serif;font-size: 11px;">
                        {{--<b>Due date: </b>{{ $invoice->due_date }}--}}
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
                <th style="height: 35px;color: #fff;text-align: left;padding-left: 10px;">Credit notes</th>
                <th style="text-align: right;color: #fff;padding-right: 10px;width: 25%;">Credit Amount</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="height: 35px;width: 50px;text-align: left;vertical-align: top;padding-left: 10px;">{{ $credit->notes  }}</td>
                <td style="vertical-align: top;text-align: right;padding-right: 10px;">{{  number_format($credit->amount, 2) }}</td>
            </tr>
            </tbody>
        </table>
    </div>

    <div style="border-top: 1px solid #D0D0D0; margin-top: 15px;"></div>

    <!-- payment summary panel -->
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
            <tr>
                <td style="width: 75%;text-align: right;font-weight: 700;height: 25px; color: red;">
                    Refunds
                </td>
                <td style="text-align: right;font-weight: 700;height: 25px; color: red;padding-right: 10px;">
                    ({{ number_format($credit->refunds->sum('amount'), 2) }})
                </td>
            </tr>
            <tr>
                <td style="width: 75%;text-align: right;font-weight: 700;height: 25px; color: red;">
                    Used Credits
                </td>
                <td style="text-align: right;font-weight: 700;height: 25px; color: red;padding-right: 10px;">
                    ({{ number_format($credit->payments->sum('payment'), 2) }})
                </td>
            </tr>
            <tr>
                <td style="width: 75%;text-align: right;font-weight: 700;height: 25px;">
                    <strong>Credit Remaining</strong>
                </td>
                <td style="text-align: right;font-weight: 700;height: 25px;padding-right: 10px;">
                    <strong>{{ number_format(getSupplierCreditLimit($credit), 2) }}</strong>
                </td>
            </tr>
        </table>
    </div>
    <!-- signatures panel -->
    <div style="margin-top: 20px;">
        @include('purchases.general.signature')
    </div>

</div>
</body>
</html>