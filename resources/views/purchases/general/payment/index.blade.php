<?php $request = request() ?>
<div class="card">
    <div class="card-body">
        <h3><b>PAYMENTS MADE</b> <span class="pull-right">Total Payments: {{ count($payments) }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Payment details</th>
                    <th>Recorded by</th>
                    <th>Status</th>
                    <th class="text-right">Amount</th>
                    @if($request->is('purchase/bill*'))
                        <th width="20%"></th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @if(count($payments))
                    @foreach($payments as $payKey => $payment)
                        <tr>
                            <td>{{ $payment->payment_date }}</td>
                            <td>{{ $payment->payment_type }}</td>
                            <td>
                                <span><b>Mode: </b>{{ $payment->payment_mode }}</span><br/>
                                @if($payment->payment_mode == 'Cheque')
                                    <span><b>Cheque no: </b>{{ $payment->cheque_no or 'None' }}</span><br/>
                                    <span><b>Cheque date: </b>{{ $payment->cheque_date or 'None' }}</span><br/>
                                    <span><b>Written bank: </b>{{ $payment->bank->name or 'None' }}</span><br/>
                                    <span><b>Type: </b>{{ $payment->cheque_type or 'None' }}</span><br/>
                                @elseif($payment->payment_mode == 'Direct Deposit')
                                    <span><b>Account no: </b>{{ $payment->account_no or 'None' }}</span><br/>
                                    <span><b>Deposited date: </b>{{ $payment->deposited_date or 'None' }}</span><br/>
                                    <span><b>Deposited bank: </b>{{ $payment->bank->name or 'None' }}</span><br/>
                                @elseif($payment->payment_mode == 'Credit Card')
                                    <span><b>Card no: </b>{{ $payment->card_no or 'None' }}</span><br/>
                                    <span><b>Card holder name: </b>{{ $payment->card_holder_name or 'None' }}</span><br/>
                                    <span><b>Expiry date: </b>{{ $payment->expiry_date or 'None' }}</span><br/>
                                    <span><b>Bank: </b>{{ $payment->bank->name or 'None' }}</span><br/>
                                @endif
                                <span><b>Paid through: </b>{{ $payment->paidThrough->name or 'None' }}</span>
                            </td>
                            <td>{{ $payment->preparedBy->name or 'None' }}</td>
                            <td>
                                <span class="{{ statusLabelColor($payment->status) }}">{{ $payment->status }}</span>
                            </td>
                            <td class="text-right">{{ number_format($payment->payment, 2) }}</td>
                            @if($request->is('purchase/bill*'))
                                <td style="text-align: right;">
                                    @if(!($payment->status == 'Canceled'|| $payment->status == 'Refunded'))
                                        @can('edit', $payment)
                                            <a href="" class="btn btn-primary btn-sm edit-payment-btn"
                                               data-id="{{ $payment->id }}">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        @endcan
                                    @endif
                                    @can('cancel', $payment)
                                        @if($payment->status == 'Paid')
                                            <a href="" class="btn btn-danger btn-sm cancel-payment-btn"
                                               data-id="{{ $payment->id }}">
                                                Cancel
                                            </a>
                                        @endif
                                    @endcan
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>No Payments Found...</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>