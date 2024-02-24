<div class="form-body">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('transfer_to') ? 'has-danger' : '' }}">
                <label class="control-label">Transfer to</label>
                <div class="ui fluid search normal selection dropdown drop-down {{ $errors->has('transfer_to') ? 'error' : '' }}">
                    <input name="transfer_to" type="hidden" value="{{ old('_token') ? old('transfer_to'): '' }}">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a store to transfer</div>
                    <div class="menu">
                        @foreach(storeDropDownFiltered($store) as $key => $filteredStore)
                            <div class="item" data-value="{{ $key }}">{{ $filteredStore }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('transfer_to') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('vehicle_id') ? 'has-danger' : '' }}">
                <label class="control-label">Vehicle used to transfer</label>
                <div class="ui fluid search normal selection dropdown drop-down {{ $errors->has('vehicle_id') ? 'error' : '' }}">
                    @if(isset($transfer->vehicle_id))
                        <input name="vehicle_id" type="hidden" value="{{ old('_token') ? old('vehicle_id'): $store }}">
                    @else
                        <input name="vehicle_id" type="hidden" value="{{ old('_token') ? old('vehicle_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a vehicle</div>
                    <div class="menu">
                        @foreach(vehicleDropDown() as $key => $vehicle)
                            <div class="item" data-value="{{ $key }}">{{ $vehicle }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('vehicle_id') }}</p>
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-12">
            <h6>Choose stocks that you want to transfer</h6>
            <hr>
            <table class="ui structured table collapse-table">
                <thead>
                    <tr>
                        <th style="width: 3%;"></th>
                        <th class="text-left">STOCK DETAILS</th>
                        <th style="width: 20%;" class="text-center">REORDER LEVEL</th>
                        <th style="width: 20%;" class="text-center">AVAILABLE STOCK</th>
                        <th style="width: 20%;" class="text-center">TRANSFER STOCK</th>
                    </tr>
                </thead>
                <tbody>
                    @if($stocks)
                        @foreach($stocks as $keyStock => $stock)
                            <tr>
                                <td style="width: 3%;">
                                    <div class="demo-checkbox">
                                        <input type="checkbox" id="{{ 'md_checkbox_29_' . $stock->id }}"
                                               name="transfers[id][{{ $stock->id }}]"
                                               class="chk-col-cyan transfer-check">
                                        <label for="{{ 'md_checkbox_29_' . $stock->id }}"></label>
                                    </div>
                                </td>
                                <td class="text-left">
                                    {{ $stock->product->name }}
                                </td>
                                <td style="width: 20%;" class="text-center">
                                    {{ $stock->min_stock_level }}
                                </td>
                                <td style="width: 20%;" class="text-center">
                                    {{ $stock->available_stock }}
                                </td>
                                <td style="width: 20%;" class="text-center">
                                    <input type="text" value="{{ $stock->available_stock }}" class="form-control text-center" name="transfers[qty][{{ $stock->id }}]" />
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-12">
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter stock in related notes here...', 'rows' => '3'], false) !!}
        </div>
    </div>
</div>
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection
@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script>
        var dropDown = $('.drop-down');
        dropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });
    </script>
@endsection
