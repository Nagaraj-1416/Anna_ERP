@extends('layouts.master')
@section('title', 'Restore Actual Qty')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Actual Qty Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($allocation, ['url' => route('sales.allocation.restore.actual.qty', $allocation), 'method' => 'PATCH', 'enctype' => 'multipart/form-data']) !!}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <p><b>Allocation from :</b> {{ $allocation->from_date }}</p>
                                <input type="hidden" name="allocation_start" value="{{ $allocation->from_date }}">
                            </div>
                            <div class="col-md-8">
                                <p><b>Allocation to :</b> {{ $allocation->to_date }}</p>
                                <input type="hidden" name="allocation_end" value="{{ $allocation->to_date }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <p><b>Allocated Route :</b> {{ $allocation->route->name }}
                                </p>
                            </div>
                            <div class="col-md-8">
                                <p><b>Allocated Vehicle :</b> {{ $allocation->salesLocation->name }}
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <p><b>Allocated Rep :</b> {{ $allocation->rep->name.' ('.$allocation->rep->code.')' }}
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Allocated Driver :</b> {{ $allocation->driver->short_name.' ('.$allocation->driver->code.')' }}
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Labours :</b>
                                    @foreach(getAllocationLabours($allocation) as $labour)
                                        {{ $labour->short_name }}@if(!$loop->last), @endif
                                    @endforeach
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="ui table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th class="text-center">CF Qty</th>
                                            <th class="text-center">Allocated Qty</th>
                                            <th class="text-center">Sold Qty</th>
                                            <th class="text-center">Returned Qty</th>
                                            <th class="text-center">Stocks Confirmed By Rep</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if($items)
                                        @foreach($items as $item)
                                            <tr>
                                                <td>{{ $item->product->name }}</td>
                                                <td class="text-center">{{ $item->cf_qty }}</td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-center">{{ $item->sold_pro_qty }}</td>
                                                <td class="text-center">{{ $item->returned_qty }}</td>
                                                <td class="text-center">
                                                    {{ $item->actual_stock }}
                                                    <input type="hidden" class="form-control" name="products[]" value="{{ $item->product_id }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>
                    {!! form()->bsSubmit('Restore', 'btn btn-success waves-effect waves-light m-r-10', 'Restore', 'submit') !!}
                    {!! form()->bsCancel('Cancel', 'sales.allocation.sheet', [$allocation]) !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script>
        var repDropDown = $('.exp-type-drop-down');
        repDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });

        var driverDropDown = $('.driver-drop-down');
        driverDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });

        var labourDropDown = $('.labour-drop-down');
        labourDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });
    </script>
@endsection
