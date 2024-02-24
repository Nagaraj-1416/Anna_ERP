@extends('layouts.master')
@section('title', 'Sales Commission - Sales Returns')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4>Sales Return Items of {{ $rep->name }}</h4>
                    <br />
                    <div class="form-body">
                        <table class="ui celled structured table collapse-table">
                            <thead>
                                <tr>
                                    <th>Return Code</th>
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Reason</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-right">Sold Rate</th>
                                    <th class="text-right">Returned Rate</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expiredSalesReturns as $return)
                                <tr>
                                    <td>
                                        <a target="_blank" href="{{ route('sales.return.show', $return->salesReturn) }}">
                                            {{ $return->salesReturn->code }}
                                        </a>
                                    </td>
                                    <td>
                                        <a target="_blank" href="{{ route('sales.customer.show', $return->customer) }}">
                                            {{ $return->customer->display_name }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $return->product->name }}
                                    </td>
                                    <td>{{ $return->reason }}</td>
                                    <td class="text-center">{{ $return->qty }}</td>
                                    <td class="text-right">{{ $return->sold_rate }}</td>
                                    <td class="text-right">{{ $return->returned_rate }}</td>
                                    <td class="text-right">{{ $return->returned_amount }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td class="text-right" colspan="7">
                                        <b>TOTAL</b>
                                    </td>
                                    <td class="text-right"><b>{{ $expiredSalesReturns->sum('returned_amount') }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
