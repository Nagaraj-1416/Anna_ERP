@extends('layouts.master')
@section('title', 'Stock Allocation Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Allocation details</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="clearfix">
                                <div class="pull-left">
                                    @if($dailyStock->status == 'Pending')
                                        {{--@if($dailyStock->sales_location == 'Van')
                                            <a href="{{ route('daily.stock.edit', [$dailyStock]) }}"
                                               class="btn waves-effect waves-light btn-primary btn-sm"
                                               target="_blank">
                                                <i class="fa fa-pencil"></i> Edit
                                            </a>
                                        @elseif($dailyStock->sales_location == 'Shop')
                                            <a href="{{ route('daily.stock.edit.shop', [$dailyStock]) }}"
                                               class="btn waves-effect waves-light btn-primary btn-sm"
                                               target="_blank">
                                                <i class="fa fa-pencil"></i> Edit
                                            </a>
                                        @endif--}}
                                        <a href="{{ route('daily.stock.change.route', [$dailyStock]) }}"
                                           class="btn waves-effect waves-light btn-primary btn-sm"
                                           target="_blank">
                                            <i class="fa fa-pencil"></i> Change Route
                                        </a>
                                        <a href="{{ route('daily.stock.change.rep', [$dailyStock]) }}"
                                           class="btn waves-effect waves-light btn-primary btn-sm"
                                           target="_blank">
                                            <i class="fa fa-pencil"></i> Change Rep
                                        </a>
                                        <button class="btn btn-danger btn-sm status-change"
                                           data-id="{{ $dailyStock->id }}" data-value="Canceled">
                                            <i class="fa fa-remove"></i> Cancel
                                        </button>
                                    @endif
                                </div>
                                <div class="pull-right"></div>
                            </div>
                            <div class="card card-body custom-top-margin">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3 col-xs-6"> <strong>Company</strong>
                                                <br>
                                                <p class="text-muted">{{ $dailyStock->company->name or 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6"> <strong>Store</strong>
                                                <br>
                                                <p class="text-muted">{{ $dailyStock->store->name or 'None' }}</p>
                                            </div>
                                            @if($dailyStock->sales_location == 'Shop')
                                                <div class="col-md-3 col-xs-6"> <strong>Sales van</strong>
                                                    <br>
                                                    <p class="text-muted">{{ $dailyStock->saleLocation->name or 'None' }}</p>
                                                </div>
                                                <div class="col-md-3 col-xs-6"> <strong>Status</strong>
                                                    <br>
                                                    <span class="{{ statusLabelColor($dailyStock->status) }}">{{ $dailyStock->status or 'None' }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        @if($dailyStock->sales_location == 'Van')
                                        <div class="row">
                                            <div class="col-md-3 col-xs-6"> <strong>Route</strong>
                                                <br>
                                                <p class="text-muted">{{ $dailyStock->route->name or 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6"> <strong>Rep</strong>
                                                <br>
                                                <p class="text-muted">{{ $dailyStock->rep->name or 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6"> <strong>Sales van</strong>
                                                <br>
                                                <p class="text-muted">{{ $dailyStock->saleLocation->name or 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6"> <strong>Status</strong>
                                                <br>
                                                <span class="{{ statusLabelColor($dailyStock->status) }}">{{ $dailyStock->status or 'None' }}</span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="clearfix">
                                        <div class="pull-left">
                                            <h3><b>ITEMS</b></h3>
                                        </div>
                                        <div class="pull-right">
                                            <a href="{{ route('daily.stock.export', [$dailyStock]) }}"
                                               class="btn waves-effect waves-light btn-inverse btn-sm">
                                                <i class="fa fa-file-pdf-o"></i> Export to PDF
                                            </a>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="table-responsive">
                                        @if($dailyStock->status == 'Pending')
                                        {!! form()->model($dailyStock, ['url' => route('daily.stock.update.items', $dailyStock), 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                                        @endif
                                        <table class="table color-table muted-table">
                                            <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th class="text-center">Available Qty in Store</th>
                                                <th class="text-center">Available Qty in Van</th>
                                                <th class="text-center">Default Qty</th>
                                                <th class="text-center">Required Qty</th>
                                                <th class="text-center">Issued Qty</th>
                                                <th class="text-center">Pending Qty</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($products))
                                                    @foreach($products as $item)
                                                        <tr>
                                                            <td>{{ $item->product->name or 'None' }}</td>
                                                            <td class="text-center">
                                                                {{ $item->available_qty_in_store }}
                                                                <input value="{{ $item->available_qty_in_store }}" type="hidden" name="stock_items[available_qty_in_store][{{ $item->id }}]" class="form-control">
                                                            </td>
                                                            <td class="text-center">{{ $item->available_qty or 'None' }}</td>
                                                            <td class="text-center">{{ $item->default_qty or 'None' }}</td>
                                                            <td class="text-center">{{ $item->required_qty or 'None' }}</td>
                                                            <td class="text-center">
                                                                @if($dailyStock->status == 'Allocated')
                                                                    {{ $item->issued_qty or 'None' }}
                                                                @else
                                                                    <div class="form-group {{ $errors->has('stock_items.issued_qty.'.$item->id) ? 'has-danger' : '' }}">
                                                                        <input type="text" style="text-align: center;" value="{{ old('stock_items.issued_qty.'.$item->id)}}" name="stock_items[issued_qty][{{ $item->id }}]" class="form-control" placeholder="issued qty" />
                                                                        <p class="form-control-feedback">{{ ($errors->has('stock_items.issued_qty.'.$item->id) ? $errors->first('stock_items.issued_qty.'.$item->id) : '') }}</p>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td class="text-center">{{ $item->pending_qty or 'None' }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="5">
                                                            <span class="text-muted">No Items Found.</span>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                        @if($dailyStock->status == 'Pending')
                                        <hr>
                                        {!! form()->bsSubmit('Submit') !!}
                                        {{ form()->close() }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">

                            <!-- recent comments -->
                            <div class="card m-t-15">
                                <div class="card-body">
                                    @include('general.comment.index', ['model' => $dailyStock])
                                </div>
                            </div>

                            <!-- recent audit logs -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.log.index', ['model' => $dailyStock, 'modelName' => 'Stock'])
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
    <script>
        $('.status-change').click(function (e) {
            var $id = $(this).data('id');
            var $status = $(this).data('value');
            var sendUrl = '{{ route('daily.stock.status.update', ['stockId' => 'ID', 'status' => 'STATUS']) }}';
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4aba45',
                confirmButtonText: 'Submit'
            }).then(function (isConfirm) {
                if (isConfirm.value) {
                    $.ajax({
                        url: sendUrl.replace('ID', $id).replace('STATUS', $status),
                        type: 'PATCH',
                        data: {'_token': '{{ csrf_token() }}'},
                        success: function (result) {
                            swal(
                                'Status Changed!',
                                'Stock allocation canceled successfully!',
                                'success'
                            ).then(function (confirm) {
                                if (confirm) {
                                    window.location.reload()
                                }
                            });
                        }
                    });
                }
            });
        })
    </script>
@endsection
