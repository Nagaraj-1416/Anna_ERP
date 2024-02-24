@extends('layouts.master')
@section('title', 'Receive GRN')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Purchase') !!}
@endsection
@section('content')
    <div class="row" ng-controller="GrnController">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">GRN Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($grn, ['url' => route('purchase.grn.do.receive', $grn), 'method' => 'PATCH']) !!}

                        <div class="card card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive" style="clear: both;">
                                        <table class="table color-table">
                                            <thead>
                                            <tr>
                                                <th class="text-center" style="width: 3%;"></th>
                                                <th >Items & Description</th>
                                                <th class="text-center" style="width: 10%;">Requested</th>
                                                <th class="text-center" style="width: 10%;">Issued</th>
                                                <th class="text-center" style="width: 10%;">Pending</th>
                                                <th class="text-center" style="width: 10%;">Received</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(count($items))
                                                @foreach($items as $itemKey => $item)
                                                    <tr class="item-row">
                                                        <td class="text-center">
                                                            <div class="demo-checkbox" style="width: 20px !important;">
                                                                <input type="checkbox" id="{{ 'md_checkbox_29_' . $itemKey }}"
                                                                       name="products[product_id][{{ $item->product_id }}]"
                                                                       class="item-chk-col-cyan" value="{{ $item->product_id }}"/>
                                                                <label style="min-width: 35px !important;" for="{{ 'md_checkbox_29_' . $itemKey }}"></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            {{ $item->product->name }}<br />
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <small>
                                                                        @if($item->batch_no)
                                                                            <b>Batch no:</b> {{ $item->batch_no or 'None' }}<br />
                                                                        @endif
                                                                        @if($item->manufacture_date)
                                                                            <b>Manufacture:</b> {{ $item->manufacture_date or 'None' }}<br />
                                                                        @endif
                                                                        @if($item->expiry_date)
                                                                            <b>Expiry date:</b> {{ $item->expiry_date or 'None' }}<br />
                                                                        @endif
                                                                        @if($item->packing_type)
                                                                            <b>Packing type:</b> {{ $item->packing_type or 'None' }}
                                                                        @endif
                                                                    </small>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <small>
                                                                        @if($item->grade)
                                                                            <b>Grade:</b> {{ $item->grade or 'None' }}<br />
                                                                        @endif
                                                                        @if($item->color)
                                                                            <b>Color:</b> {{ $item->color or 'None' }}<br />
                                                                        @endif
                                                                        @if($item->brand)
                                                                            <b>Brand:</b> {{ $item->brand or 'None' }}<br />
                                                                        @endif
                                                                        @if($item->no_of_bags)
                                                                            <b>No of bags:</b> {{ $item->no_of_bags or 'None' }}
                                                                        @endif
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">{{ $item->quantity }}</td>
                                                        <td class="text-center">{{ $item->issued_qty }}</td>
                                                        <td class="text-center">{{ $item->pending_qty }}</td>
                                                        <td class="text-right">
                                                            <input type="text" class="form-control text-center item-received-qty" name="products[received_qty][{{ $item->product_id }}]" value="{{ old('_token') ? old('products.received_qty.'.$item->product_id): '' }}" placeholder="received" readonly/>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="box-title">Other Details</h4>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            {!! form()->bsText('unloaded_by', 'Unloaded By', null, ['placeholder' => 'unloaded by']) !!}
                                        </div>
                                        @if($grn->transfer_by == "OwnVehicle")
                                        <div class="col-md-3">
                                            {!! form()->bsText('odo_ends_at', 'ODO Ends at', null, ['placeholder' => 'ends at']) !!}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! form()->bsSubmit('Receive') !!}
                        {!! form()->bsCancel('Cancel', 'purchase.grn.show', $grn) !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script src="{{ asset('js/vendor/object-helper.js') }}"></script>
    <script>
        app.controller('GrnController', function ($scope) {

            $scope.formElement = {
                itemReceivedQty: $('.item-received-qty'),
                itemCheck: $('.item-chk-col-cyan')
            };

            $scope.formElement.itemCheck.change(function (e) {
                e.preventDefault();
                if($(this).is(":checked")) {
                    var parent = $(this).parents(".item-row");
                    parent.find(".item-received-qty").removeAttr("readonly");

                }else{
                    var parent = $(this).parents(".item-row");
                    parent.find(".item-received-qty").val(null);
                    parent.find(".item-received-qty").attr("readonly", "readonly");
                }
            });

        });
    </script>
@endsection
