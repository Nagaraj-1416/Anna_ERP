@extends('layouts.master')
@section('title', 'Price Book Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Price Book Details') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Price Book History</h4>
                </div>
                <div class="card-body">
                    <div class="row custom-top-margin">
                        <div class="col-md-12">
                            <div class="card card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Price Book</strong>
                                                <br>
                                                <p class="text-muted">
                                                    <a target="_blank" href="{{ route('setting.price.book.show', $priceHistory->priceBook) }}">
                                                        {{ $priceHistory->priceBook->name }}
                                                    </a>
                                                </p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Date</strong>
                                                <br>
                                                <p class="text-muted">{{ carbon($priceHistory->date)->format('d F Y, h:i:s A') }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Updated By</strong>
                                                <br>
                                                <p class="text-muted">{{ $priceHistory->updatedBy->name or 'None' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h3><b>OLD PRICES</b> <span
                                                class="pull-right">Total Prices: {{ $priceHistory->items()->count() }}</span>
                                    </h3>
                                    <hr>
                                    <div class="table-responsive">
                                        <table class="table color-table muted-table">
                                            <thead>
                                            <tr>
                                                <th class="text-left">Products</th>
                                                <th style="width:20%">Quantity range</th>
                                                <th class="text-right" style="width:10%">Prices</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($priceHistory->items()->get() as $price)
                                                <tr>
                                                    <td>{{ $price->product->name ?? 'None' }}</td>
                                                    <td>{{ $price->range_start_from ?? 'None'}} - {{ $price->range_end_to ?? 'None'}}</td>
                                                    <td class="text-right">{{ $price->price ? number_format($price->price, 2) : 'None'}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
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
@endsection