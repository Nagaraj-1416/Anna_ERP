<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME').' | Staffs' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="salesAllocation">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">Staffs</span><br/>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">Total Staffs {{ $staffs->count() }}</span>
                </td>
            </tr>
        </table>
    </div>

    <hr style="margin-bottom: 10px;">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
            <thead>
            <tr style="background-color: #2f3d4a;">
                <th style="height: 35px;color: #fff;text-align: left;width: 15%;padding-left: 15px;">Code</th>
                <th style="text-align: left;color: #fff;width: 25%;">Full Name</th>
                <th style="text-align: left;color: #fff;width: 25%;">Email</th>
                <th style="text-align: left;color: #fff;width: 15%;">Mobile</th>
                <th style="text-align: left;color: #fff;width: 15%;padding-right: 10px;">Phone</th>
            </tr>
            </thead>
            <tbody>
            @if($staffs)
                @foreach($staffs as $key => $staff)
                    <tr>
                        <td style="vertical-align: middle;width: 10%;height: 35px;padding-left: 15px;">{{ $staff->code ?? 'None' }}</td>
                        <td style="vertical-align: middle;text-align: left;width: 25%;">{{ $staff->full_name ?? 'None'}}</td>
                        <td style="vertical-align: middle;text-align: left;width: 25%;">{{ $staff->email ?? 'None'}}</td>
                        <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $staff->mobile ?? 'None'}}</td>
                        <td style="vertical-align: middle;text-align: left;width: 15%;padding-right: 10px;">{{ $staff->phone ?? 'None' }}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
</body>
</html>