@extends('layouts.master')
@section('title', 'Product Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline-inverse">
            <div class="card-header">
                <h4 class="m-b-0 text-white">{{ $product->code }}</h4>
            </div>
            <div class="card-body">
                <!-- action buttons -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-left">
                            <a href="{{ route('setting.product.edit', [$product]) }}" class="btn waves-effect waves-light btn-primary btn-sm" target="_blank">
                                <i class="fa fa-pencil"></i> Edit
                            </a>
                        </div>
                        <div class="pull-right"></div>
                    </div>
                </div>

                <div class="row custom-top-margin">
                    <div class="col-md-9">
                        <div class="card card-body">
                            <h3>
                                <b>{{ $product->name }} {{ $product->tamil_name ? '('. $product->tamil_name .')' : '' }}</b>
                                <span class="pull-right text-muted">
                                    @if($product->is_active == 'Yes')
                                        {{ 'Active' }}
                                    @else
                                        {{ 'Inactive' }}
                                    @endif
                                </span>
                            </h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="card blog-widget">
                                        <div class="card-body">
                                            <div class="blog-image">
                                                <img src="{{route('setting.product.image', [$product])}}" alt="img"
                                                     class="img-responsive">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-3 col-xs-6"> <strong>Type</strong>
                                            <br>
                                            <p class="text-muted">{{ $product->type or 'None' }}</p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3 col-xs-6 b-r"> <strong>Measurement</strong>
                                            <br>
                                            <p class="text-muted">{{ $product->measurement->name or 'None' }}</p>
                                        </div>
                                        <div class="col-md-3 col-xs-6 b-r"> <strong>Min stock level</strong>
                                            <br>
                                            <p class="text-muted">{{ $product->min_stock_level or 'None' }}</p>
                                        </div>
                                        <div class="col-md-3 col-xs-6"> <strong>Inventory account</strong>
                                            <br>
                                            <p class="text-muted">{{ $product->inventoryAccount->name or 'None' }}</p>
                                        </div>
                                    </div>

                                    @if($product->type != 'Raw Material')
                                    <h5 class="box-title box-title-with-margin">Sales Details</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3 col-xs-6 b-r"> <strong>Wholesale price</strong>
                                            <br>
                                            <p class="text-muted">{{ $product->wholesale_price or 'None' }}</p>
                                        </div>
                                        <div class="col-md-3 col-xs-6 b-r"> <strong>Retail price</strong>
                                            <br>
                                            <p class="text-muted">{{ $product->retail_price or 'None' }}</p>
                                        </div>
                                        <div class="col-md-3 col-xs-6 b-r"> <strong>Distribution price</strong>
                                            <br>
                                            <p class="text-muted">{{ $product->distribution_price or 'None' }}</p>
                                        </div>
                                        <div class="col-md-3 col-xs-6"> <strong>Income account</strong>
                                            <br>
                                            <p class="text-muted">{{ $product->incomeAccount->name or 'None' }}</p>
                                        </div>
                                    </div>
                                    @endif

                                    <h5 class="box-title box-title-with-margin">Purchase Details</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3 col-xs-6 b-r"> <strong>Buying price</strong>
                                            <br>
                                            <p class="text-muted">{{ $product->buying_price or 'None' }}</p>
                                        </div>
                                        <div class="col-md-3 col-xs-6"> <strong>Expense account</strong>
                                            <br>
                                            <p class="text-muted">{{ $product->expenseAccount->name or 'None' }}</p>
                                        </div>
                                    </div>

                                    <h5 class="box-title box-title-with-margin">Opening Details</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3 col-xs-6 b-r"> <strong>Opening cost</strong>
                                            <br>
                                            <p class="text-muted">{{ $product->opening_cost or 'None' }}</p>
                                        </div>
                                        <div class="col-md-3 col-xs-6"> <strong>Opening cost at</strong>
                                            <br>
                                            <p class="text-muted">{{ $product->opening_cost_at or 'None' }}</p>
                                        </div>
                                        <div class="col-md-3 col-xs-6"> <strong>Opening QTY</strong>
                                            <br>
                                            <p class="text-muted">{{ $product->opening_qty or 'None' }}</p>
                                        </div>
                                        <div class="col-md-3 col-xs-6"> <strong>Opening QTY at</strong>
                                            <br>
                                            <p class="text-muted">{{ $product->opening_qty_at or 'None' }}</p>
                                        </div>
                                    </div>

                                    <h5 class="box-title box-title-with-margin">Notes</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12 col-xs-6">
                                            <p class="text-muted">{{ $product->notes or 'None' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h3><b>STOCK HISTORIES (IN / OUT)</b> <span class="pull-right">Total Histories: 0</span></h3>
                                <hr>
                                <div class="table-responsive">
                                    <table class="table color-table muted-table">
                                        <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Quantity</th>
                                            <th>Transaction</th>
                                            <th>Description</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($stock) && count($stock->histories))
                                            @foreach($stock->histories as $history)
                                                <tr>
                                                    <td>{{ $history->trans_date or 'None' }}</td>
                                                    <td>{{ $history->quantity or 'None' }}</td>
                                                    <td>{{ $history->transaction or 'None' }}</td>
                                                    <td>{{ $history->trans_description or 'None' }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5">
                                                    <span class="text-muted">No Histories Found.</span>
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h3><b>ATTACHMENTS</b></h3>
                                <hr>
                                @include('_inc.document.view', ['model' => $product])
                            </div>
                        </div>

                    </div>
                    <div class="col-md-3">
                        <!-- recent comments -->
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Barcode</h4>
                                <hr>
                                @include('_inc.barcode.index', ['product' => $product])
                            </div>
                        </div>

                        <!-- recent comments -->
                        <div class="card">
                            <div class="card-body">
                                @include('general.comment.index', ['model' => $product])
                            </div>
                        </div>

                        <!-- recent audit logs -->
                        <div class="card">
                            <div class="card-body">
                                @include('general.log.index', ['model' => $product, 'modelName' => 'Product'])
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
    @include('general.comment.script', ['modelId' => $product->id])
    @include('_inc.document.script', ['model' => $product])
@endsection
