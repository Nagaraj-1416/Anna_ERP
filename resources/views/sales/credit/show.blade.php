@extends('layouts.master')
@section('title', 'Credit Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">{{ $credit->code }}</h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                @if($credit->status == 'Canceled')
                                    @can('clone', $credit)
                                        <a href="{{ route('sales.credit.clone', [$credit]) }}"
                                           class="btn waves-effect waves-light btn-warning btn-sm"
                                           target="_blank">
                                            <i class="fa fa-copy"></i> Clone
                                        </a>
                                    @endcan
                                    @can('edit', $credit)
                                        <button id="refund-sidebar-btn" data-id="{{ $credit->id }}"
                                                class="btn waves-effect waves-light btn-info btn-sm credit-open-btn">
                                            <i class="fa fa-money"></i> Open Credit
                                        </button>
                                    @endcan
                                @else

                                    @can('edit', $credit)
                                        <a href="{{ route('sales.credit.edit', [$credit]) }}"
                                           class="btn waves-effect waves-light btn-primary btn-sm"
                                           target="_blank">
                                            <i class="fa fa-pencil"></i> Edit
                                        </a>
                                    @endcan
                                    @can('clone', $credit)
                                        <a href="{{ route('sales.credit.clone', [$credit]) }}"
                                           class="btn waves-effect waves-light btn-warning btn-sm"
                                           target="_blank">
                                            <i class="fa fa-copy"></i> Clone
                                        </a>
                                    @endcan
                                    @can('create', new \App\CustomerCreditRefund())
                                        <button id="refund-sidebar-btn"
                                                class="btn waves-effect waves-light btn-danger btn-sm sidebar-btn">
                                            <i class="fa fa-money"></i> Refund Credit
                                        </button>
                                    @endcan
                                    @can('create', new \App\InvoicePayment())
                                        <button id="apply_to_invoices_btn"
                                                class="btn waves-effect waves-light btn-info btn-sm">
                                            <i class="fa fa-money"></i> Apply to Invoices
                                        </button>
                                    @endcan
                                    @can('edit', $credit)
                                        <button id="refund-sidebar-btn" data-id="{{ $credit->id }}"
                                                class="btn waves-effect waves-light btn-danger btn-sm credit-cancel-btn">
                                            <i class="fa fa-money"></i> Cancel Credit
                                        </button>
                                    @endcan
                                @endif
                            </div>
                            <div class="pull-right">
                                @can('export', $credit)
                                    <a href="{{ route('sales.credit.export', [$credit]) }}"
                                       class="btn waves-effect waves-light btn-inverse btn-sm">
                                        <i class="fa fa-file-pdf-o"></i> Export to PDF
                                    </a>
                                @endcan
                                @can('print', $credit)
                                    <a target="_blank" href="{{ route('sales.credit.print', [$credit]) }}"
                                       class="btn waves-effect waves-light btn-inverse btn-sm">
                                        <i class="fa fa-print"></i> Print
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                @include('sales.credit.general.invoice.index')
                <!-- po summary and history -->
                    <div class="row custom-top-margin">
                        <div class="col-md-9">
                            <div class="card card-body printableArea">
                                <h3>
                                    <b>CUSTOMER CREDIT</b> |
                                    <small class="{{ statusLabelColor($credit->status) }}">
                                        {{ $credit->status }}
                                    </small>
                                    <span class="pull-right">#{{ $credit->code }}</span></h3>
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
                                            <p><b>Credit Date :</b> {{ $credit->date }}</p>
                                        </div>
                                        <div class="pull-right text-right"></div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="table-responsive" style="clear: both;">
                                            <table class="table color-table inverse-table">
                                                <thead>
                                                <tr>
                                                    <th>Notes</th>
                                                    <th class="text-right" style="width: 25%;">Amount</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>{{ $credit->notes or 'None' }}</td>
                                                    <td class="text-right">{{ number_format($credit->amount, 2) }}</td>
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
                                                    <td width="80%" class="text-right">Refunds</td>
                                                    <td class="text-right custom-td-btm-border ">
                                                        ({{ number_format($credit->refunds->sum('amount'), 2) }})
                                                    </td>
                                                </tr>
                                                <tr style="color: red;">
                                                    <td width="80%" class="text-right">Credited</td>
                                                    <td class="text-right custom-td-btm-border ">
                                                        ({{ number_format($credit->payments->sum('payment'), 2) }})
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="80%" class="text-right">
                                                        <h3><b>Remaining</b></h3>
                                                    </td>
                                                    <td class="text-right custom-td-btm-border ">
                                                        <h3>
                                                            <b>{{ number_format(getCustomerCreditLimit($credit), 2) }}</b>
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
                                        <p><b>Customer :</b>
                                            <a target="_blank"
                                               href="{{ route('sales.customer.show', [$credit->customer]) }}">
                                                {{ $credit->customer->display_name or 'None' }}
                                            </a>
                                        </p>
                                        <p><b>Company :</b> {{ $credit->company->name or 'None' }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        {{--<p><b>Business type :</b> {{ $credit->businessType->name or 'None' }}</p>--}}
                                        <p><b>Prepared by :</b> {{ $credit->preparedBy->name or 'None'}}</p>
                                    </div>
                                    <div class="col-md-6"></div>
                                </div>
                            </div>
                            {{--Refund --}}
                            @include('sales.credit.refund.refund', ['credit' => $credit])
                            @include('sales.credit.general.invoice.list.index')
                            <div class="card">
                                <div class="card-body">
                                    <h3><b>ATTACHMENTS</b> <span
                                                class="pull-right">Total: {{ count($credit->documents) }}</span></h3>
                                    <hr>
                                    @include('_inc.document.view', ['model' => $credit])
                                </div>
                            </div>
                        </div>

                        <!-- recent logs -->
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    @include('general.comment.index', ['model' => $credit])
                                </div>
                            </div>

                            <!-- recent audit logs -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.log.index', ['model' => $credit, 'modelName' => 'Order'])
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
    @include('general.comment.script', ['modelId' => $credit->id])
    @include('sales.order.invoice.script', ['modal' => $credit])
    @include('_inc.document.script', ['model' => $credit])
    @include('sales.credit.general.invoice.script')
    @include('sales.credit.general.invoice.list.script')
    <script>
        $('.credit-open-btn').click(function () {
            var id = $(this).data('id');
            var approvalUrl = '{{ route('sales.credit.status.change', [ 'credit'=>'ID']) }}';
            approvalUrl = approvalUrl.replace('ID', id);
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action! Are you sure want to open this Credit?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4aba45',
                confirmButtonText: 'Yes, Open!'
            }).then(function (isConfirm) {
                if (isConfirm.value) {
                    $.ajax({
                        url: approvalUrl,
                        type: 'POST',
                        data: {'_token': '{{ csrf_token() }}', 'status': 'Open'},
                        success: function (result) {
                            swal(
                                'Credit status changed!',
                                'Customer Credit Opened successfully!',
                                'success'
                            ).then(function () {
                                location.reload()
                            });
                        }
                    });
                }
            });
        });

        $('.credit-cancel-btn').click(function () {
            var id = $(this).data('id');
            var approvalUrl = '{{ route('sales.credit.status.change', [ 'credit'=>'ID']) }}';
            approvalUrl = approvalUrl.replace('ID', id);
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action! Are you sure want to cancel this Credit?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4aba45',
                confirmButtonText: 'Yes, Cancel!'
            }).then(function (isConfirm) {
                if (isConfirm.value) {
                    $.ajax({
                        url: approvalUrl,
                        type: 'POST',
                        data: {'_token': '{{ csrf_token() }}', 'status': 'Canceled'},
                        success: function (result) {
                            swal(
                                'Credit status changed!',
                                'Customer Credit canceled successfully!',
                                'success'
                            ).then(function () {
                                location.reload()
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection