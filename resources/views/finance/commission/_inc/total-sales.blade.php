@extends('layouts.master')
@section('title', 'Sales Commission - Total Sales')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4>Total Sales of {{ $rep->name }}</h4>
                    <br />
                    <div class="form-body">
                        <table class="ui celled structured table collapse-table">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Order#</th>
                                    <th>Order Date</th>
                                    <th>Prepared By</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($totalSales as $sale)
                                <tr>
                                    <td>
                                        <a target="_blank" href="{{ route('sales.customer.show', $sale->customer) }}">
                                            {{ $sale->customer->display_name }}
                                        </a>
                                    </td>
                                    <td>
                                        <a target="_blank" href="{{ route('sales.order.show', $sale) }}">
                                            {{ $sale->ref }}
                                        </a>
                                    </td>
                                    <td>{{ $sale->order_date }}</td>
                                    <td>{{ $sale->preparedBy->name }}</td>
                                    <td class="text-right">{{ number_format($sale->total, 2) }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td class="text-right" colspan="4">
                                        <b>TOTAL</b>
                                    </td>
                                    <td class="text-right"><b>{{ number_format($totalSales->sum('total'), 2) }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
