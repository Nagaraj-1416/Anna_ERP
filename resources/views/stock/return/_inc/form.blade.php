<div class="form-body">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('return_to') ? 'has-danger' : '' }}">
                <label class="control-label">Return to</label>
                <div class="ui fluid search normal selection dropdown drop-down {{ $errors->has('return_to') ? 'error' : '' }}">
                    <input name="return_to" type="hidden" value="{{ old('_token') ? old('return_to'): '' }}">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a production unit to return</div>
                    <div class="menu">
                        @foreach(productionUnitDropDown() as $key => $pUnit)
                            <div class="item" data-value="{{ $key }}">{{ $pUnit }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('return_to') }}</p>
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
                        <th class="text-left">ITEM DETAILS</th>
                        <th style="width: 20%;" class="text-center">AVAILABLE STOCK</th>
                        <th style="width: 20%;" class="text-left">LAST PURCHASED PRICE</th>
                        <th style="width: 20%;" class="text-center">RETURN STOCK</th>
                    </tr>
                </thead>
                <tbody>
                    @if($stocks)
                        @foreach($stocks as $keyStock => $stock)
                            <tr>
                                <td style="width: 3%;">
                                    <div class="demo-checkbox" style="width: 20px;">
                                        <input type="checkbox" id="{{ 'md_checkbox_29_' . $stock->id }}"
                                               name="returns[id][{{ $stock->id }}]"
                                               class="chk-col-cyan transfer-check" {{ old() && old('returns.id.'.$stock->id) ? 'checked' : '' }}>
                                        <label for="{{ 'md_checkbox_29_' . $stock->id }}"></label>
                                    </div>
                                </td>
                                <td class="text-left">
                                    {{ $stock->product->name }}
                                </td>
                                <td style="width: 20%;" class="text-center">
                                    {{ $stock->available_stock }}
                                </td>
                                <td style="width: 20%;" class="text-left">
                                    {{ number_format(getLastPurchasePrice($stock->product_id), 2) }}
                                    <input type="hidden" value="{{ getLastPurchasePrice($stock->product_id) }}" class="form-control text-center" name="returns[price][{{ $stock->id }}]" />
                                </td>
                                <td style="width: 20%;" class="text-center">
                                    <input type="hidden" value="{{ $stock->available_stock }}" class="form-control text-center" name="returns[available_qty][{{ $stock->id }}]" />
                                    <div class="form-group {{ $errors->has('returns.qty.'.$stock->id) ? 'has-danger' : '' }}">
                                        <input type="text" value="{{ old() ? old('returns.qty.'.$stock->id) : $stock->available_stock }}" class="form-control text-center" name="returns[qty][{{ $stock->id }}]" />
                                        <p class="form-control-feedback">{{ ($errors->has('returns.qty.'.$stock->id) ? $errors->first('returns.qty.'.$stock->id) : '') }}</p>
                                    </div>
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
