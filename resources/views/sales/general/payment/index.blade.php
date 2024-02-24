<?php $request = request() ?>
<div class="card">
    <div class="card-body">
        <h3><b>PAYMENTS RECEIVED</b> <span class="pull-right">Total Payments: {{ count($payments) }}</span></h3>
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
                    @if($request->is('sales/invoice*'))
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

                                <span><b>Deposited to: </b>{{ $payment->depositedTo->name or 'None' }}</span>
                            </td>
                            <td>{{ $payment->preparedBy->name or 'None' }}</td>
                            <td>
                                <span class="{{ statusLabelColor($payment->status) }}">{{ $payment->status }}</span>
                            </td>
                            <td class="text-right">{{ number_format($payment->payment, 2) }}</td>
                            @if($request->is('sales/invoice*'))
                                <td style="text-align: right;">
                                    @if(!($payment->status == 'Canceled'|| $payment->status == 'Refunded'))
                                        {{--@can('edit', $payment)
                                            <a href="" class="btn btn-primary btn-sm edit-payment-btn"
                                               data-id="{{ $payment->id }}">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        @endcan--}}
                                        @can('delete', $payment)
                                            <a href="" class="btn btn-danger btn-sm delete-payment-btn"
                                               data-id="{{ $payment->id }}">
                                                <i class="fa fa-remove"></i>
                                            </a>
                                        @endcan
                                    @endif
                                    @can('print', $payment)
                                        <a target="_blank"
                                           href="{{ route('sales.payment.print', ['invoice' => $payment->invoice,'payment' => $payment]) }}"
                                           class="btn btn-inverse btn-sm"><i class="fa fa-print"></i></a>
                                    @endcan
                                    @if($payment->status == 'Paid')
                                        @can('cancel', $payment)
                                            <button class="btn btn-danger btn-sm cancel-payment-btn"
                                                    data-id="{{ $payment->id }}">
                                                Cancel
                                            </button>
                                        @endcan
                                    @endif
                                    @if($payment->status == 'Canceled')
                                        @can('refund', $payment)
                                            <a href="" class="btn btn-warning btn-sm refund-payment-btn"
                                               data-id="{{ $payment->id }}">
                                                Refund
                                            </a>
                                        @endcan
                                    @endif
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