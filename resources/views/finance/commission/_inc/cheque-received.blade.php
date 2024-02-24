@extends('layouts.master')
@section('title', 'Sales Commission - Cheque Received')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4>Cheque Received of {{ $rep->name }}</h4>
                    <br />
                    <div class="form-body">
                        <table class="ui celled structured table collapse-table">
                            <thead>
                                <tr>
                                    <th>Cheque Details</th>
                                    <th>Customer</th>
                                    <th>Order#</th>
                                    <th>Order Date</th>
                                    <th>Payment Date</th>
                                    <th class="text-right">Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($chequesReceived as $key => $cheques)
                                    @php
                                        ['cheque_no' => $chequeNo] = chequeKeyToArray($key);
                                        $chequeData = getChequeDataByNo($cheques->first());
                                    @endphp
                                    <tr>
                                        <td colspan="6">
                                            <b>Cheque#: </b>{{ $key }} <br />
                                            <b>Cheque Date: </b>{{ $chequeData['formattedDate'] }} <br />
                                            <b>Written Bank#: </b>{{ $chequeData['bank'] }} <br />
                                        </td>
                                    </tr>
                                    @foreach($cheques as $cheque)
                                        <tr>
                                            <td></td>
                                            <td>
                                                {{ $cheque->customer->display_name }}
                                            </td>
                                            <td>
                                                <a target="_blank" href="{{ route('sales.order.show', $cheque->order->id) }}">
                                                    {{ $cheque->order->ref }}
                                                </a>
                                            </td>
                                            <td>{{ $cheque->order->order_date }}</td>
                                            <td>{{ $cheque->payment_date }}</td>
                                            <td class="text-right">{{ number_format($cheque->payment, 2) }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                                <tr>
                                    <td class="text-right" colspan="5">
                                        <b>TOTAL</b>
                                    </td>
                                    <td class="text-right"><b>{{ number_format($chequeReceivedAmount, 2) }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
