@extends('layouts.master')
@section('title', 'Inquiry Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">{{ $inquiry->code }}</h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                @can('edit', $inquiry)
                                    <a href="{{ route('sales.inquiries.edit', [$inquiry]) }}"
                                       class="btn waves-effect waves-light btn-primary btn-sm"
                                       target="_blank">
                                        <i class="fa fa-pencil"></i> Edit
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
                                    <b>INQUIRY</b> |
                                    <small class="{{ statusLabelColor($inquiry->status) }}">
                                        {{ $inquiry->status }}
                                    </small>
                                    <span class="pull-right">#{{ $inquiry->code }}</span></h3>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-left">
                                            @if($inquiry->company)
                                                <address>
                                                    <h4><b class="text-danger">{{ $inquiry->company->name }}</b></h4>
                                                    @include('_inc.address.view', ['address' => $inquiry->company->addresses->first()])
                                                </address>
                                            @endif
                                        </div>
                                        <div class="pull-right text-right">
                                            <address>
                                                <h4 class="font-bold">{{ $customer->display_name or '' }}</h4>
                                                @include('_inc.address.view', ['address' => $address])
                                            </address>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="pull-left">
                                            <p><b>Inquiry Date :</b> {{ $inquiry->inquiry_date }}</p>
                                        </div>
                                        <div class="pull-right text-right">

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
                                                    <th class="text-right">Delivery Date</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if($inquiry->product_items->count())
                                                    @foreach($inquiry->product_items as $itemKey => $item)
                                                        <tr>
                                                            <td class="text-center">{{ $itemKey + 1 }}</td>
                                                            <td>
                                                                {{ $item['name'] or ''}}<br>
                                                                <small class="text-muted">{{ $item['notes'] or '' }}</small>
                                                            </td>
                                                            <td class="text-center">{{ $item['quantity'] or '' }}</td>
                                                            <td class="text-right">{{ $item['delivery_date'] or '' }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                @endif
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
                                               href="{{ route('sales.customer.show', [$inquiry->customer]) }}">
                                                {{ $inquiry->customer->display_name or 'None' }}
                                            </a>
                                        </p>
                                    </div>
                                    {{--<div class="col-md-3">
                                        <p><b>Business type :</b> {{ $inquiry->businessType->name or 'None' }}</p>
                                    </div>--}}
                                    <div class="col-md-6">
                                        <div class="order-notes">
                                            <h5>Notes</h5>
                                            <small class="text-muted">{{ $inquiry->description }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h3><b>ATTACHMENTS</b></h3>
                                    <hr>
                                    @include('_inc.document.view', ['model' => $inquiry])
                                </div>
                            </div>
                        </div>

                        <!-- recent logs -->
                        <div class="col-md-3">
                            @if($inquiry->status == 'Open')
                                <div class="card border-warning text-center estimate-approval-panel">
                                    <div class="card-body">
                                        <h3 class="card-title text-danger"><i class="fa fa-clock-o"></i> Conversion
                                            Pending</h3>
                                        <p class="card-subtitle"> This inquiry is waiting for conversion. You can take
                                            further actions once inquiry convert to <code>Estimate</code> or
                                            <code>Order</code>.</p>
                                        @can('create', new \App\Estimate())
                                            <a class="btn btn-danger"
                                               href="{{ route('sales.estimate.create') }}?inquiry={{ $inquiry->id }}"
                                               data-id="{{ $inquiry->id }}">
                                                <i class="fa fa-check"></i> To Estimate
                                            </a>
                                        @endcan
                                        @can('create', new \App\SalesOrder())
                                            <a class="btn btn-danger"
                                               href="{{ route('sales.order.create') }}?inquiry={{ $inquiry->id }}"
                                               data-id="{{ $inquiry->id }}">
                                                <i class="fa fa-check"></i> To Order
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            @endif
                            @if($inquiry->status != 'Open')
                                <div class="card border-success text-center estimate-approval-panel">
                                    <div class="card-body">
                                        <h3 class="card-title text-success"><i
                                                    class="fa fa-clock-o"></i> {{ $inquiry->status }}</h3>
                                        <p class="card-subtitle"> This inquiry
                                            is {{ strtolower($inquiry->status) }} </p>

                                        @php
                                            $link = '#';
                                            $converted = $inquiry->converted;
                                            if ($converted){
                                                $convertedModel = class_basename($converted);
                                                if ($convertedModel == 'Estimate'){
                                                    $link = route('sales.estimate.show', $converted->id);
                                                }else if ($convertedModel == 'SalesOrder'){
                                                    $link = route('sales.order.show', $converted->id);
                                                }
                                            }
                                        @endphp
                                        <a class="btn btn-success" target="_blank" href="{{ $link }}"
                                           data-id="{{ $inquiry->id }}">
                                            <i class="fa fa-file"></i> Open
                                        </a>
                                    </div>
                                </div>
                        @endif

                        <!-- recent comments -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.comment.index', ['model' => $inquiry])
                                </div>
                            </div>

                            <!-- recent audit logs -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.log.index', ['model' => $inquiry, 'modelName' => 'Inquiry'])
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
    @include('general.comment.script', ['modelId' => $inquiry->id])
    @include('_inc.document.script', ['model' => $inquiry])
@endsection