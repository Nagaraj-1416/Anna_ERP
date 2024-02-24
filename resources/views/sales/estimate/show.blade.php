@extends('layouts.master')
@section('title', 'Estimate Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">{{ $estimate->estimate_no }}</h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                @can('edit', $estimate)
                                    <a href="{{ route('sales.estimate.edit', [$estimate]) }}"
                                       class="btn waves-effect waves-light btn-primary btn-sm"
                                       target="_blank">
                                        <i class="fa fa-pencil"></i> Edit
                                    </a>
                                @endcan
                                @can('clone', $estimate)
                                    <a href=""
                                       class="btn waves-effect waves-light btn-warning btn-sm"
                                       target="_blank">
                                        <i class="fa fa-copy"></i> Clone
                                    </a>
                                @endcan
                            </div>
                            <div class="pull-right">
                                @can('export', $estimate)
                                    <a href="{{ route('sales.estimate.export', [$estimate]) }}"
                                       class="btn waves-effect waves-light btn-inverse btn-sm">
                                        <i class="fa fa-file-pdf-o"></i> Export to PDF
                                    </a>
                                @endcan
                                @can('print', $estimate)
                                    <a href="{{ route('sales.estimate.print', [$estimate]) }}"
                                       class="btn waves-effect waves-light btn-inverse btn-sm"
                                       target="_blank">
                                        <i class="fa fa-print"></i> Print
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>

                    <!-- estimate summary and history -->
                    <div class="row custom-top-margin">
                        <div class="col-md-9">
                            <div class="card card-body printableArea">
                                <h3>
                                    <b>ESTIMATE</b> |
                                    <small class="{{ statusLabelColor($estimate->status) }}">
                                        {{ $estimate->status }}
                                    </small>
                                    <span class="pull-right">#{{ $estimate->estimate_no }}</span></h3>
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
                                                @include('_inc.address.view', ['address' => $address])
                                            </address>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="pull-left">
                                            <p><b>Estimate Date :</b> {{ $estimate->estimate_date }}</p>
                                        </div>
                                        <div class="pull-right text-right">
                                            <p><b>Expiry Date :</b> {{ $estimate->expiry_date }}</p>
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
                                                    <td class="text-right custom-td-btm-border "> {{ number_format($estimate->sub_total, 2) }} </td>
                                                </tr>
                                                <tr>
                                                    <td width="80%" class="text-right">
                                                        Discount {{ $estimate->discount_type == 'Percentage' ? '('.$estimate->discount_rate.'%)' : ''}}</td>
                                                    <td class="text-right custom-td-btm-border "> {{ number_format($estimate->discount, 2) }} </td>
                                                </tr>
                                                <tr>
                                                    <td width="80%" class="text-right">Adjustment</td>
                                                    <td class="text-right custom-td-btm-border "> {{ number_format($estimate->adjustment, 2) }} </td>
                                                </tr>
                                                <tr>
                                                    <td width="80%" class="text-right"><h3><b>Total</b></h3></td>
                                                    <td class="text-right custom-td-btm-border "><h3>
                                                            <b>{{ number_format($estimate->total, 2) }}</b></h3></td>
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
                                               href="{{ route('sales.customer.show', [$estimate->customer]) }}">
                                                {{ $estimate->customer->display_name or 'None' }}
                                            </a>
                                        </p>
                                        {{--<p><b>Business type :</b> {{ $estimate->businessType->name or 'None' }}</p>--}}
                                        <p><b>Company :</b> {{ $estimate->company->name or 'None' }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><b>Sales rep :</b> {{ $estimate->rep->name or 'None' }}</p>
                                        <p><b>Prepared by :</b> {{ $order->preparedBy->name or 'None'}}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="order-terms">
                                            <h5>Terms & Conditions</h5>
                                            <small class="text-muted">{{ $estimate->terms }}</small>
                                        </div>
                                        <br/>
                                        <div class="order-notes">
                                            <h5>Notes</h5>
                                            <small class="text-muted">{{ $estimate->notes }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h3><b>ATTACHMENTS</b> <span
                                                class="pull-right">Total: {{ count($estimate->documents) }}</span></h3>
                                    <hr>
                                    @include('_inc.document.view', ['model' => $estimate])
                                </div>
                            </div>
                        </div>

                        <!-- recent logs -->
                        <div class="col-md-3">

                            <!-- Send Estimate -->
                            @if($estimate->status == 'Draft')
                                <div class="card border-warning text-center send-estimate-panel">
                                    <div class="card-body">
                                        <h3 class="card-title text-danger"><i class="fa fa-clock-o"></i> Send Estimate
                                        </h3>
                                        <p class="card-subtitle"> This is a <code>DRAFT</code> estimate. You can send
                                            this to customer for approval and convert to <code>SENT</code>.</p>
                                        <a class="btn btn-danger send-estimate" href="" data-id="{{ $estimate->id }}">
                                            <i class="fa fa-send"></i> Send
                                        </a>
                                    </div>
                                </div>
                            @endif

                        <!-- Approval Pending -->
                            @if($estimate->status == 'Sent')
                                <div class="card border-warning text-center estimate-approval-panel">
                                    <div class="card-body">
                                        <h3 class="card-title text-danger"><i class="fa fa-clock-o"></i> Approval
                                            Pending</h3>
                                        <p class="card-subtitle"> This estimate is waiting for customer approval. You
                                            can take further actions once estimate make as <code>Accepted</code> or
                                            <code>Declined</code>.</p>
                                        <a class="btn btn-danger accept-estimate" href="" data-id="{{ $estimate->id }}">
                                            <i class="fa fa-check"></i> Mark as Accepted
                                        </a>
                                        <a class="btn btn-danger decline-estimate" href=""
                                           data-id="{{ $estimate->id }}">
                                            <i class="fa fa-check"></i> Mark as Declined
                                        </a>
                                    </div>
                                </div>
                            @endif

                        <!-- Convert to Order -->
                            @if($estimate->status == 'Accepted')
                                <div class="card border-warning text-center estimate-approval-panel">
                                    <div class="card-body">
                                        <h3 class="card-title text-danger"><i class="fa fa-clock-o"></i> Convert to
                                            Order</h3>
                                        <p class="card-subtitle"> This estimate is accepted by customer. You can convert
                                            this estimate to <code>Sales Order</code> now.</p>
                                        <a class="btn btn-danger"
                                           href="{{ route('sales.order.create') }}?estimation={{ $estimate->id }}"
                                           data-id="{{ $estimate->id }}">
                                            <i class="fa fa-check"></i> To Convert
                                        </a>
                                    </div>
                                </div>
                        @endif

                        <!-- recent comments -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.comment.index', ['model' => $estimate])
                                </div>
                            </div>

                            <!-- recent audit logs -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.log.index', ['model' => $estimate, 'modelName' => 'Order'])
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
    @include('general.comment.script', ['modelId' => $estimate->id])
    @include('_inc.document.script', ['model' => $estimate])
    <script>
        var sendEstimatePanel = $('.send-estimate-panel');
        var estimateApprovalPanel = $('.estimate-approval-panel');

        /** send estimate */
        sendEstimatePanel.on('click', '.send-estimate', function () {
            var id = $(this).data('id');
            var sendUrl = '{{ route('sales.estimate.send', [ 'estimate'=>'ID']) }}';
            sendUrl = sendUrl.replace('ID', id);
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action! Are you sure want to send this estimate to customer?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4aba45',
                confirmButtonText: 'Yes, Send!'
            }).then(function (isConfirm) {
                if (isConfirm.value) {
                    $.ajax({
                        url: sendUrl,
                        type: 'POST',
                        data: {'_token': '{{ csrf_token() }}'},
                        success: function (result) {
                            swal(
                                'Send Estimate!',
                                'Sales estimate sent successfully!',
                                'success'
                            );
                            setTimeout(location.reload(), 300);
                        }
                    });
                }
            });
        });

        /** accept estimate */
        estimateApprovalPanel.on('click', '.accept-estimate', function () {
            var id = $(this).data('id');
            var acceptUrl = '{{ route('sales.estimate.accept', [ 'estimate'=>'ID']) }}';
            acceptUrl = acceptUrl.replace('ID', id);
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action! Are you sure want to mark this estimate as accepted?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4aba45',
                confirmButtonText: 'Yes, Accepted!'
            }).then(function (isConfirm) {
                if (isConfirm.value) {
                    $.ajax({
                        url: acceptUrl,
                        type: 'POST',
                        data: {'_token': '{{ csrf_token() }}'},
                        success: function (result) {
                            swal(
                                'Mark as Accepted!',
                                'Estimate mark as accepted successfully!',
                                'success'
                            );
                            setTimeout(location.reload(), 300);
                        }
                    });
                }
            });
        });

        /** accept estimate */
        estimateApprovalPanel.on('click', '.decline-estimate', function () {
            var id = $(this).data('id');
            var declineUrl = '{{ route('sales.estimate.decline', [ 'estimate'=>'ID']) }}';
            declineUrl = declineUrl.replace('ID', id);
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action! Are you sure want to mark this estimate as declined?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4aba45',
                confirmButtonText: 'Yes, Declined!'
            }).then(function (isConfirm) {
                if (isConfirm.value) {
                    $.ajax({
                        url: declineUrl,
                        type: 'POST',
                        data: {'_token': '{{ csrf_token() }}'},
                        success: function (result) {
                            swal(
                                'Mark as Declined!',
                                'Estimate mark as declined successfully!',
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