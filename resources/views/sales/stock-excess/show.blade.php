@extends('layouts.master')
@section('title', 'Stock Excess Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Stock Excess Details</h4>
                </div>
                <div class="card-body">
                    <div class="clearfix">
                        <div class="pull-left">
                            <a href="{{ route('sales.stock.excess.export', [$stock]) }}"
                               class="btn waves-effect waves-light btn-inverse btn-sm">
                                <i class="fa fa-file-pdf-o"></i> Export to PDF
                            </a>
                        </div>
                        <div class="pull-right"></div>
                    </div>
                    <!-- estimate summary and history -->
                    <div class="row custom-top-margin">
                        <div class="col-md-9">
                            <div class="card card-body printableArea">
                                <h3>
                                    <b>EXCESS STOCK</b>
                                </h3>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-left">
                                            <p><b>Excess Date :</b> {{ $stock->date }}</p>
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
                                                    <th class="text-center">Quantity</th>
                                                    <th class="text-right">Rate</th>
                                                    <th class="text-right">Amount</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(count($items))
                                                    @foreach($items as $itemKey => $item)
                                                        <tr>
                                                            <td class="text-center">{{ $itemKey+1 }}</td>
                                                            <td>
                                                                {{ $item->product->name }}
                                                            </td>
                                                            <td class="text-center">{{ $item->qty }}</td>
                                                            <td class="text-right">{{ number_format($item->rate, 2) }}</td>
                                                            <td class="text-right">{{ number_format($item->amount, 2) }}</td>
                                                            <td class="text-center {{ statusLabelColor($item->status) }}">
                                                                {{ $item->status }}
                                                            </td>
                                                            <td class="text-center">
                                                                @if($item->status == 'Drafted')
                                                                <a target="_blank" class="btn btn-success btn-xs" href="{{ route('sales.stock.excess.approve', [$stock, $item]) }}">
                                                                    <i class="fa fa-check"></i> Approve
                                                                </a>
                                                                <a target="_blank" class="btn btn-danger btn-xs" href="{{ route('sales.stock.excess.reject', [$stock, $item]) }}">
                                                                    <i class="fa fa-remove"></i> Reject
                                                                </a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="4" class="text-right"><h5><b>Total</b></h5></td>
                                                        <td class="text-right custom-td-btm-border">
                                                            <h5><b>{{ number_format($stock->amount, 2) }}</b></h5>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                @else
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="box-title box-title-with-margin">Transactions</h4>
                                <hr>
                                @if(count($items))
                                    <table class="table color-table inverse-table">
                                        <thead>
                                        <tr>
                                            <th>Transaction#</th>
                                            <th>Narration</th>
                                            <th class="text-right">Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($items as $itemKey => $item)
                                            @if($item->transaction)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('finance.trans.show', $item->transaction) }}">
                                                            {{ $item->transaction->code }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $item->transaction->auto_narration }}</td>
                                                    <td class="text-right">
                                                        <a target="_blank" href="{{ route('finance.trans.show', $item->transaction) }}">
                                                            {{ number_format($item->transaction->amount, 2) }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endif

                                <h4 class="box-title box-title-with-margin">Other Details</h4>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><b>Prepared by :</b> {{ $stock->preparedBy->name or 'None' }}</p>
                                        <p><b>Prepared on :</b> {{ $stock->prepared_on or 'None' }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><b>Approved by :</b> {{ $stock->approvedBy->name or 'None' }}</p>
                                        <p><b>Approved on :</b> {{ $stock->approved_on or 'None'}}</p>
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

                        <!-- recent logs -->
                        <div class="col-md-3">
                            <!-- recent comments -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.comment.index', ['model' => $stock])
                                </div>
                            </div>

                            <!-- recent audit logs -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.log.index', ['model' => $stock, 'modelName' => 'Excess Stock'])
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