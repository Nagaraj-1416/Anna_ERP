@extends('layouts.master')
@section('title', 'Sales Commission - Cheque Collection')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4>Cheque Collection of {{ $rep->name }}</h4>
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
                                @foreach($chequeCollection as $key => $collection)
                                    @php
                                        ['cheque_no' => $checkNo, 'bank_id' => $bankId] = chequeKeyToArray($key);
                                        $chequeData = getChequeDataByNo($key);
                                    @endphp
                                    <tr>
                                        <td colspan="6">
                                            <span><b>Cheque data: </b>{{ $checkNo }}</span>
                                            |
                                            <span>{{ $chequeData['formattedDate'] }}</span>
                                            |
                                            <span>{{ $chequeData['bank'] }}</span>
                                        </td>
                                    </tr>
                                    @foreach($collection as $payment)
                                        <tr>
                                            <td>
                                                <a target="_blank" href="{{ route('sales.customer.show', $payment->order->customer) }}">
                                                    {{ $payment->order->customer->display_name }}
                                                </a>
                                            </td>
                                            <td>
                                                <a target="_blank" href="{{ route('sales.order.show', $payment->order) }}">
                                                    {{ $payment->order->ref }}
                                                </a>
                                            </td>
                                            <td>{{ $payment->order->order_date }}</td>
                                            <td>{{ $payment->payment_date }}</td>
                                            <td>{{ $payment->preparedBy->name }}</td>
                                            <td class="text-right">{{ number_format($payment->payment, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="6" class="text-right">
                                            <b>{{ number_format($collection->sum('payment'), 2) }}</b>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-right" colspan="5">
                                        <b>TOTAL</b>
                                    </td>
                                    <td class="text-right"><b>{{ number_format($chequeCollectionAmount, 2) }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
