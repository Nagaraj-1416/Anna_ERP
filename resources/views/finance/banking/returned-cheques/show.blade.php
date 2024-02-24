@extends('layouts.master')
@section('title', 'Cheque Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Cheque# {{ $chequeNo }} | {{ $chequeData['chequeType'] }} | Is settled? "{{ $chequeData['settled'] }}" </h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                <a href="{{ route('finance.return.cheques.create.payment', [$chequeKey]) }}"
                                   class="btn waves-effect waves-light btn-success btn-sm" target="_blank">
                                    <i class="ti-check"></i> Make Payment
                                </a>
                            </div>
                            <div class="pull-right"></div>
                        </div>
                    </div>
                    <div class="row m-t-20">
                        <div class="col-md-3">
                            <p><b>Cheque written date :</b>
                                {{ $chequeData['formattedDate']  ?? 'None' }}
                            </p>
                        </div>
                        <div class="col-md-3">
                            <p><b>Cheque written bank :</b>
                                {{ $chequeData['bank']  ?? 'None' }}
                            </p>
                        </div>
                        <div class="col-md-3">
                            <p><b>Cheque registered date :</b>
                                {{ $chequeData['formattedRegDate']  ?? 'None' }}
                            </p>
                        </div>
                        <div class="col-md-3">
                            <p><b>Amount :</b>
                                {{ number_format($chequeData['eachTotal'], 2) }}
                            </p>
                        </div>
                    </div>
                    <br />
                    <h5>References</h5>
                    <div class="row m-t-10">
                        <div class="col-md-12">
                            <table class="ui celled structured table collapse-table">
                                <thead>
                                    <tr>
                                        <th style="width: 20%;">Order#</th>
                                        <th>Customer</th>
                                        <th style="width: 15%;">Payment date</th>
                                        <th class="text-right" style="width: 15%;">Payment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cheques as $chequeKey => $chequeValue)
                                    <tr>
                                        <td>
                                            <a target="_blank" href="/sales/invoice/{{ $chequeValue->chequeable->invoice->id }}">
                                                {{ $chequeValue->chequeable->invoice->ref }}
                                            </a>
                                        </td>
                                        <td>
                                            <a target="_blank" href="/sales/customer/{{ $chequeValue->chequeable->invoice->customer->id }}">
                                                {{ $chequeValue->chequeable->invoice->customer->display_name }}
                                            </a>
                                        </td>
                                        <td>{{ $chequeValue->chequeable->payment_date }}</td>
                                        <td class="text-right">{{ number_format($chequeValue->chequeable->payment, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row m-t-20">
                        <div class="col-md-12">
                            <h5>Collected Payments</h5>
                            <table class="ui celled structured table collapse-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Payment details</th>
                                        <th>Recorded by</th>
                                        <th>Status</th>
                                        <th class="text-right">Amount</th>
                                        <th>Action</th>
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
                                            <td>
                                                @if($payment->status == 'Paid')
                                                    <a href="" class="btn btn-danger btn-sm cancel-payment" data-id="{{ $payment->id }}" data-value="{{ $chequeKey }}">
                                                        <i class="fa fa-remove"></i> Cancel
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="5" style="text-align: right;">
                                                <b>Settled amount</b>
                                            </td>
                                            <td style="text-align: right;"><b>{{ number_format($settledAmount, 2) }}</b></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="text-align: right;">
                                                <b>Balance</b>
                                            </td>
                                            <td style="text-align: right;"><b>{{ number_format($balance, 2) }}</b></td>
                                            <td></td>
                                        </tr>
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
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('.cancel-payment').click(function (e) {
            var $id = $(this).data('id');
            var $chequeNo = $(this).data('value');
            var sendUrl = '{{ route('finance.return.cheques.cancel.payment', ['cheque' => 'CHEQUE', 'payment' => 'ID']) }}';
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#fc4b6c',
                confirmButtonText: 'Submit'
            }).then(function (isConfirm) {
                if (isConfirm.value) {
                    $.ajax({
                        url: sendUrl.replace('ID', $id).replace('CHEQUE', $chequeNo),
                        type: 'POST',
                        data: {'_token': '{{ csrf_token() }}'},
                        success: function (result) {
                            swal(
                                'Status Changed!',
                                'Payment canceled successfully!',
                                'success'
                            ).then(function (confirm) {
                                if (confirm) {
                                    window.location.reload()
                                }
                            });
                        }
                    });
                }
            });
        })
    </script>
@endsection
