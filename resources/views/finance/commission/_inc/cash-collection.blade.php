@extends('layouts.master')
@section('title', 'Sales Commission - Cash Collection')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4>Cash Collection of {{ $rep->name }}</h4>
                    <br />
                    <div class="form-body">
                        <table class="ui celled structured table collapse-table">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Order#</th>
                                    <th>Order Date</th>
                                    <th>Payment Date</th>
                                    <th>Prepared By</th>
                                    <th class="text-right">Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cashCollection as $collection)
                                <tr>
                                    <td>
                                        <a target="_blank" href="{{ route('sales.customer.show', $collection->order->customer) }}">
                                            {{ $collection->order->customer->display_name }}
                                        </a>
                                    </td>
                                    <td>
                                        <a target="_blank" href="{{ route('sales.order.show', $collection->order) }}">
                                            {{ $collection->order->ref }}
                                        </a>
                                    </td>
                                    <td>{{ $collection->order->order_date }}</td>
                                    <td>{{ $collection->payment_date }}</td>
                                    <td>{{ $collection->preparedBy->name }}</td>
                                    <td class="text-right">{{ number_format($collection->payment, 2) }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td class="text-right" colspan="5">
                                        <b>TOTAL</b>
                                    </td>
                                    <td class="text-right"><b>{{ number_format($cashCollection->sum('payment'), 2) }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
