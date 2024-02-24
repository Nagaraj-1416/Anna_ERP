@extends('emails.layout.default')
@section('content')
    <div style="padding: 10px;">
        <p>Hello There,</p>
        <p>Below list of products are required for this allocation. Allocation details are below,</p>
        <p><span style="font-weight: 600;">Allocation#</span>: <a href="{{ route('sales.allocation.show', [$allocation]) }}" target="_blank">{{ $allocation->code }}</a></p>
        <p><span style="font-weight: 600;">Allocation on</span>: {{ $allocation->from_date }}</p>
        <p><span style="font-weight: 600;">Route</span>: {{ $allocation->route->name }}</p>
        <p><span style="font-weight: 600;">Rep</span>: {{ $allocation->rep->name }}</p>
        <p><span style="font-weight: 600;">Driver</span>: {{ $allocation->driver->short_name }}</p>
        <p style="text-align: center; text-decoration: underline; font-size: 14px;"><strong>Products list</strong></p>
        <table style="width:100%;border-collapse: collapse;">
            <thead>
                <tr style="border: 1px solid #39897a;">
                    <th style="text-align: left;border: 1px solid #39897a;padding: 10px;">Name</th>
                    <th style="text-align: left;border: 1px solid #39897a;padding: 10px;">In van</th>
                    <th style="text-align: left;border: 1px solid #39897a;padding: 10px;">Requested qty</th>
                    <th style="text-align: left;border: 1px solid #39897a;padding: 10px;">In store</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr style="border: 1px solid #39897a;">
                    <td style="border: 1px solid #39897a;padding: 10px;">{{ $product->product->name or '' }}</td>
                    <td style="border: 1px solid #39897a;padding: 10px;">{{ $product->quantity or '' }}</td>
                    <td style="border: 1px solid #39897a;padding: 10px;">{{ $product->cf_qty or '' }}</td>
                    <td style="border: 1px solid #39897a;padding: 10px;">{{ $product->product->stock ? $product->product->stock->available_stock : '' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="padding-top: 10px;">
            <a href="{{ route('sales.allocation.show', [$allocation]) }}" target="_blank">Please click here to view above allocation.</a>
        </p>
        <p style="padding-top: 10px;">Thank you.</p>
    </div>
@stop