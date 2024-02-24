@extends('layouts.master')
@section('title', 'Transfer Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">
                        {{ $transfer->type }} Transfer
                        @if($transfer->transfer_mode == 'ByHand')
                            By Hand
                        @else
                            By Bank Deposit
                        @endif
                    </h4>
                </div>
                <div class="card-body">

                    {{--@if(isDirectorLevelStaff() || isAccountLevelStaff())
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                @if($transfer->status == 'Pending')
                                <button data-id="{{ $transfer->id }}" class="btn waves-effect waves-light btn-info btn-sm approve-transfer">
                                    <i class="ti-check"></i> Approve
                                </button>
                                <button data-id="{{ $transfer->id }}" class="btn waves-effect waves-light btn-danger btn-sm decline-transfer">
                                    <i class="ti-close"></i> Decline
                                </button>
                                @endif
                            </div>
                            <div class="pull-right"></div>
                        </div>
                    </div>
                    @endif--}}

                    <div class="row">
                        <div class="col-md-9">
                            <div class="card card-body printableArea">
                                <h3>
                                    <b>
                                        {{ carbon($transfer->date)->format('F j, Y') }}
                                    </b> |
                                    <small class="{{ statusLabelColor($transfer->status) }}">
                                        {{ $transfer->status }}
                                    </small>
                                    <span class="pull-right">
                                        {{ number_format($transfer->amount, 2) }}
                                    </span>
                                </h3>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><b>Sender: </b>
                                            {{ $transfer->senderCompany->name }}
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><b>Receiver: </b>
                                            {{ $transfer->receiverCompany->name }}
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><b>Transferred by:</b>
                                            {{ $transfer->transferBy->name }}
                                        </p>
                                    </div>
                                </div>
                                @if($transfer->transfer_mode == 'ByHand')
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><b>Handed over date: </b>
                                            {{ carbon($transfer->handed_over_date)->format('F j, Y') }}
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><b>Handed over time: </b>
                                            {{ carbon($transfer->handed_over_time)->format('h:i:s A')}}
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><b>Handed over to:</b>
                                            {{ $transfer->handedOrderTo->full_name }}
                                        </p>
                                    </div>
                                </div>
                                @endif

                                @if($transfer->transfer_mode == 'DepositedToBank')
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><b>Deposited date: </b>
                                            {{ carbon($transfer->deposited_date)->format('F j, Y') }}
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><b>Deposited time: </b>
                                            {{ carbon($transfer->deposited_time)->format('h:i:s A')}}
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><b>Deposited to:</b>
                                            {{ $transfer->depositedTo->name }}
                                        </p>
                                    </div>
                                </div>
                                @endif

                                @if($transfer->status == 'Received')
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><b>Received amount: </b>
                                            <code style="font-size: 16px;"><b>{{ number_format($transfer->received_amount, 2) }}</b></code>
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><b>Received by: </b>
                                            {{ $transfer->receivedBy->name }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><b>Received on: </b>
                                            {{ carbon($transfer->received_on)->format('F j, Y h:i:s A') }}
                                        </p>
                                    </div>
                                </div>
                                @endif
                                <h4 class="box-title box-title-with-margin">Transaction Details <span class="text-warning">(Before Approval)</span></h4>
                                <hr>
                                <div class="table-responsive">
                                    <table class="table color-table inverse-table">
                                        <thead>
                                            <tr>
                                                <th width="70%">Account</th>
                                                <th width="15%" class="text-right">Debit</th>
                                                <th width="15%" class="text-right">Credit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $transfer->debitedTo->name }}</td>
                                                <td class="text-right">{{ number_format($transfer->amount, 2) }}</td>
                                                <td class="text-right">0.00</td>
                                            </tr>
                                            <tr>
                                                <td>{{ $transfer->creditedTo->name }}</td>
                                                <td class="text-right">0.00</td>
                                                <td class="text-right">{{ number_format($transfer->amount, 2) }}</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="text-right"><h6><b>TOTAL</b></h6></td>
                                                <td class="text-right"><h6><b>{{ number_format($transfer->amount, 2) }}</b></h6></td>
                                                <td class="text-right"><h6><b>{{ number_format($transfer->amount, 2) }}</b></h6></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                @if($transfer->status == 'Received')
                                <h4 class="box-title box-title-with-margin">Transaction Details <span class="text-green">(After Approval)</span></h4>
                                <hr>
                                <div class="table-responsive">
                                    <table class="table color-table inverse-table">
                                        <thead>
                                        <tr>
                                            <th width="70%">Account</th>
                                            <th width="15%" class="text-right">Debit</th>
                                            <th width="15%" class="text-right">Credit</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>{{ $transfer->debitedTo->name }}</td>
                                            <td class="text-right">{{ number_format($transfer->received_amount, 2) }}</td>
                                            <td class="text-right">0.00</td>
                                        </tr>
                                        <tr>
                                            <td>{{ $transfer->creditedTo->name }}</td>
                                            <td class="text-right">0.00</td>
                                            <td class="text-right">{{ number_format($transfer->received_amount, 2) }}</td>
                                        </tr>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td class="text-right"><h6><b>TOTAL</b></h6></td>
                                            <td class="text-right"><h6><b>{{ number_format($transfer->received_amount, 2) }}</b></h6></td>
                                            <td class="text-right"><h6><b>{{ number_format($transfer->received_amount, 2) }}</b></h6></td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                @endif

                                @if($transfer->transaction)
                                <h4>Narration</h4>
                                <small class="text-muted">{{ $transfer->transaction ? $transfer->transaction->auto_narration : '' }}</small>
                                @endif

                                {!! form()->model($transfer, ['url' => route('finance.transfer.status.update', $transfer), 'method' => 'PATCH']) !!}

                                @if($transfer->type == 'Cheque')
                                <h4 class="box-title box-title-with-margin">List of cheques</h4>
                                <hr>
                                <div class="table-responsive">
                                    <table class="table color-table inverse-table">
                                        <thead>
                                        <tr>
                                            @if($transfer->status == 'Pending')
                                                @if(isDirectorLevelStaff() || isAccountLevelStaff())
                                                    <th style="width: 2%;">#</th>
                                                @endif
                                            @endif
                                            <th>Cheque no</th>
                                            <th>Cheque date</th>
                                            <th>Written bank</th>
                                            <th>Cheque type</th>
                                            <th>Status</th>
                                            <th width="15%" class="text-right">Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @if($transfer->items)
                                                @foreach($transfer->items as $itemKey => $item)
                                                    <tr>
                                                        @if($transfer->status == 'Pending')
                                                            @if(isDirectorLevelStaff() || isAccountLevelStaff())
                                                                <td style="width: 2%;">
                                                                    <div class="demo-checkbox">
                                                                        <input type="checkbox" id="{{ 'md_checkbox_29_' . $item->id }}"
                                                                               name="cheques[]"
                                                                               class="chk-col-cyan" value="{{ (float)$item->id }}" data-value="{{ $item->amount }}">
                                                                        <label for="{{ 'md_checkbox_29_' . $item->id }}"></label>
                                                                    </div>
                                                                </td>
                                                            @endif
                                                        @endif
                                                        <td>{{ $item->cheque_no }}</td>
                                                        <td>{{ $item->cheque_date }}</td>
                                                        <td>{{ $item->bank->name }}</td>
                                                        <td>{{ $item->cheque_type }}</td>
                                                        <td>
                                                            <span class="{{ statusLabelColor($item->status) }}">
                                                                {{ $item->status }}
                                                            </span>
                                                        </td>
                                                        <td class="text-right">{{ number_format($item->amount, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td class="text-right" colspan="{{ $transfer->status == 'Pending' ? 6 : 5 }}"><h6><b>TOTAL</b></h6></td>
                                            <td class="text-right"><h6><b>{{ number_format($transfer->amount, 2) }}</b></h6></td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                @endif

                                @if(isDirectorLevelStaff() || isAccountLevelStaff())
                                    @if($transfer->status == 'Pending')
                                    <table class="table color-table inverse-table m-t-40">
                                        <tr>
                                            <td class="text-right" style="vertical-align: middle;"><h6><b>RECEIVED AMOUNT</b></h6></td>
                                            <td class="text-right" width="250">
                                                <div class="form-group required m-t-15 {{ $errors->has('received_amount') ? 'has-danger' : '' }}">
                                                    <input type="text" class="form-control received-amount" placeholder="" name="received_amount" {{ $errors->has('received_amount') ? 'error' : '' }}>
                                                    <p class="form-control-feedback">{{ $errors->first('received_amount') }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    <hr>
                                    <div class="clearfix">
                                        <div class="pull-left">
                                            <button type="submit" name="approval" value="Declined" class="btn waves-effect waves-light btn-danger">
                                                <i class="ti-close"></i> Decline
                                            </button>
                                        </div>
                                        <div class="pull-right">
                                            <button type="submit" name="approval" value="Approved" class="btn waves-effect waves-light btn-info">
                                                <i class="ti-check"></i> Approve
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                @endif
                                {{ form()->close() }}

                                @if($transfer->status == 'Drafted' && $transfer->transfer_mode == 'DepositedToBank')
                                <!-- upload posited receipt -->
                                {!! form()->model($transfer, ['url' => route('finance.transfer.upload.receipt', $transfer), 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                                <div class="row m-t-15">
                                    <div class="col-md-12">
                                        <div class="form-group required" {{ $errors->has('deposited_receipt') ? 'has-danger' : '' }}>
                                            <label class="control-label text-danger">Please upload the deposited slip to send this tranfer for approval</label>
                                            <input type="file" class="form-control" id="depositedReceipt" name="deposited_receipt" {{ $errors->has('deposited_receipt') ? 'error' : '' }}>
                                            <p class="form-control-feedback">{{ $errors->first('deposited_receipt') }}</p>
                                        </div>
                                        <hr>
                                        <div class="clearfix">
                                            <div class="pull-left"></div>
                                            <div class="pull-right">
                                                <button type="submit" name="upload-receipt" value="Upload" class="btn waves-effect waves-light btn-info">
                                                    <i class="ti-upload"></i> Upload
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{ form()->close() }}
                                @endif
                            </div>

                            @if($transfer->deposited_receipt)
                            <div class="card">
                                <div class="card-body">
                                    <div class="clearfix">
                                        <div class="pull-left">
                                            <h5 class="text-primary"><b>Deposited Slip</b></h5>
                                            <small class="text-muted">
                                                <b>Uploaded by:</b> {{ $transfer->receiptUploadedBy->name ?? '' }}, <b>On: </b> {{ carbon($transfer->receipt_uploaded_on)->format('F j, Y h:i:s A') }}
                                            </small>
                                        </div>
                                        <div class="pull-right"></div>
                                    </div>
                                    <hr>
                                    <img src="{{ route('finance.transfer.deposited.receipt', $transfer) }}" width="100%">
                                </div>
                            </div>
                            @endif

                        </div>

                        <div class="col-md-3">
                            <!-- recent comments -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.comment.index', ['model' => $transfer])
                                </div>
                            </div>
                            <!-- recent audit logs -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.log.index', ['model' => $transfer, 'modelName' => 'Transfer'])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('general.comment.script', ['modelId' => $transfer->id])
    @include('_inc.document.script', ['model' => $transfer])
    <script>
        $('.approve-transfer').click(function (e) {
            var $id = $(this).data('id');
            var approveUrl = '{{ route('finance.transfer.approve', ['transfer' => 'ID']) }}';
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2c9404',
                confirmButtonText: 'Yes, Approve'
            }).then(function (isConfirm) {
                if (isConfirm.value) {
                    $.ajax({
                        url: approveUrl.replace('ID', $id),
                        type: 'POST',
                        data: {'_token': '{{ csrf_token() }}'},
                        success: function (result) {
                            swal(
                                'Status Changed!',
                                'Transfer status updated successfully!',
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
        });

        $('.decline-transfer').click(function (e) {
            var $id = $(this).data('id');
            var approveUrl = '{{ route('finance.transfer.decline', ['transfer' => 'ID']) }}';
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#b5000b',
                confirmButtonText: 'Yes, Decline'
            }).then(function (isConfirm) {
                if (isConfirm.value) {
                    $.ajax({
                        url: approveUrl.replace('ID', $id),
                        type: 'POST',
                        data: {'_token': '{{ csrf_token() }}'},
                        success: function (result) {
                            swal(
                                'Status Changed!',
                                'Transfer status updated successfully!',
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
        });

        var transferType = '{{ $transfer->type }}';
        var chkCheque = $('.chk-col-cyan');

        var receivedAmount = $('.received-amount');
        if(transferType == 'Cheque'){
            receivedAmount.attr('readonly', true);
        }

        chkCheque.change(function (e) {
            e.preventDefault();
            if($(this).is(":checked")) {
                var addedTransAmount = receivedAmount.val();
                var addedChequeAmount = $(this).data('value');
                var addedAmount = Number(addedTransAmount) + Number(addedChequeAmount);
                receivedAmount.val(addedAmount)
            }else{
                var deductedTransAmount = receivedAmount.val();
                var deductedChequeAmount = $(this).data('value');
                var deductedAmount = Number(deductedTransAmount) - Number(deductedChequeAmount);
                receivedAmount.val(deductedAmount)
            }
        });

    </script>
@endsection
