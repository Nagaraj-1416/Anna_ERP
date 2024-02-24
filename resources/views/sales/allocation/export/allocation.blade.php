<div>
    <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
        <tr>
            <td style="width: 50%;font-family: sans-serif;" align="left">
                <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">SALES ALLOCATION</span>
            </td>
            <td style="width: 50%;font-family: sans-serif;" align="right">
                <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">#{{ $allocation->code ?? '' }}</span>
            </td>
        </tr>
    </table>
</div>

<div style="border-top: 1px solid #D0D0D0; margin-top: 15px;"></div>
<div style="padding-top: 15px;">
    <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
        <tr>
            <td style="width: 50%;font-family: sans-serif;" align="left">
                <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 14px;">
                            <b>Allocation type:</b> {{ $allocation->day_type }} {{ $allocation->day_type == 'Single' ? 'Day' : 'Days' }}
                            <br>
                        </span>
                </div>
            </td>
        </tr>
        <tr>
            <td style="width: 50%;font-family: sans-serif;" align="left">
                <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 14px;">
                            <b>Allocation from:</b> {{ $allocation->from_date }} <br>
                        </span>
                </div>
            </td>
            <td style="width: 50%;font-family: sans-serif;">
                <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 14px;">
                            <b>Allocation To:</b> {{ $allocation->to_date }} <br>
                        </span>
                </div>
            </td>
        </tr>
        <tr>
            <td style="width: 50%;font-family: sans-serif;" align="left">
                <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 14px;">
                            <b>Sales location type:</b> {{ $allocation->sales_location }} <br>
                        </span>
                </div>
            </td>
            <td style="width: 50%;font-family: sans-serif;">
                <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 14px;">
                            <b>Sales location:</b> {{ $allocation->salesLocation->name.' ('.$allocation->salesLocation->code.')' }}
                            <br>
                        </span>
                </div>
            </td>
        </tr>

        <tr>
            <td style="width: 30%;font-family: sans-serif;" align="left">
                <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 14px;">
                            <b>Vehicle:</b> {{ $allocation->vehicle->vehicle_no }} <br>
                        </span>
                </div>
            </td>
            <td style="width: 30%;font-family: sans-serif;">
                <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 14px;">
                            <b>Rep:</b> {{ $allocation->rep->name.' ('.$allocation->rep->code.')' }}
                            <br>
                        </span>
                </div>
            </td>
            <td style="width: 30%;font-family: sans-serif;">
                <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 14px;">
                            <b>Route:</b> {{ $allocation->route->name.' ('.$allocation->route->code.')' }}
                            <br>
                        </span>
                </div>
            </td>
        </tr>
        <tr>
            <td style="width: 50%;font-family: sans-serif;" align="left">
                <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 14px;">
                            <b>Company:</b> {{ $allocation->company->name }} <br>
                        </span>
                </div>
            </td>
            <td style="width: 50%;font-family: sans-serif;">
                <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 14px;">
                            <b>Prepared by:</b> {{ $allocation->preparedBy->name or 'None' }}
                            <br>
                        </span>
                </div>
            </td>
        </tr>
    </table>
</div>
