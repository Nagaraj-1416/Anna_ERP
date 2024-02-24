@extends('layouts.master')
@section('title', 'Sales Commission - Stock Shortages')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4>Stock Shortages of {{ $rep->name }}</h4>
                    <br />
                    <div class="form-body">
                        <table class="ui celled structured table collapse-table">
                            <thead>
                                <tr>
                                    <th>Allocation Details</th>
                                    <th>Items</th>
                                    <th>Qty</th>
                                    <th>Rate</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stockShortagesItems as $stockShortagesItem)
                                <tr>
                                    <td>
                                        <a target="_blank" href="{{ route('sales.allocation.show', $stockShortagesItem->stockShortage->dailySale) }}">
                                            {{ $stockShortagesItem->stockShortage->dailySale->code }}
                                        </a>
                                        <br />
                                        {{ $stockShortagesItem->stockShortage->dailySale->route->name }}
                                    </td>
                                    <td>{{ $stockShortagesItem->product->name }}</td>
                                    <td>{{ $stockShortagesItem->qty }}</td>
                                    <td>{{ $stockShortagesItem->rate }}</td>
                                    <td class="text-right">{{ number_format($stockShortagesItem->amount, 2) }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td class="text-right" colspan="4">
                                        <b>TOTAL</b>
                                    </td>
                                    <td class="text-right"><b>{{ number_format($stockShortagesTotal, 2) }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
