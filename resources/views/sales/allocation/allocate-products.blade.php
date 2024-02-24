@extends('layouts.master')
@section('title', 'Add Products')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Add Products to Allocation</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($allocation, ['url' => route('sales.allocation.store.products', $allocation), 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
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
                                <h6>Choose stocks that you want to allocation to above allocation</h6>
                                <hr>
                                <table class="ui structured table collapse-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 3%;"></th>
                                            <th class="text-left">STOCK ITEMS</th>
                                            <th style="width: 20%;" class="text-center">AVAILABLE STOCK IN STORE</th>
                                            <th style="width: 20%;" class="text-center">ISSUE QTY</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if($stocks)
                                        @foreach($stocks as $keyStock => $stock)
                                            <tr>
                                                <td style="width: 3%;">
                                                    <div class="demo-checkbox" style="width: 20px;">
                                                        <input type="checkbox" id="{{ 'md_checkbox_29_' . $stock->id }}"
                                                               name="allocates[stock_id][{{ $stock->id }}]"
                                                               class="chk-col-cyan allocate-check" {{ old() && old('allocates.stock_id.'.$stock->id) ? 'checked' : '' }}>
                                                        <label for="{{ 'md_checkbox_29_' . $stock->id }}"></label>
                                                        <input type="hidden" value="{{ $stock->product_id }}" class="form-control text-center" name="allocates[product_id][{{ $stock->id }}]" />
                                                        <input type="hidden" value="{{ $stock->store_id }}" class="form-control text-center" name="allocates[store_id][{{ $stock->id }}]" />
                                                    </div>
                                                </td>
                                                <td class="text-left">
                                                    <a target="_blank" href="{{ route('stock.show', $stock) }}">
                                                        {{ $stock->product->name }}
                                                    </a>
                                                </td>
                                                <td style="width: 20%;" class="text-center">
                                                    {{ $stock->available_stock }}
                                                </td>
                                                <td style="width: 20%;" class="text-center">
                                                    <input type="hidden" value="{{ $stock->available_stock }}" class="form-control text-center" name="allocates[available_qty][{{ $stock->id }}]" />
                                                    <div class="form-group {{ $errors->has('allocates.issue_qty.'.$stock->id) ? 'has-danger' : '' }}">
                                                        <input placeholder="issue qty" type="text" value="" class="form-control text-center" name="allocates[issue_qty][{{ $stock->id }}]" />
                                                        <p class="form-control-feedback">{{ ($errors->has('allocates.issue_qty.'.$stock->id) ? $errors->first('allocates.issue_qty.'.$stock->id) : '') }}</p>
                                                    </div>
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
                    {!! form()->bsSubmit('Submit', 'btn btn-success waves-effect waves-light m-r-10', 'Submit', 'submit') !!}
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
    </script>
@endsection
