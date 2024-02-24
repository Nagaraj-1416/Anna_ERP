@extends('layouts.master')
@section('title', 'Order Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Order Details</h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                @if($order->status != 'Canceled')
                                    @can('edit', $order)
                                        <a href="{{ route('sales.order.edit', [$order]) }}"
                                           class="btn waves-effect waves-light btn-primary btn-sm"
                                           target="_blank">
                                            <i class="fa fa-pencil"></i> Edit
                                        </a>
                                    @endcan
                                    @if(isset($pendingOrderAmount) && $pendingOrderAmount > 0)
                                        @can('create', new \App\Invoice())
                                            <button class="btn waves-effect waves-light btn-info btn-sm" id="createInv">
                                                <i class="fa fa-plus"></i> Create Invoice
                                            </button>
                                        @endcan
                                    @endif
                                    @can('edit', $order)
                                        <button class="btn waves-effect waves-light btn-danger btn-sm" id="cancelOrder">
                                            <i class="fa fa-ban"></i> Cancel Order
                                        </button>
                                    @endcan
                                    @can('edit', $order)
                                        <button class="btn waves-effect waves-light btn-info btn-sm" data-value="{{ $order->id }}" id="allowPrint">
                                            <i class="fa fa-print"></i> Allow to Print
                                        </button>
                                    @endcan
                                    @if($order->is_credit_sales == 'No')
                                        @can('edit', $order)
                                            <button class="btn waves-effect waves-light btn-info btn-sm" data-value="{{ $order->id }}" id="updateCreditOrder">
                                                <i class="fa fa-money"></i> Update as Credit Order
                                            </button>
                                        @endcan
                                    @endif
                                @endif
                                @can('clone', $order)
                                    <a href="{{ route('sales.order.clone', [$order]) }}"
                                       class="btn waves-effect waves-light btn-warning btn-sm"
                                       target="_blank">
                                        <i class="fa fa-copy"></i> Clone
                                    </a>
                                @endcan
                            </div>
                            <div class="pull-right">
                                @can('export', $order)
                                    <a href="{{ route('sales.order.export', [$order]) }}"
                                       class="btn waves-effect waves-light btn-inverse btn-sm">
                                        <i class="fa fa-file-pdf-o"></i> Export to PDF
                                    </a>
                                @endcan
                                @can('print', $order)
                                    <a href="{{ route('sales.order.print', [$order]) }}"
                                       class="btn waves-effect waves-light btn-inverse btn-sm">
                                        <i class="fa fa-print"></i> Print
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>

                    <!-- load create invoice form -->
                @include('sales.order.invoice.create', ['model' => $order])

                @include('sales.general.cancel.create', ['model' => $order, 'route' => route('sales.order.cancel', [$order]), 'varName' => 'order'])

                <!-- po summary and history -->
                    <div class="row custom-top-margin">
                        <div class="col-md-9">
                            <div class="card card-body printableArea">
                                <h3>
                                    <b>
                                        @if($order->is_credit_sales == 'Yes')
                                            CREDIT
                                        @else
                                            CASH
                                        @endif
                                        ORDER
                                    </b> |
                                    <small class="{{ statusLabelColor($order->status) }}">
                                        {{ $order->status }}
                                    </small>
                                    <small>
                                        @if($order->is_order_printed == 'Yes')
                                            <span class="text-green">{{ '& Printed' }}</span>
                                        @else
                                            <span class="text-warning">{{ '| Not Printed' }}</span>
                                        @endif
                                    </small>

                                    <span class="pull-right">#{{ $order->ref }}</span></h3>
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
                                                <h4 class="font-bold">{{ $customer->display_name }}</h4>
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
                                            <p><b>Delivery Date :</b> {{ $order->delivery_date }}</p>
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
                                                    <th class="text-right">Discount</th>
                                                    <th class="text-right">Total</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(count($items))
                                                    @foreach($items as $itemKey => $item)
                                                        <tr>
                                                            <td class="text-center">{{ $itemKey+1 }}</td>
                                                            <td>
                                                                {{ $item->name }}<br>
                                                                <small class="text-muted">{{ $item->pivot->notes }}</small>
                                                            </td>
                                                            <td class="text-center">{{ $item->pivot->quantity }}</td>
                                                            <td class="text-right">{{ number_format($item->pivot->rate, 2) }}</td>
                                                            <td class="text-right">
                                                                {{ number_format($item->pivot->discount, 2) }}
                                                                {{ $item->pivot->discount_type == 'Percentage' ? '('.$item->pivot->discount_rate.'%)' : ''}}
                                                            </td>
                                                            <td class="text-right">{{ number_format($item->pivot->amount, 2) }}</td>
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
                                                    <td width="80%" class="text-right">Sub total</td>
                                                    <td class="text-right custom-td-btm-border "> {{ number_format($order->sub_total, 2) }} </td>
                                                </tr>
                                                <tr>
                                                    <td width="80%" class="text-right">
                                                        Discount {{ $order->discount_type == 'Percentage' ? '('.$order->discount_rate.'%)' : ''}}</td>
                                                    <td class="text-right custom-td-btm-border "> {{ number_format($order->discount, 2) }} </td>
                                                </tr>
                                                <tr>
                                                    <td width="80%" class="text-right">Adjustment</td>
                                                    <td class="text-right custom-td-btm-border "> {{ number_format($order->adjustment, 2) }} </td>
                                                </tr>
                                                <tr>
                                                    <td width="80%" class="text-right"><h3><b>Total</b></h3></td>
                                                    <td class="text-right custom-td-btm-border "><h3>
                                                            <b>{{ number_format($order->total, 2) }}</b></h3></td>
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
                                        <p><b>Customer :</b>
                                            <a target="_blank"
                                               href="{{ route('sales.customer.show', [$order->customer]) }}">
                                                {{ $order->customer->display_name or 'None' }}
                                            </a>
                                        </p>
                                        <p><b>Order type :</b> {{ $order->order_type }}</p>
                                        <p><b>Scheduled date :</b> {{ $order->scheduled_date or 'None' }}</p>
                                        {{--<p><b>Business type :</b> {{ $order->businessType->name or 'None' }}</p>--}}
                                        <p><b>Company :</b> {{ $order->company->name or 'None' }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p>
                                            <b>Delivery Status :</b>
                                            <span class="{{ statusLabelColor($order->delivery_status) }}">{{ $order->delivery_status }}</span>
                                        </p>
                                        <p>
                                            <b>Invoice Status :</b>
                                            <span class="{{ statusLabelColor($order->invoice_status) }}">{{ $order->invoice_status }}</span>
                                        </p>
                                        <p><b>Approved by :</b> {{ $order->approvedBy->name or 'None' }}</p>
                                        <p><b>Prepared by :</b> {{ $order->preparedBy->name or 'None'}}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="order-terms">
                                            <h5>Terms & Conditions</h5>
                                            <small class="text-muted">{{ $order->terms }}</small>
                                        </div>
                                        <br/>
                                        <div class="order-notes">
                                            <h5>Notes</h5>
                                            <small class="text-muted">{{ $order->notes }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- invoices -->
                            @include('sales.general.invoices', ['invoices' => $invoices])

                            <!-- payment received -->
                            @include('sales.general.payment.index', ['payments' => $payments])

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

                            <!-- convert order open -->
                            @if($order->status == 'Draft')
                                <div class="card border-warning text-center so-convert-panel">
                                    <div class="card-body">
                                        <h3 class="card-title text-danger"><i class="fa fa-clock-o"></i> Convert to Open
                                        </h3>
                                        <p class="card-subtitle"> This is a <code>DRAFT</code> order. You can take
                                            further actions once you convert to <code>OPEN</code>.</p>
                                        <a class="btn btn-danger convert-po" href="" data-id="{{ $order->id }}">
                                            <i class="fa fa-check"></i> Convert Order
                                        </a>
                                    </div>
                                </div>
                            @endif

                        <!-- approve order -->
                            @if($order->status == 'Awaiting Approval')
                                <div class="card border-warning text-center so-approval-panel">
                                    <div class="card-body">
                                        <h3 class="card-title text-danger"><i class="fa fa-clock-o"></i> Approval
                                            Pending</h3>
                                        <p class="card-subtitle"> This is order is waiting for approval. You can take
                                            further actions once you approve it.</p>
                                        <a class="btn btn-danger approve-po" href="" data-id="{{ $order->id }}">
                                            <i class="fa fa-check"></i> Approve Order
                                        </a>
                                    </div>
                                </div>
                            @endif

                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Sales Order Summary</h4>
                                    <hr>
                                    <div>
                                        <h3 class="card-title"><b>{{ number_format($order->total, 2) }}</b></h3>
                                        <h6 class="card-subtitle">Sales Order Amount
                                            <span class="pull-right">{{ number_format(getProgressValue($order->total, soOutstanding($order)['paid']), 2) }}%</span>
                                        </h6>
                                    </div>
                                    <div class="custom-top-margin">
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                 style="width: {{ getProgressValue($order->total, soOutstanding($order)['paid'])}}%; height:10px;" aria-valuenow="25" aria-valuemin="0"
                                                 aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <h4>{{ number_format(soOutstanding($order)['invoiced'], 2) }}</h4>
                                            <h6 class="text-muted text-info">Total Invoiced</h6>
                                        </div>
                                        <div class="col-4">
                                            <h4>{{ number_format(soOutstanding($order)['paid'], 2) }}</h4>
                                            <h6 class="text-muted text-success">Total Paid</h6>
                                        </div>
                                        <div class="col-4">
                                            <h4>{{ number_format(soOutstanding($order)['balance'], 2) }}</h4>
                                            <h6 class="text-muted text-warning">Total Balance</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>

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
    @include('sales.order.invoice.script', ['modal' => $order])
    @include('_inc.document.script', ['model' => $order])
    @include('sales.general.cancel.script', ['modal' => $order, 'btnName' => 'cancelOrder',  'varName' => 'order'])
    <script>
        /** approve order */
        $('.so-approval-panel').on('click', '.approve-po', function () {
            var id = $(this).data('id');
            var approvalUrl = '{{ route('sales.order.approve', [ 'order'=>'ID']) }}';
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
        $('.so-convert-panel').on('click', '.convert-po', function () {
            var id = $(this).data('id');
            var approvalUrl = '{{ route('sales.order.convert', [ 'order'=>'ID']) }}';
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

        /** allow to print */
        $('#allowPrint').on('click', function () {
            var orderId = $(this).data('value');
            var allowPrintUrl = '{{ route('sales.order.allow.print', [ 'order'=>'ID']) }}';
            allowPrintUrl = allowPrintUrl.replace('ID', orderId);
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action! Are you sure want to allow this order to print again?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4aba45',
                confirmButtonText: 'Yes, Allow!'
            }).then(function (isConfirm) {
                if (isConfirm.value) {
                    $.ajax({
                        url: allowPrintUrl,
                        type: 'POST',
                        data: {'_token': '{{ csrf_token() }}'},
                        success: function (result) {
                            swal(
                                'Allow Print!',
                                'Order updated successfully!',
                                'success'
                            );
                            setTimeout(location.reload(), 300);
                        }
                    });
                }
            });
        });

        /** update order as Credit Order */
        $('#updateCreditOrder').on('click', function () {
            var orderId = $(this).data('value');
            var allowPrintUrl = '{{ route('sales.order.update.to.credit', [ 'order'=>'ID']) }}';
            allowPrintUrl = allowPrintUrl.replace('ID', orderId);
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action! Are you sure that you want to update this order as Credit?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4aba45',
                confirmButtonText: 'Yes, Update!'
            }).then(function (isConfirm) {
                if (isConfirm.value) {
                    $.ajax({
                        url: allowPrintUrl,
                        type: 'POST',
                        data: {'_token': '{{ csrf_token() }}'},
                        success: function (result) {
                            swal(
                                'Update to Credit!',
                                'Order updated successfully!',
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