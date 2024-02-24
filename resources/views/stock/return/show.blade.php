@extends('layouts.master')
@section('title', 'Stock Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Stock') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">{{ $stock->product->code }}</h4>
                </div>
                <div class="card-body">
                    @if($stock->type == 'Manual')
                        <!-- action buttons -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-left">
                                    <a href="{{ route('stock.edit', $stock->id) }}" class="btn waves-effect waves-light btn-primary btn-sm" target="_blank">
                                        <i class="fa fa-pencil"></i> Update Min Stock Level
                                    </a>
                                    {{--<a href="#" class="btn waves-effect waves-light btn-danger btn-sm" target="_blank">
                                        <i class="fa fa-remove"></i> Delete
                                    </a>--}}
                                </div>
                                <div class="pull-right"></div>
                            </div>
                        </div>
                        <hr>
                    @endif

                    <div class="row">
                        <div class="col-md-9">
                            <div class="card card-body">
                                <h3>
                                    <b>
                                        <a target="_blank" href="{{ route('setting.product.show', [$stock->product]) }}">{{ $stock->product->name }}</a></b>
                                    <span class="pull-right text-muted">
                                        {{ $stock->type }}
                                    </span>
                                </h3>
                                <hr>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="card blog-widget">
                                            <div class="card-body">
                                                <div class="blog-image">
                                                    <img src="{{route('setting.product.image', [$stock->product])}}" alt="img" class="img-responsive">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md-3 col-xs-6"> <strong>Available stock</strong>
                                                <br>
                                                <p class="text-muted">{{ $stock->available_stock or 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6"> <strong>Minimum stock level</strong>
                                                <br>
                                                <p class="text-muted">{{ $stock->min_stock_level or 'None' }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 col-xs-6 b-r"> <strong>Category</strong>
                                                <br>
                                                <p class="text-muted">{{ $stock->category or 'None'}}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 b-r"> <strong>Stock available at</strong>
                                                <br>
                                                <p class="text-muted">{{ $stock->store->name or 'None'}}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6"> <strong>Vehicle</strong>
                                                <br>
                                                <p class="text-muted">{{ $stock->vehicle->vehicle_no or 'None'}}</p>
                                            </div>
                                        </div>

                                        @if($stock->notes)
                                            <h5 class="box-title box-title-with-margin">Notes</h5>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-12 col-xs-6">
                                                    <p class="text-muted">{{ $stock->notes or 'None' }}</p>
                                                </div>
                                            </div>
                                        @endif
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
                                                <th>Type</th>
                                                <th class="text-center">IN Qty</th>
                                                <th class="text-center">OUT Qty</th>
                                                <th class="text-right">Rate</th>
                                                <th class="text-right">Amount</th>
                                                <th>Production</th>
                                                <th>Shop</th>
                                                <th>Description</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($histories))
                                                    @foreach($histories as $history)
                                                        <tr>
                                                            <td>{{ $history->trans_date or 'None' }}</td>
                                                            <td>{{ $history->type or 'None' }}</td>
                                                            <td class="text-center">
                                                                @if($history->transaction == 'In')
                                                                    <span class="text-green">{{ $history->quantity }}</span>
                                                                @else
                                                                    <span>0</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                @if($history->transaction == 'Out')
                                                                    <span class="text-danger">{{ $history->quantity }}</span>
                                                                @else
                                                                    <span>0</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-right">{{ $history->rate }}</td>
                                                            <td class="text-right">{{ number_format($history->quantity * $history->rate)  }}</td>
                                                            <td>{{ $history->productionUnit->name or 'N/A' }}</td>
                                                            <td>{{ $history->salesLocation->name or 'N/A' }}</td>
                                                            <td>{{ $history->trans_description or 'None' }}</td>
                                                        </tr>
                                                    @endforeach
                                                    <tr style="border-top: 3px solid #cacaca">
                                                        <td colspan="2" class="text-right"><b>TOTAL</b></td>
                                                        <td class="text-center text-green table-success"><b>{{ $inTotal }}</b></td>
                                                        <td class="text-center text-danger table-danger"><b>{{ $outTotal }}</b></td>
                                                        <td colspan="5"></td>
                                                    </tr>
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
                        </div>
                        <div class="col-md-3">

                            <!-- recent comments -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.comment.index', ['model' => $stock])
                                </div>
                            </div>

                            <!-- recent audit logs -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.log.index', ['model' => $stock, 'modelName' => 'Stock'])
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection