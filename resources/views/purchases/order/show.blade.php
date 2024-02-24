@extends('layouts.master')
@section('title', 'Order Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Purchase') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">{{ $order->po_no }}</h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                @if($order->status == 'Pending')
                                    @can('edit', $order)
                                        <a href="{{ route('purchase.order.edit', [$order]) }}"
                                           class="btn waves-effect waves-light btn-primary btn-sm"
                                           target="_blank">
                                            <i class="fa fa-pencil"></i> Edit
                                        </a>
                                    @endcan
                                    @can('edit', $order)
                                        <button id="cancelOrder" class="btn waves-effect waves-light btn-danger btn-sm">
                                            <i class="fa fa-ban"></i> Cancel Order
                                        </button>
                                    @endcan
                                @endif
                                @can('create', new \App\Grn())
                                    @if($order->status == 'Sent' && $order->grn_received == 'No')
                                        <a href="{{ route('purchase.grn.create') }}?order={{ $order->id }}"
                                           class="btn waves-effect waves-light btn-info btn-sm"
                                           target="_blank">
                                            <i class="fa fa-plus"></i> Create GRN
                                        </a>
                                    @endif
                                @endcan
                            </div>
                            <div class="pull-right">
                                {{--@can('export', $order)
                                    <a href="{{ route('purchase.order.export', [$order]) }}"
                                       class="btn waves-effect waves-light btn-inverse btn-sm"
                                       target="_blank">
                                        <i class="fa fa-file-pdf-o"></i> Export to PDF
                                    </a>
                                @endcan--}}
                                @can('print', $order)
                                    <a href="{{ route('purchase.order.print', [$order]) }}"
                                       class="btn waves-effect waves-light btn-inverse btn-sm"
                                       target="_blank">
                                        <i class="fa fa-print"></i> Print
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>

                    <!-- load create bill form -->
                    @include('purchases.order.bill.create', ['model' => $order])

                    @include('purchases.general.cancel.create', ['model' => $order, 'route' => route('purchase.order.cancel', [$order]), 'varName' => 'order'])

                    <!-- po summary and history -->
                    <div class="row custom-top-margin">
                        <div class="col-md-9">
                            <div class="card card-body printableArea">
                                <h3>
                                    <b>PURCHASE ORDER</b> |
                                    <small class="{{ statusLabelColor($order->status) }}">
                                        {{ $order->status }}
                                    </small>
                                    <span class="pull-right">#{{ $order->po_no }}</span></h3>
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
                                                <h4 class="font-bold">{{ $supplier->display_name or '' }}</h4>
                                                @if($address)
                                                    @include('_inc.address.view', ['address' => $address])
                                                @endif
                                            </address>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="pull-left">
                                            <p><b>Order Date :</b> {{ $order->order_date }}</p>
                                        </div>
                                        <div class="pull-right text-right">
                                            <p><b>Purchase mode :</b> {{ $order->po_mode }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="table-responsive" style="clear: both;">
                                            <table class="table color-table inverse-table">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" style="width: 3%;">#</th>
                                                    <th>Items & Description</th>
                                                    <th class="text-center" style="width: 25%;">Quantity</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(count($items))
                                                    @foreach($items as $itemKey => $item)
                                                        <tr>
                                                            <td class="text-center">{{ $itemKey+1 }}</td>
                                                            <td>
                                                                {{ $item->name }}
                                                            </td>
                                                            <td class="text-center">{{ $item->pivot->quantity }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <br /><br />
                                <h4 class="box-title box-title-with-margin">Other Details</h4>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><b>Supplier :</b>
                                            <a target="_blank"
                                               href="{{ route('purchase.supplier.show', [$order->supplier]) }}">
                                                {{ $order->supplier->display_name or 'None' }}
                                            </a>
                                        </p>
                                        <p><b>Order type :</b> {{ $order->po_type }}</p>
                                        <p><b>Company :</b> {{ $order->company->name or 'None' }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><b>Approved by :</b> {{ $order->approvedBy->name or 'None' }}</p>
                                        <p><b>Prepared by :</b> {{ $order->preparedBy->name or 'None'}}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="order-notes">
                                            <h5>Notes</h5>
                                            <small class="text-muted">{{ $order->notes }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- bills -->
                            @include('purchases.general.grns', ['grns' => $grns])

                            <!-- bills -->
                            @include('purchases.general.bills', ['bills' => $bills])

                            <!-- payment made -->
                            @include('purchases.general.payment.index', ['payments' => $payments])

                            <div class="card">
                                <div class="card-body">
                                    <h3><b>ATTACHMENTS</b> <span
                                                class="pull-right">Total: {{ count($order->documents) }}</span></h3>
                                    <hr>
                                    @include('_inc.document.view', ['model' => $order])
                                </div>
                            </div>
                        </div>

                        <!-- recent logs -->
                        <div class="col-md-3">
                            <!-- approve order -->
                            @can('approve', $order)
                                @if($order->status == 'Pending')
                                    <div class="card border-warning text-center po-approval-panel">
                                        <div class="card-body">
                                            <h3 class="card-title text-danger"><i class="fa fa-clock-o"></i> Approval
                                                Pending</h3>
                                            <p class="card-subtitle"> This is order is waiting for approval. You can
                                                take
                                                further actions once you approve it.</p>
                                            <a class="btn btn-danger approve-po" href="" data-id="{{ $order->id }}">
                                                <i class="fa fa-check"></i> Approve Order
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endcan

                            <!-- recent comments -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.comment.index', ['model' => $order])
                                </div>
                            </div>

                            <!-- recent audit logs -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.log.index', ['model' => $order, 'modelName' => 'Order'])
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
    @include('general.comment.script', ['modelId' => $order->id])
    @include('_inc.document.script', ['model' => $order])
    @include('purchases.order.bill.script', ['modal' => $order])
    @include('purchases.general.cancel.script', ['modal' => $order, 'btnName' => 'cancelOrder',  'varName' => 'order'])
    <script>
        /** approve order */
        $('.po-approval-panel').on('click', '.approve-po', function () {
            var id = $(this).data('id');
            var approvalUrl = '{{ route('purchase.order.approve', [ 'order'=>'ID']) }}';
            approvalUrl = approvalUrl.replace('ID', id);
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action! Are you sure want to approve this Order?",
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
                                'Order Approval!',
                                'Purchase order approved successfully!',
                                'success'
                            );
                            setTimeout(location.reload(), 300);
                        }
                    });
                }
            });
        });

        /** convert order to open */
        $('.po-convert-panel').on('click', '.convert-po', function () {
            var id = $(this).data('id');
            var approvalUrl = '{{ route('purchase.order.convert', [ 'order'=>'ID']) }}';
            approvalUrl = approvalUrl.replace('ID', id);
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action! Are you sure want to covert this Order open?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4aba45',
                confirmButtonText: 'Yes, Covert!'
            }).then(function (isConfirm) {
                if (isConfirm.value) {
                    $.ajax({
                        url: approvalUrl,
                        type: 'POST',
                        data: {'_token': '{{ csrf_token() }}'},
                        success: function (result) {
                            swal(
                                'Covert Order!',
                                'Purchase order converted successfully!',
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