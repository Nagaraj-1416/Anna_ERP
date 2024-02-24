@extends('layouts.master')
@section('title', 'GRN Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Purchase') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">{{ $grn->code }}</h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                @if($grn->status == 'Sent')
                                <a href="{{ route('purchase.grn.receive', $grn)  }}"
                                   class="btn waves-effect waves-light btn-primary btn-sm"
                                   target="_blank">
                                    <i class="ti-check"></i> Receive GRN
                                </a>
                                @endif
                            </div>
                            <div class="pull-right" style="display: none;">
                                <a href="#"
                                   class="btn waves-effect waves-light btn-inverse btn-sm"
                                   target="_blank">
                                    <i class="fa fa-file-pdf-o"></i> Export to PDF
                                </a>
                                <a href="#"
                                   class="btn waves-effect waves-light btn-inverse btn-sm"
                                   target="_blank">
                                    <i class="fa fa-print"></i> Print
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- po summary and history -->
                    <div class="row custom-top-margin">
                        <div class="col-md-9">
                            <div class="card card-body printableArea">
                                <h3>
                                    <b>Goods Receipt Note</b> |
                                    <small class="{{ statusLabelColor($grn->status) }}">
                                        {{ $grn->status }}
                                    </small>
                                    <span class="pull-right">#{{ $grn->code }}</span></h3>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-left">
                                            <address>
                                                <h4><b class="text-danger">{{ $company->name }}</b></h4>
                                                @include('_inc.address.view', ['address' => $companyAddress])
                                            </address>
                                        </div>
                                        <div class="pull-right text-right">
                                            <address>
                                                <h4 class="font-bold">{{ $supplier->display_name }}</h4>
                                                @if($address)
                                                    @include('_inc.address.view', ['address' => $address])
                                                @endif
                                            </address>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="pull-left">
                                            <p><b>Date :</b> {{ $grn->date }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="table-responsive" style="clear: both;">
                                            <table class="table color-table inverse-table">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th >Items & Description</th>
                                                    <th class="text-center" style="width: 10%;">Requested</th>
                                                    <th class="text-center" style="width: 8%;">Issued</th>
                                                    <th class="text-center" style="width: 8%;">Pending</th>
                                                    <th class="text-center" style="width: 8%;">Received</th>
                                                    <th class="text-center" style="width: 8%;">Rejected</th>
                                                    <th class="text-right" style="width: 8%;">Rate</th>
                                                    <th class="text-right" style="width: 10%;">Total</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(count($items))
                                                    @foreach($items as $itemKey => $item)
                                                        <tr>
                                                            <td class="text-center">{{ $itemKey+1 }}</td>
                                                            <td>
                                                                {{ $item->product->name }}<br />
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <small>
                                                                            @if($item->batch_no)
                                                                            <b>Batch no:</b> {{ $item->batch_no or 'None' }}<br />
                                                                            @endif
                                                                            @if($item->manufacture_date)
                                                                            <b>Manufacture:</b> {{ $item->manufacture_date or 'None' }}<br />
                                                                            @endif
                                                                            @if($item->expiry_date)
                                                                            <b>Expiry date:</b> {{ $item->expiry_date or 'None' }}<br />
                                                                            @endif
                                                                            @if($item->packing_type)
                                                                            <b>Packing type:</b> {{ $item->packing_type or 'None' }}
                                                                            @endif
                                                                        </small>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <small>
                                                                            @if($item->grade)
                                                                            <b>Grade:</b> {{ $item->grade or 'None' }}<br />
                                                                            @endif
                                                                            @if($item->color)
                                                                            <b>Color:</b> {{ $item->color or 'None' }}<br />
                                                                            @endif
                                                                            @if($item->brand)
                                                                            <b>Brand:</b> {{ $item->brand or 'None' }}<br />
                                                                            @endif
                                                                            @if($item->no_of_bags)
                                                                                <b>No of bags:</b> {{ $item->no_of_bags or 'None' }}
                                                                            @endif
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">{{ $item->quantity }}</td>
                                                            <td class="text-center">{{ $item->issued_qty }}</td>
                                                            <td class="text-center">{{ $item->pending_qty }}</td>
                                                            <td class="text-center">{{ $item->received_qty }}</td>
                                                            <td class="text-center">{{ $item->rejected_qty }}</td>
                                                            <td class="text-right">{{ number_format($item->rate, 2) }}</td>
                                                            <td class="text-right">
                                                                @if($item->status == 'Received')
                                                                    {{ number_format($item->received_amount, 2) }}
                                                                @else
                                                                    {{ number_format($item->amount, 2) }}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="table-responsive" style="clear: both;">
                                            <table class="table">
                                                <tbody>
                                                <tr>
                                                    <td width="80%" class="text-right"><h3><b>Total</b></h3></td>
                                                    <td class="text-right custom-td-btm-border ">
                                                        <h3>
                                                            <b>
                                                                @if($grn->status == 'Received')
                                                                    {{ number_format($grn->items()->sum('received_amount'), 2) }}
                                                                @else
                                                                    {{ number_format($grn->items()->sum('amount'), 2) }}
                                                                @endif
                                                            </b>
                                                        </h3>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="box-title box-title-with-margin">Other Details</h4>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><b>Supplier :</b>
                                            <a target="_blank"
                                               href="{{ route('purchase.supplier.show', [$grn->supplier]) }}">
                                                {{ $grn->supplier->display_name or 'None' }}
                                            </a>
                                        </p>
                                        <p><b>Company :</b> {{ $grn->company->name or 'None' }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><b>Received by :</b> {{ $grn->receivedBy->name or 'None' }}</p>
                                        <p><b>Prepared by :</b> {{ $grn->preparedBy->name or 'None'}}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="order-notes">
                                            <h5>Notes</h5>
                                            <small class="text-muted">{{ $grn->notes }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- bills -->
                            @include('purchases.general.bills', ['bills' => $bills])

                            <!-- payment made -->
                            @include('purchases.general.payment.index', ['payments' => $payments])

                            @include('purchases.general.grn-trans', ['trans' => $grn->transactions])

                            <div class="card">
                                <div class="card-body">
                                    <h3><b>ATTACHMENTS</b> <span
                                                class="pull-right">Total: {{ count($grn->documents) }}</span></h3>
                                    <hr>
                                    @include('_inc.document.view', ['model' => $grn])
                                </div>
                            </div>
                        </div>

                        <!-- recent logs -->
                        <div class="col-md-3">
                            <!-- approve order -->
                            @if($grn->status == 'Drafted')
                                <div class="card border-warning text-center grn-approval-panel">
                                    <div class="card-body">
                                        <h3 class="card-title text-danger"><i class="fa fa-clock-o"></i> Approval
                                            Pending</h3>
                                        <p class="card-subtitle"> This is GRN is waiting for approval. You can
                                            take
                                            further actions once you approve it.</p>
                                        <a class="btn btn-danger approve-grn" href="" data-id="{{ $grn->id }}">
                                            <i class="fa fa-check"></i> Approve GRN
                                        </a>
                                    </div>
                                </div>
                            @endif

                            <!-- recent comments -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.comment.index', ['model' => $grn])
                                </div>
                            </div>

                            <!-- recent audit logs -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.log.index', ['model' => $grn, 'modelName' => 'Order'])
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
    @include('general.comment.script', ['modelId' => $grn->id])
    @include('_inc.document.script', ['model' => $grn])
    <script>
        /** approve order */
        $('.grn-approval-panel').on('click', '.approve-grn', function () {
            var id = $(this).data('id');
            var approvalUrl = '{{ route('purchase.grn.approve', [ 'grn'=>'ID']) }}';
            approvalUrl = approvalUrl.replace('ID', id);
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action! Are you sure want to approve this GRN?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4aba45',
                confirmButtonText: 'Yes, Approve!'
            }).then(function (isConfirm) {
                if (isConfirm.value) {
                    $.ajax({
                        url: approvalUrl,
                        type: 'POST',
                        data: {'_token': '{{ csrf_token() }}'},
                        success: function (result) {
                            swal(
                                'GRN Approval!',
                                'Goods receipt note approved successfully!',
                                'success'
                            );
                            setTimeout(location.reload(), 300);
                        }
                    });
                }
            });
        });
    </script>
@endsection