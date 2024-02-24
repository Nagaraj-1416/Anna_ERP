<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>@yield('title')</title>
</head>
<body style="margin: 0px;background-color: whitesmoke;font-family: Arial;font-size: 12px;">
<table style="width:100%;border-collapse: collapse;font-size: 12px;">
    <tr style="border-bottom: 1px solid #39897a;background-color: #ffffff;">
        <td style="width: 5%"></td>
        <td style="padding: 5px;">
            <div style="float: left; text-align: left;">
                <img alt="{{ env('APP_NAME') }}" src="{!! isset($message) ? $message->embed(public_path(logo())) : logoSrc() !!}" style="width: 85px;">
            </div>
            <div style="float: right; text-align: right;">
                <span style="font-weight: bold; font-size: 16px; line-height: 24px; color: #0a0a0a">{{ 'AnnA Industry' }}</span><br />
                <span style="text-align: right; color: #0a0a0a">{{ carbon()->now()->format('l jS \\of F Y') }}</span>
            </div>
        </td>
        <td style="width: 5%"></td>
    </tr>
</table>
<table style="width:100%;border-collapse: collapse;">
    <tr>
        <td style="width: 5%;background-color: whitesmoke;"></td>
        <td style="background-color: rgb(255, 255, 255);">
            @yield('content')
        </td>
        <td style="width: 5%;background-color: whitesmoke;"></td>
    </tr>
</table>
<table style="width:100%;border-collapse: collapse;">
    <tr style="border-top: 1px solid rgb(234, 234, 234);background-color: rgb(250, 250, 250);">
        <td style="width: 5%;background-color: #39897a;"></td>
        <td style="background-color: #39897a; text-align: left; font-size: 12px; padding: 10px;">
            <p style="color: #fff; font-size: 12px; margin-top: 10px;"><strong>Email:</strong> annacoff@gmail.com, <strong>Phone:</strong> 0212241565, <strong>Mobile:</strong> 0773769031, <strong>Fax:</strong> 0212241501</p>
        </td>
        <td style="width: 5%;background-color: #39897a;"></td>
    </tr>
</table>
</body>
</html>