@extends('layouts.master')
@section('title', 'Sales Commission - Cheques Returned')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4>Cheque Returned of {{ $rep->name }}</h4>
                    <br />
                    <div class="form-body">
                        <table class="ui celled structured table collapse-table">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Order#</th>
                                    <th>Order Date</th>
                                    <th>Payment Date</th>
                                    <th class="text-right">Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($chequeReturned as $key => $returned)
                                    @php
                                        ['cheque_no' => $chequeNo] = chequeKeyToArray($key);
                                        $chequeData = getChequeDataByNo($returned->first());
                                    @endphp
                                    <tr>
                                        <td colspan="5">
                                            <span><b>Cheque data: </b>{{ $chequeNo }}</span>
                                            |
                                            <span>{{ $chequeData['formattedDate'] }}</span>
                                            |
                                            <span>{{ $chequeData['bank'] }}</span>
                                            <br />
                                            <b>Bounced Date: </b>{{ $chequeData['bounced_date'] }}
                                        </td>
                                    </tr>
                                    @foreach($returned as $cheque)
                                        <tr>
                                            <td>
                                                <a target="_blank" href="{{ route('sales.customer.show', $cheque->chequeable->invoice->customer) }}">
                                                    {{ $cheque->chequeable->invoice->customer->display_name }}
                                                </a>
                                            </td>
                                            <td>
                                                <a target="_blank" href="{{ route('sales.order.show', $cheque->chequeable->invoice->order) }}">
                                                    {{ $cheque->chequeable->invoice->order->ref }}
                                                </a>
                                            </td>
                                            <td>{{ $cheque->chequeable->invoice->order->order_date }}</td>
                                            <td>{{ $cheque->cheque_date }}</td>
                                            <td class="text-right">{{ $cheque->amount }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="5" class="text-right">
                                            <b>{{ $chequeData['eachTotal'] }}</b>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-right" colspan="4">
                                        <b>TOTAL</b>
                                    </td>
                                    <td class="text-right"><b>{{ $chequeReturnedAmount }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
