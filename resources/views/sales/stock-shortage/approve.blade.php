@extends('layouts.master')
@section('title', 'Stock Shortage Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Approve an item</h4>
                </div>
                <div class="card-body">
                    {!! form()->model($stock, ['url' => route('sales.stock.shortage.do.approve', [$stock, $stockItem]), 'method' => 'PATCH']) !!}
                    <div class="row custom-top-margin">
                        <div class="col-md-9">
                            <div class="card card-body printableArea">
                                <h3>
                                    <b>SHORTAGE STOCK</b>
                                </h3>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-left">
                                            <p><b>Shortage Date :</b> {{ $stock->date }}</p>
                                        </div>
                                        <div class="pull-right text-right">
                                            <p><b>Rep :</b> {{ $stock->rep->name }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="table-responsive" style="clear: both;">
                                            <table class="table color-table inverse-table">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th style="width: 50%;">Items & Description</th>
                                                    <th class="text-center" style="width: 10%;">Quantity</th>
                                                    <th class="text-right">Rate</th>
                                                    <th class="text-right">Amount</th>
                                                    <th class="text-center">Status</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">{{ 1 }}</td>
                                                        <td>
                                                            {{ $stockItem->product->name }}
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="form-group required {{ $errors->has('item_qty') ? 'has-danger' : '' }}">
                                                                <input type="text" name="item_qty" value="{{ $stockItem->qty }}" class="form-control text-center">
                                                                <p class="form-control-feedback">{{ $errors->first('item_qty') }}</p>
                                                            </div>
                                                        </td>
                                                        <td class="text-right">{{ number_format($stockItem->rate, 2) }}</td>
                                                        <td class="text-right">{{ number_format($stockItem->amount, 2) }}</td>
                                                        <td class="text-center {{ statusLabelColor($stockItem->status) }}">
                                                            {{ $stockItem->status }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="box-title box-title-with-margin">Transaction Details</h4>
                                <hr>
                                @if($stockItem->status == 'Drafted')
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
                                            <td>{{ $debitAccount->name }}</td>
                                            <td class="text-right">{{ number_format($stockItem->amount, 2) }}</td>
                                            <td class="text-right">0.00</td>
                                        </tr>
                                        <tr>
                                            <td>{{ $creditAccount->name }}</td>
                                            <td class="text-right">0.00</td>
                                            <td class="text-right">{{ number_format($stockItem->amount, 2) }}</td>
                                        </tr>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td class="text-right"><h6><b>TOTAL</b></h6></td>
                                            <td class="text-right"><h6><b>{{ number_format($stockItem->amount, 2) }}</b></h6></td>
                                            <td class="text-right"><h6><b>{{ number_format($stockItem->amount, 2) }}</b></h6></td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                @endif

                                <h4 class="box-title box-title-with-margin">Other Details</h4>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><b>Prepared by :</b> {{ $stock->preparedBy->name or 'None' }}</p>
                                        <p><b>Prepared on :</b> {{ $stock->prepared_on or 'None' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="order-notes">
                                            <h5>Notes</h5>
                                            <small class="text-muted">{{ $stock->notes }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><b>Route :</b> {{ $stock->route->name or 'None' }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><b>Allocation :</b>
                                            <a target="_blank" href="{{ route('sales.allocation.show', $stock->dailySale) }}">
                                                {{ $stock->dailySale->code or 'None' }}
                                            </a>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><b>Company :</b> {{ $stock->company->name or 'None' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <!-- Approval Pending -->
                            @if($stockItem->status == 'Drafted')
                                <div class="card border-warning text-center stock-approval-panel">
                                    <div class="card-body">
                                        <h3 class="card-title text-danger"><i class="fa fa-clock-o"></i> Approval
                                            Pending</h3>
                                        <p class="card-subtitle text-center">
                                            The shortage stock items is waiting for your approval. <br />
                                            <code>Please note that only approved stocks list will generate the related account transaction.</code>
                                        </p>
                                        <button type="submit" class="btn btn-success">Approve!</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    {{ form()->close() }}
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    @include('general.comment.script', ['modelId' => $stock->id])
    @include('_inc.document.script', ['model' => $stock])
    <script>
        $(document).ready(function () {
            var $approveBtn = $('.approve-stock');
            var $rejectBtn = $('.reject-stock');

            var approveRoute = '';
            var rejectRoute = '';
            var showRoute = '{{ route('sales.stock.excess.show', $stock) }}';

            $approveBtn.click(function (e) {
                e.preventDefault();
                Swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this action! Are you sure want to approve the excess stock list?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor : '#087a15',
                    confirmButtonText: 'Yes, Approve!',
                    cancelButtonText: 'Close'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: approveRoute,
                            type: 'POST',
                            data: {_token : '{{ csrf_token() }}'},
                            success: function(response) {
                                if (response.success){
                                    Swal(
                                        'Mark as Approved!',
                                        'This excess stock list is approved successfully!',
                                        'success'
                                    );
                                    setTimeout(function () {
                                        window.location.href = showRoute;
                                    }, 2000);
                                }else{
                                    Swal(
                                        'Failed!',
                                        'Your request is failed.',
                                        'error'
                                    )
                                }
                            }
                        });
                    }
                })
            });

            $rejectBtn.click(function (e) {
                e.preventDefault();
                Swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this action! Are you sure want to reject the excess stock list?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor : '#cb0d21',
                    confirmButtonText: 'Yes, Reject!',
                    cancelButtonText: 'Close'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: rejectRoute,
                            type: 'POST',
                            data: {_token : '{{ csrf_token() }}'},
                            success: function(response) {
                                if (response.success){
                                    Swal(
                                        'Mark as Rejected!',
                                        'This excess stock list is rejected successfully!',
                                        'success'
                                    );
                                    setTimeout(function () {
                                        window.location.href = showRoute;
                                    }, 2000);
                                }else{
                                    Swal(
                                        'Failed!',
                                        'Your request is failed.',
                                        'error'
                                    )
                                }
                            }
                        });
                    }
                })
            });
        });
    </script>
@endsection