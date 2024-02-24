<div class="form-body">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('staff_id') ? 'has-danger' : '' }}">
                <label class="control-label">Store staff</label>
                <div class="ui fluid search normal selection dropdown drop-down {{ $errors->has('staff_id') ? 'error' : '' }}">
                    <input name="staff_id" type="hidden" value="{{ old('_token') ? old('staff_id'): '' }}">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a store staff</div>
                    <div class="menu">
                        @foreach($staff as $staffKey => $staffValue)
                            <div class="item" data-value="{{ $staffKey }}">{{ $staffValue }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('staff_id') }}</p>
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-12">
            <h6>Choose stocks that you want to review and update</h6>
            <hr>
            <table class="ui structured table collapse-table">
                <thead>
                    <tr>
                        <th class="text-left">STOCK ITEMS</th>
                        <th style="width: 20%;" class="text-center">AVAILABLE STOCK</th>
                        <th style="width: 20%;" class="text-center">ACTUAL STOCK</th>
                    </tr>
                </thead>
                <tbody>
                    @if($stocks)
                        @foreach($stocks as $keyStock => $stock)
                            <tr>
                                <td class="text-left">
                                    <a target="_blank" href="{{ route('stock.show', $stock) }}">
                                        {{ $stock->product->name }}
                                    </a>
                                </td>
                                <td style="width: 20%;" class="text-center">
                                    {{ $stock->available_stock }}
                                </td>
                                <td style="width: 20%;" class="text-center">
                                    <input type="hidden" value="{{ $stock->id }}" class="form-control text-center" name="reviews[id][{{ $stock->id }}]" />
                                    <input type="hidden" value="{{ $stock->available_stock }}" class="form-control text-center" name="reviews[available_qty][{{ $stock->id }}]" />
                                    <input type="text" value="{{ $stock->available_stock }}" class="form-control text-center" name="reviews[actual_qty][{{ $stock->id }}]" />
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="row m-t-20">
        <div class="col-md-12">
            {!! form()->bsTextarea('notes', 'Stock review Notes', null, ['placeholder' => 'enter your stock review related notes here...', 'rows' => '3']) !!}
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
