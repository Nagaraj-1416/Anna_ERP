@extends('layouts.master')
@section('title', 'Invoice Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row main">
        <div class="col-md-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Invoice Details</h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                @if(!($invoice->status == 'Canceled' || $invoice->status == 'Refunded'))
                                    @can('edit', $invoice)
                                        <a href="{{ route('sales.invoice.edit', [$invoice]) }}"
                                           class="btn waves-effect waves-light btn-primary btn-sm"
                                           target="_blank">
                                            <i class="fa fa-pencil"></i> Edit
                                        </a>
                                    @endcan
                                    @if($pendingAmount > 0)
                                        @can('create', new \App\InvoicePayment())
                                            <button class="btn waves-effect waves-light btn-info btn-sm"
                                                    id="recordPayment">
                                                <i class="fa fa-plus"></i> Record Payment
                                            </button>
                                        @endcan
                                    @endif
                                    @if($invoice->status != 'Canceled')
                                        @can('cancel', $invoice)
                                            <button class="btn waves-effect waves-light btn-danger btn-sm"
                                                    id="cancelInvoice">
                                                <i class="fa fa-ban"></i> Cancel Invoice
                                            </button>
                                        @endcan
                                    @endif
                                @endif
                                @if($invoice->status == 'Canceled')
                                    @can('refund', $invoice)
                                        <button class="btn waves-effect waves-light btn-warning btn-sm"
                                                id="refundInvoice">
                                            <i class="fa fa-reply"></i> Refund Invoice
                                        </button>
                                    @endcan
                                @endif
                                @can('show', $invoice->order)
                                    <a href="{{ route('sales.order.show', [$invoice->order]) }}"
                                       class="btn waves-effect waves-light btn-dark btn-sm"
                                       target="_blank">
                                        <i class="fa fa-shopping-cart"></i> Go to Order
                                    </a>
                                @endcan
                            </div>
                            <div class="pull-right">
                                @can('export', $invoice)
                                    <a href="{{ route('sales.invoice.export', [$invoice]) }}"
                                       class="btn waves-effect waves-light btn-inverse btn-sm">
                                        <i class="fa fa-file-pdf-o"></i> Export to PDF
                                    </a>
                                @endcan
                                @can('print', $invoice)
                                    <a href="{{ route('sales.invoice.print', [$invoice]) }}"
                                       class="btn waves-effect waves-light btn-inverse btn-sm"
                                       target="_blank">
                                        <i class="fa fa-print"></i> Print
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>

                    <!-- load record payment form -->
                @include('sales.invoice.payment.create', ['model' => $invoice])

                @include('sales.general.cancel.create', ['model' => $invoice, 'route' => route('sales.invoice.cancel', [$invoice]), 'varName' => 'invoice', 'header' => 'Invoice'])
                @include('sales.general.cancel.create', ['model' => $invoice, 'route' => route('sales.payment.cancel', ['payment' => 'PAYMENT']), 'formName' => 'paymentCancelForm', 'header' => 'Payment', 'varName' => 'payment'])
                @include('sales.general.refund.create', ['model' => $invoice, 'route' => route('sales.invoice.refund', [$invoice]), 'varName' => 'invoice', 'header' => 'Invoice'])
                @include('sales.general.refund.create', ['model' => $invoice, 'route' => route('sales.payment.refund', ['payment' => 'PAYMENT']), 'formName' => 'paymentRefundForm', 'header' => 'Payment', 'varName' => 'payment'])

                <!-- invoice summary and history -->
                    <div class="row custom-top-margin">
                        <div class="col-md-9">
                            <div class="card card-body printableArea">
                                <h3>
                                    <b>INVOICE</b> |
                                    <small class="{{ statusLabelColor($invoice->status) }}">
                                        {{ $invoice->status }}
                                    </small>
                                    <span class="pull-right">#{{ $invoice->ref }}</span>
                                </h3>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-left">
                                            <address>
                                                <h4> &nbsp;<b class="text-danger">{{ $company->name }}</b></h4>
                                                @include('_inc.address.view', ['address' => $companyAddress])
                                            </address>
                                        </div>
                                        <div class="pull-right text-right">
                                            <address>
                                                <h4 class="font-bold">{{ $customer->display_name }}</h4>
                                                @include('_inc.address.view', ['address' => $address])
                                            </address>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="table-responsive" style="clear: both;">
                                            <table class="table color-table inverse-table">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th>Invoice Date</th>
                                                    <th>Due Date</th>
                                                    <th>Prepared By</th>
                                                    <th>Status</th>
                                                    <th class="text-right">Amount</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td class="text-center">1</td>
                                                    <td>{{ $invoice->invoice_date }}</td>
                                                    <td>{{ $invoice->due_date }}</td>
                                                    <td>{{ $invoice->preparedBy->name or 'None' }}</td>
                                                    <td>{{ $invoice->status }}</td>
                                                    <td class="text-right">{{ number_format($invoice->amount, 2) }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="table-responsive" style="clear: both;">
                                            <table class="table">
                                                <tbody>
                                                <tr style="color: red;">
                                                    <td width="80%" class="text-right">Payments Received</td>
                                                    <td class="text-right custom-td-btm-border ">
                                                        ({{ number_format(invOutstanding($invoice)['paid'], 2) }})
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="80%" class="text-right"><h3><b>Balance</b></h3></td>
                                                    <td class="text-right custom-td-btm-border "><h3>
                                                            <b>{{ number_format(invOutstanding($invoice)['balance'], 2) }}</b>
                                                        </h3></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="box-title box-title-with-margin">Other Details</h4>
                                <hr>
                                <div class="row">
                                    {{--<div class="col-md-3">
                                        <p><b>Business type :</b> {{ $invoice->businessType->name or 'None' }}</p>
                                    </div>--}}
                                    <div class="col-md-3">
                                        <p><b>Company :</b> {{ $invoice->company->name or 'None' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="order-notes">
                                            <h5>Notes</h5>
                                            <small class="text-muted">{{ $invoice->notes }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- payments received -->
                            @include('sales.general.payment.index', ['payments' => $payments])

                            <div class="card">
                                <div class="card-body">
                                    <h3><b>ATTACHMENTS</b> <span
                                                class="pull-right">Total: {{ count($invoice->documents) }}</span></h3>
                                    <hr>
                                    @include('_inc.document.view', ['model' => $invoice])
                                </div>
                            </div>
                        </div>

                        <!-- recent logs -->
                        <div class="col-md-3">
                            <!-- invoice summary -->
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Invoice Summary</h4>
                                    <hr>
                                    <div>
                                        <h3 class="card-title"><b>{{ number_format($invoice->amount, 2) }}</b></h3>
                                        <h6 class="card-subtitle">Invoice Amount
                                            <span class="pull-right">{{ number_format(getProgressValue($invoice->amount, invOutstanding($invoice)['paid']), 2) }}%</span>
                                        </h6>
                                    </div>
                                    <div class="custom-top-margin">
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                 style="width: {{ getProgressValue($invoice->amount, invOutstanding($invoice)['paid']) }}%; height:10px;" aria-valuemin="0"
                                                 aria-valuemax="200"></div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <h4>{{ number_format(invOutstanding($invoice)['paid'], 2) }}</h4>
                                            <h6 class="text-muted text-green">Paid Amount</h6>
                                        </div>
                                        <div class="col-6">
                                            <h4>{{ number_format(invOutstanding($invoice)['balance'], 2) }}</h4>
                                            <h6 class="text-muted text-warning">Balance Amount</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- sales order summary -->
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Sales Order Summary</h4>
                                    <hr>
                                    <div>
                                        <p>
                                            <b>Customer :</b>
                                            <a target="_blank"
                                               href="{{ route('sales.customer.show', [$invoice->customer]) }}">{{ $invoice->customer->display_name ?? 'None' }}</a>
                                        </p>
                                        <p>
                                            <b>Order No :</b>
                                            <a target="_blank"
                                               href="{{ route('sales.order.show', [$invoice->order]) }}">{{ $invoice->order->ref }}</a>
                                        </p>
                                        <p><b>Order Date :</b> {{ $invoice->order->order_date }}</p>
                                        <p><b>Delivery Date :</b> {{ $invoice->order->delivery_date }}</p>
                                        <p><b>Order Status :</b> <span
                                                    class="{{ statusLabelColor($invoice->order->status) }}">{{ $invoice->order->status }}</span>
                                        </p>
                                        <p><b>Invoice Status :</b> <span
                                                    class="{{ statusLabelColor($invoice->order->invoice_status) }}">{{ $invoice->order->invoice_status }}</span>
                                        </p>
                                        <p><b>Delivery Status :</b> <span
                                                    class="{{ statusLabelColor($invoice->order->delivery_status) }}">{{ $invoice->order->delivery_status }}</span>
                                        </p>
                                        <hr>
                                        <h3 class="card-title"><b>{{ number_format($invoice->order->total, 2) }}</b>
                                        </h3>
                                        <h6 class="card-subtitle">Sales Order Amount
                                            <span class="pull-right">{{ number_format(getProgressValue($invoice->order->total, soOutstanding($invoice->order)['paid']), 2) }}%</span>
                                        </h6>
                                    </div>
                                    <div class="custom-top-margin">
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                 style="width: {{getProgressValue($invoice->order->total, soOutstanding($invoice->order)['paid'])}}%; height:10px;" aria-valuenow="25" aria-valuemin="0"
                                                 aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <h4>{{ number_format(soOutstanding($invoice->order)['invoiced'], 2) }}</h4>
                                            <h6 class="text-muted text-info">Total Billed</h6>
                                        </div>
                                        <div class="col-4">
                                            <h4>{{ number_format(soOutstanding($invoice->order)['paid'], 2) }}</h4>
                                            <h6 class="text-muted text-success">Total Paid</h6>
                                        </div>
                                        <div class="col-4">
                                            <h4>{{ number_format(soOutstanding($invoice->order)['balance'], 2) }}</h4>
                                            <h6 class="text-muted text-warning">Total Balance</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- recent comments -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.comment.index', ['model' => $invoice])
                                </div>
                            </div>

                            <!-- recent audit logs -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.log.index', ['model' => $invoice, 'modelName' => 'Invoice'])
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
    @include('general.comment.script', ['modelId' => $invoice->id])
    @include('sales.invoice.payment.script', ['modal' => $invoice])
    @include('_inc.document.script', ['model' => $invoice])
    @include('sales.general.payment.script')
    @include('sales.general.cancel.script', ['modal' => $invoice, 'btnName' => 'cancelInvoice',  'varName' => 'invoice'])
    @include('sales.general.cancel.script', ['modal' => $invoice, 'btnName' => 'paymentCancel', 'formName' => 'paymentCancelForm', 'varName' => 'payment', 'route' => route('sales.payment.cancel', ['payment' => 'PAYMENT'])])
    @include('sales.general.refund.script', ['modal' => $invoice, 'btnName' => 'refundInvoice',  'varName' => 'invoice'])
    @include('sales.general.refund.script', ['modal' => $invoice, 'btnName' => 'paymentRefund', 'formName' => 'paymentRefundForm', 'varName' => 'payment', 'route' => route('sales.payment.refund', ['payment' => 'PAYMENT'])])
@endsection