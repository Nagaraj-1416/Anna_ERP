@extends('layouts.master')
@section('title', 'Sales Commission - Credit Sales')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4>Credit Sales of {{ $rep->name }}</h4>
                    <br />
                    <div class="form-body">
                        <table class="ui celled structured table collapse-table">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Order#</th>
                                    <th>Order Date</th>
                                    <th class="text-right">Amount</th>
                                    <th class="text-right">Paid</th>
                                    <th class="text-right">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($creditOrders as $creditOrder)
                                <tr>
                                    <td>
                                        <a target="_blank" href="{{ route('sales.customer.show', $creditOrder->customer) }}">
                                            {{ $creditOrder->customer->display_name }}
                                        </a>
                                    </td>
                                    <td>
                                        <a target="_blank" href="{{ route('sales.order.show', $creditOrder) }}">
                                            {{ $creditOrder->ref }}
                                        </a>
                                    </td>
                                    <td>{{ $creditOrder->order_date }}</td>
                                    <td class="text-right">{{ $creditOrder->total }}</td>
                                    <td class="text-right">{{ $creditOrder->paid }}</td>
                                    <td class="text-right">{{ $creditOrder->balance }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td class="text-right" colspan="3">
                                        <b>TOTAL</b>
                                    </td>
                                    <td class="text-right"><b>{{ $creditOrders->sum('total') }}</b></td>
                                    <td class="text-right"><b>{{ $creditOrders->sum('paid') }}</b></td>
                                    <td class="text-right"><b>{{ $creditOrders->sum('balance') }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
