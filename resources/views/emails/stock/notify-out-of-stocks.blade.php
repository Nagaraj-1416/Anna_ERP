@extends('emails.layout.default')
@section('content')
    <div style="padding: 10px;">
        <p>Hello There,</p>
        <p>Below list of products are now out of stock.</p>
        <p style="text-align: center; text-decoration: underline; font-size: 14px;"><strong>Products list</strong></p>
        <table style="width:100%;border-collapse: collapse;">
            <thead>
                <tr style="border: 1px solid #39897a;">
                    <th style="text-align: left;border: 1px solid #39897a;padding: 10px;">Name</th>
                    <th style="text-align: left;border: 1px solid #39897a;padding: 10px;">Available qty</th>
                    <th style="text-align: left;border: 1px solid #39897a;padding: 10px;">Re-order level</th>
                    <th style="text-align: left;border: 1px solid #39897a;padding: 10px;">Store</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stocks as $stock)
                    <tr style="border: 1px solid #39897a;">
                        <td style="border: 1px solid #39897a;padding: 10px;">{{ $stock->product->name or '' }}</td>
                        <td style="border: 1px solid #39897a;padding: 10px;">{{ $stock->available_stock or '' }}</td>
                        <td style="border: 1px solid #39897a;padding: 10px;">{{ $stock->min_stock_level or '' }}</td>
                        <td style="border: 1px solid #39897a;padding: 10px;">{{ $stock->store->name or '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p style="padding-top: 10px;">Thank you.</p>
    </div>
@stop