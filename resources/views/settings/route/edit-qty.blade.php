@extends('layouts.master')
@section('title', 'Update Default Qty')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Products Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($route, ['url' => route('setting.route.update.qty', $route), 'method' => 'PATCH']) !!}
                    <div class="row m-t-10">
                        <div class="col-md-12">
                            <h6>Enter product default quantity and press update</h6>
                            <hr>
                            <table class="ui structured table collapse-table">
                                <thead>
                                <tr>
                                    <th class="text-left">PRODUCTS</th>
                                    <th style="width: 20%;" class="text-center">DEFAULT QTY</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($products)
                                    @foreach($products as $keyPro => $product)
                                        <tr>
                                            <td class="text-left">
                                                <a target="_blank" href="{{ route('setting.product.show', $product) }}">
                                                    {{ $product->name }}
                                                </a>
                                            </td>
                                            <td style="width: 20%;" class="text-center">
                                                <input type="text" value="{{ $product->pivot->default_qty }}" class="form-control text-center" name="products[{{ $product->id }}][default_qty]" />
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    {!! form()->bsSubmit('Update') !!}
                    {!! form()->bsCancel('Cancel', 'setting.route.show', $route) !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
