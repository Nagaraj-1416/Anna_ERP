@extends('layouts.master')
@section('title', 'Bill Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Purchase') !!}
@endsection
@section('content')
    <div class="row main">
        <div class="col-md-12">
            <div class="card card-outline-inverse">
                <div class="card-header main">
                    <h4 class="m-b-0 text-white">{{ $bill->bill_no }}</h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">

                                @if($bill->status != 'Canceled' && $bill->status != 'Paid')
                                    @can('edit', $bill)
                                        <a href="{{ route('purchase.bill.edit', [$bill]) }}"
                                           class="btn waves-effect waves-light btn-primary btn-sm"
                                           target="_blank">
                                            <i class="fa fa-pencil"></i> Edit
                                        </a>
                                        <button class="btn waves-effect waves-light btn-danger btn-sm" id="cancelBill">
                                            <i class="fa fa-ban"></i> Cancel Bill
                                        </button>
                                    @endcan
                                    @can('create', new \App\BillPayment())
                                        @if($pendingAmount > 0)
                                            <button class="btn waves-effect waves-light btn-info btn-sm"
                                                    id="recordPayment">
                                                <i class="fa fa-plus"></i> Record Payment
                                            </button>
                                        @endif
                                    @endcan
                                @endif
                                @can('show', $bill->order)
                                    <a href="{{ route('purchase.order.show', [$bill->order]) }}"
                                       class="btn waves-effect waves-light btn-dark btn-sm"
                                       target="_blank">
                                        <i class="fa fa-shopping-cart"></i> Go to Order
                                    </a>
                                @endcan
                            </div>
                            <div class="pull-right">
                                @can('export', $bill)
                                    <a href="{{ route('purchase.bill.export', [$bill]) }}"
                                       class="btn waves-effect waves-light btn-inverse btn-sm"
                                       target="_blank">
                                        <i class="fa fa-file-pdf-o"></i> Export to PDF
                                    </a>
                                @endcan
                                @can('print', $bill)
                                    <a href="{{ route('purchase.bill.print', [$bill]) }}"
                                       class="btn waves-effect waves-light btn-inverse btn-sm"
                                       target="_blank">
                                        <i class="fa fa-print"></i> Print
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>

                    <!-- load record payment form -->
                @include('purchases.bill.payment.create', ['model' => $bill])
                @include('purchases.general.cancel.create', ['model' => $bill, 'route' => route('purchase.bill.cancel', [$bill]), 'formName' => 'billCancel', 'varName' => 'bill', 'header' => 'Bill',])
                @include('purchases.general.cancel.create', ['model' => $bill, 'route' => route('purchase.payment.cancel', ['payment' => 'PAYMENT']), 'formName' => 'paymentCancelForm', 'header' => 'Payment', 'varName' => 'payment'])
                @include('purchases.general.refund.create', ['model' => $bill, 'route' => route('purchase.payment.refund', ['payment' => 'PAYMENT']), 'formName' => 'paymentRefundForm', 'header' => 'Payment', 'varName' => 'payment'])

                <!-- bill summary and history -->
                    <div class="row custom-top-margin">
                        <div class="col-md-9">
                            <div class="card card-body printableArea">
                                <h3>
                                    <b>BILL</b> |
                                    <small class="{{ statusLabelColor($bill->status) }}">
                                        {{ $bill->status }}
                                    </small>
                                    <span class="pull-right">#{{ $bill->bill_no }}</span>
                                </h3>
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
                                                <h4><b class="text-inverse">{{ $supplier->display_name }}</b>
                                                </h4>
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
                                                    <th>Bill date</th>
                                                    <th>Due date</th>
                                                    <th>Prepared by</th>
                                                    <th>Status</th>
                                                    <th class="text-right">Amount</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td class="text-center">1</td>
                                                    <td>{{ $bill->bill_date }}</td>
                                                    <td>{{ $bill->due_date }}</td>
                                                    <td>{{ $bill->preparedBy->name or 'None' }}</td>
                                                    <td>{{ $bill->status }}</td>
                                                    <td class="text-right">{{ number_format($bill->amount, 2) }}</td>
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
                                                    <td width="80%" class="text-right">Payments Made</td>
                                                    <td class="text-right custom-td-btm-border ">
                                                        ({{ number_format(billOutstanding($bill)['paid'], 2) }})
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="80%" class="text-right"><h3><b>Balance</b></h3></td>
                                                    <td class="text-right custom-td-btm-border "><h3>
                                                            <b>{{ number_format(billOutstanding($bill)['balance'], 2) }}</b>
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
                                    <div class="col-md-3">
                                        <p><b>Company :</b> {{ $bill->company->name or 'None' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="order-notes">
                                            <h5>Notes</h5>
                                            <small class="text-muted">{{ $bill->notes }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- payments made -->
                            @include('purchases.general.payment.index', ['payments' => $payments])

                            <div class="card">
                                <div class="card-body">
                                    <h3><b>ATTACHMENTS</b> <span
                                                class="pull-right">Total: {{ count($bill->documents) }}</span></h3>
                                    <hr>
                                    @include('_inc.document.view', ['model' => $bill])
                                </div>
                            </div>
                        </div>

                        <!-- recent logs -->
                        <div class="col-md-3">

                            <!-- bill summary -->
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Bill Summary</h4>
                                    <hr>
                                    <div>
                                        <h3 class="card-title"><b>{{ number_format($bill->amount, 2) }}</b></h3>
                                        <h6 class="card-subtitle">Bill Amount
                                            <span class="pull-right">{{ number_format(getProgressValue($bill->amount, billOutstanding($bill)['paid']), 2) }}%</span>
                                        </h6>
                                    </div>
                                    <div class="custom-top-margin">
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                 style="width: {{ getProgressValue($bill->amount, billOutstanding($bill)['paid']) }}%; height:10px;" aria-valuenow="25" aria-valuemin="0"
                                                 aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <h4>{{ number_format(billOutstanding($bill)['paid'], 2) }}</h4>
                                            <h6 class="text-muted text-success">Paid Amount</h6>
                                        </div>
                                        <div class="col-6">
                                            <h4>{{ number_format(billOutstanding($bill)['balance'], 2) }}</h4>
                                            <h6 class="text-muted text-warning">Balance Amount</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- purchase order summary -->
                        @include('purchases.bill._inc.order')

                        <!-- recent comments -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.comment.index', ['model' => $bill])
                                </div>
                            </div>

                            <!-- recent audit logs -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.log.index', ['model' => $bill, 'modelName' => 'Bill'])
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
    @include('general.comment.script', ['modelId' => $bill->id])
    @include('purchases.bill.payment.script', ['modal' => $bill])
    @include('_inc.document.script', ['model' => $bill])
    @include('purchases.general.payment.script')
    @include('purchases.general.cancel.script', ['modal' => $bill, 'btnName' => 'cancelBill', 'formName' => 'billCancel',  'varName' => 'bill'])
    @include('purchases.general.cancel.script', ['modal' => $bill, 'btnName' => 'paymentCancel', 'formName' => 'paymentCancelForm', 'varName' => 'payment', 'route' => route('purchase.payment.cancel', ['payment' => 'PAYMENT'])])
    @include('purchases.general.refund.script', ['modal' => $bill, 'btnName' => 'paymentRefund', 'formName' => 'paymentRefundForm', 'varName' => 'payment', 'route' => route('purchase.payment.refund', ['payment' => 'PAYMENT'])])

@endsection