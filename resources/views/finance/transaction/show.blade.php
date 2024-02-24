@extends('layouts.master')
@section('title', 'Transaction Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Transaction Details') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">{{ $trans->code }}</h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            {{--@if($trans->category == 'Manual')--}}
                                <div class="pull-left">
                                    <a href="{{ route('finance.trans.edit', [$trans]) }}"
                                       class="btn waves-effect waves-light btn-primary btn-sm" target="_blank">
                                        <i class="fa fa-pencil"></i> Edit
                                    </a>
                                    <button class="btn btn-danger btn-sm trans-delete">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </div>
                                <div class="pull-right">
                                    <a href="{{ route('finance.trans.export', [$trans]) }}"
                                       class="btn waves-effect waves-light btn-inverse btn-sm">
                                        <i class="fa fa-file-pdf-o"></i> Export to PDF
                                    </a>
                                    <a href="{{ route('finance.trans.print', [$trans]) }}"
                                       class="btn waves-effect waves-light btn-inverse btn-sm">
                                        <i class="fa fa-print"></i> Print
                                    </a>
                                </div>
                            {{--@endif--}}
                        </div>
                    </div>

                    <div class="row custom-top-margin">
                        <div class="col-md-9">
                            <div class="card card-body">
                                <h3>
                                    <b>TRANSACTION</b> |
                                    <small >
                                        {{ $trans->type }}
                                    </small>
                                    <span class="pull-right">#{{ $trans->code }}</span></h3>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><b>Date :</b> {{ $trans->date }}</p>
                                    </div>
                                </div>
                                {{--<div class="row">
                                    <div class="col-md-4">
                                        <p><b>Amount :</b> {{ number_format($trans->amount, 2) }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><b>Category :</b> {{ $trans->category or 'None' }}</p>
                                    </div>
                                </div>--}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <p><b>Transaction type :</b> {{ $trans->txType->name ?? 'None' }} </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><b>Customer :</b>
                                            @if($trans->customer)
                                                <a target="_blank"
                                                   href="{{ route('sales.customer.show', [$trans->customer]) }}">
                                                    {{ $trans->customer->display_name }}
                                                </a>
                                            @else
                                                None
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><b>Supplier :</b>
                                            @if($trans->supplier)
                                                <a target="_blank"
                                                   href="{{ route('purchase.supplier.show', [$trans->supplier]) }}">
                                                    {{ $trans->supplier->display_name }}
                                                </a>
                                            @else
                                                None
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="order-notes">
                                            <h5>Narration</h5>
                                            <small class="text-muted">{{ $trans->auto_narration ? '(Auto) '.$trans->auto_narration : '' }}</small><br />
                                            <small class="text-muted">{{ $trans->manual_narration ? '(Manual) '.$trans->manual_narration : '' }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {{--<div class="order-notes">--}}
                                            {{--<h5>Notes</h5>--}}
                                            {{--<small class="text-muted">{{ $trans->notes or 'None' }}</small>--}}
                                        {{--</div>--}}
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h3><b>TRANSACTION RECORDS</b> <span
                                                class="pull-right">Total: {{ count($records) }}</span></h3>
                                    <hr>
                                    <div class="table-responsive">
                                        <table class="table color-table inverse-table">
                                            <thead>
                                                <tr>
                                                    <th width="70%">Account</th>
                                                    <th width="15%" class="text-right">Debit</th>
                                                    <th width="15%" class="text-right">Credit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($records as $record)
                                                    <tr>
                                                        <td>{{ $record->account->name or 'None' }} ({{ $trans->auto_narration }})</td>

                                                        <td class="text-right">{{ number_format(($record->type == 'Debit') ? $record->amount : 0.00 , 2)}}</td>
                                                        <td class="text-right">{{ number_format(($record->type == 'Credit') ? $record->amount : 0.00, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td class="text-right"><h4><b>Total </b></h4></td>
                                                    <td class="text-right"><h4><b>{{ number_format($trans->records->where('category', 'Account')->where('type', 'Debit')->sum('amount'), 2) }}</b></h4></td>
                                                    <td class="text-right"><h4><b>{{ number_format($trans->records->where('category', 'Account')->where('type', 'Credit')->sum('amount'), 2) }}</b></h4></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h3><b>ATTACHMENTS</b> <span
                                                class="pull-right">Total: {{ count($trans->documents) }}</span></h3>
                                    <hr>
                                    @include('_inc.document.view', ['model' => $trans])
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">

                            <!-- recent comments -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.comment.index', ['model' => $trans])
                                </div>
                            </div>

                            <!-- recent audit logs -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.log.index', ['model' => $trans, 'modelName' => 'Transaction'])
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
    @include('general.comment.script', ['modelId' => $trans->id])
    @include('_inc.document.script', ['model' => $trans])

    <script>
        $(document).ready(function () {
            var $delBtn = $('.trans-delete');
            var deleteRoute = '{{ route('finance.trans.delete', $trans) }}';
            var indexRoute = '{{ route('finance.trans.index') }}';
            $delBtn.click(function (e) {
                e.preventDefault();
                Swal({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this transaction!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor : '#fc4b6c',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: deleteRoute,
                            type: 'DELETE',
                            data: {_token : '{{ csrf_token() }}'},
                            success: function(response) {
                                if (response.success){
                                    Swal(
                                        'Deleted!',
                                        'Your transaction has been deleted.',
                                        'success'
                                    );
                                    setTimeout(function () {
                                        window.location.href = indexRoute;
                                    }, 800);
                                }else{
                                    Swal(
                                        'Failed!',
                                        'Your transaction deleted failed.',
                                        'error'
                                    )
                                }
                            }
                        });
                    }
                })
            });
        });
    </script>
@endsection
