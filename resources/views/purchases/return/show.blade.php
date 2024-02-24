@extends('layouts.master')
@section('title', 'Return Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Purchase') !!}
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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-right">
                                <a href="#"
                                   class="btn waves-effect waves-light btn-inverse btn-sm">
                                    <i class="fa fa-file-pdf-o"></i> Export to PDF
                                </a>
                                <a target="_blank" href="#"
                                   class="btn waves-effect waves-light btn-inverse btn-sm">
                                    <i class="fa fa-print"></i> Print
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="row custom-top-margin">
                        <div class="col-md-9">
                            <div class="card card-body printableArea">
                                <h3><b>PURCHASE RETURN</b></h3>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><b>Returned date:</b> {{ $return->date }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive" style="clear: both;">
                                            <table class="table color-table inverse-table">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" style="width: 3%;">#</th>
                                                    <th>Items & Description</th>
                                                    <th class="text-center" style="width: 25%;">Returned Quantity</th>
                                                    <th class="text-right" style="width: 25%;">Returned Rate</th>
                                                    <th class="text-right" style="width: 25%;">Returned Amount</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if($items)
                                                    @foreach($items as $itemKey => $item)
                                                        <tr>
                                                            <td class="text-center">{{ $itemKey+1 }}</td>
                                                            <td>
                                                                {{ $item->product->name }}
                                                            </td>
                                                            <td class="text-center">{{ $item->returned_qty }}</td>
                                                            <td class="text-right">{{ number_format($item->returned_rate, 2) }}</td>
                                                            <td class="text-right">{{ number_format(($item->returned_qty*$item->returned_rate), 2) }}</td>
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
                                                    <td width="80%" class="text-right"><h4><b>Total Returned</b></h4></td>
                                                    <td class="text-right custom-td-btm-border "><h4>
                                                            <b>{{ number_format($items->sum('returned_amount'), 2) }}</b></h4></td>
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
                                        <p><b>Company :</b> {{ $return->company->name or 'None' }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><b>Prepared by :</b> {{ $return->preparedBy->name or 'None'}}</p>
                                    </div>
                                    <div class="col-md-6"></div>
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
    @include('purchases.bill.payment.script', ['modal' => $return])
    @include('_inc.document.script', ['model' => $return])
@endsection