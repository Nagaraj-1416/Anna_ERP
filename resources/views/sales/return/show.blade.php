@extends('layouts.master')
@section('title', 'Return Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Return Details</h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    {{--<div class="row">
                        <div class="col-md-12">
                            <div class="pull-left"></div>
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
                    </div>--}}

                    <!-- estimate summary and history -->
                    <div class="row custom-top-margin">
                        <div class="col-md-9">
                            <div class="card card-body printableArea">
                                <h3>
                                    <b>SALES RETURN</b>
                                    <span class="pull-right">#{{ $return->code }}</span></h3>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><b>Returned date:</b> {{ $return->date }}</p>
                                    </div>
                                    <div class="col-md-9">
                                        <p><b>Allocation :</b>
                                            <a target="_blank"
                                               href="{{ route('sales.allocation.show', [$return->allocation]) }}">
                                                {{ $return->allocation->code.' ('.$return->allocation->from_date.')' }}
                                            </a>
                                        </p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive" style="clear: both;">
                                            <table class="table color-table inverse-table">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th style="width: 50%;">Items & Description</th>
                                                    <th class="text-center">Quantity</th>
                                                    <th class="text-right">Sold rate</th>
                                                    <th class="text-right">Returned rate</th>
                                                    <th class="text-right">Returned amount</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(count($items))
                                                    @foreach($items as $itemKey => $item)
                                                        <tr>
                                                            <td class="text-center">{{ $itemKey+1 }}</td>
                                                            <td>
                                                                {{ $item->product->name }}<br>
                                                                <small class="text-muted">
                                                                    <b>Type :</b>{{ $item->type }}<br />
                                                                    <b>Reason :</b>{{ $item->reason }}
                                                                </small>
                                                            </td>
                                                            <td class="text-center">{{ $item->qty }}</td>
                                                            <td class="text-right">{{ number_format($item->sold_rate, 2) }}</td>
                                                            <td class="text-right">{{ number_format($item->returned_rate, 2) }}</td>
                                                            <td class="text-right">{{ number_format($item->returned_amount, 2) }}</td>
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
                                                    <td width="80%" class="text-right"><h4><b>Returned</b></h4></td>
                                                    <td class="text-right custom-td-btm-border "><h4>
                                                            <b>{{ number_format($items->sum('returned_amount'), 2) }}</b></h4></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="box-title box-title-with-margin">Resolutions</h4>
                                <table class="ui celled structured table">
                                    <thead>
                                        <tr>
                                            <th class="">Type</th>
                                            <th class="">Resolution details</th>
                                            <th class="text-right">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($return->resolutions as $keyRes => $resolution)
                                        <tr>
                                            <td>{{ $resolution->resolution }}</td>
                                            <td>
                                                @if($resolution->resolution == 'Replace')
                                                    <b>Replaced by</b>
                                                    <ul>
                                                        @foreach($return->replaces as $replace)
                                                        <li>{{ $replace->product->name }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    None
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($resolution->amount, 2) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                        {{--<tr><td colspan="3"><b>Replaced by</b></td></tr>
                                        <tr>

                                        </tr>
                                        @foreach($return->replaces as $keyRep => $replace)

                                        @endforeach--}}
                                        <tr>
                                            <td width="80%" colspan="2" class="text-right"><h4><b>Resolution total</b></h4></td>
                                            <td class="text-right custom-td-btm-border "><h4>
                                                    <b>{{ number_format($items->sum('returned_amount'), 2) }}</b></h4></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <h4 class="box-title box-title-with-margin">Other Details</h4>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p>
                                            <b>Customer :</b>
                                            <a target="_blank"
                                               href="{{ route('sales.customer.show', [$return->customer]) }}">
                                                {{ $return->customer->display_name or 'None' }}
                                            </a>
                                        </p>
                                        <p><b>Company :</b> {{ $return->company->name or 'None' }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><b>Prepared by :</b> {{ $return->preparedBy->name or 'None'}}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="order-notes">
                                            <h5>Notes</h5>
                                            <small class="text-muted">{{ $return->notes }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h3><b>ATTACHMENTS</b> <span
                                                class="pull-right">Total: {{ count($return->documents) }}</span></h3>
                                    <hr>
                                    @include('_inc.document.view', ['model' => $return])
                                </div>
                            </div>
                        </div>

                        <!-- recent logs -->
                        <div class="col-md-3">
                            <!-- recent comments -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.comment.index', ['model' => $return])
                                </div>
                            </div>

                            <!-- recent audit logs -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.log.index', ['model' => $return, 'modelName' => 'Return'])
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
    @include('general.comment.script', ['modelId' => $return->id])
    @include('_inc.document.script', ['model' => $return])
@endsection