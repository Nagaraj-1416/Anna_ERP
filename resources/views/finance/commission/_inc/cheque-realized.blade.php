@extends('layouts.master')
@section('title', 'Sales Commission - Cheques Realized')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4>Cheque Realized of {{ $rep->name }}</h4>
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
                                @foreach($chequeRealized as $key => $realized)
                                    @php
                                    ['cheque_no' => $chequeNo] = chequeKeyToArray($key);
                                    $chequeData = getChequeDataByNo($realized->first());
                                    @endphp
                                    <tr>
                                        <td colspan="6">
                                            <span><b>Cheque data: </b>{{ $chequeNo }}</span>
                                            |
                                            <span>{{ $chequeData['formattedDate'] }}</span>
                                            |
                                            <span>{{ $chequeData['bank'] }}</span>
                                        </td>
                                    </tr>
                                    @foreach($realized as $cheque)
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
                                            <td>{{ $cheque->chequeable->payment_date }}</td>
                                            <td>{{ $cheque->chequeable->preparedBy->name }}</td>
                                            <td class="text-right">{{ number_format($cheque->amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="6" class="text-right">
                                            <b>{{ number_format($realized->sum('amount'), 2) }}</b>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-right" colspan="5">
                                        <b>TOTAL</b>
                                    </td>
                                    <td class="text-right"><b>{{ number_format($chequeRealizedAmount, 2) }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
