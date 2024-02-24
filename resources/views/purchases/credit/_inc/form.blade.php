<div class="form-body">
    {{--<div class="row">
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('business_type_id') ? 'has-danger' : '' }}">
                <label class="control-label">Business type</label>
                <div class="ui fluid  search selection dropdown bt-drop-down {{ $errors->has('business_type_id') ? 'error' : '' }}">
                    <input type="hidden" name="business_type_id">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a business type</div>
                    <div class="menu"></div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('business_type_id') }}</p>
            </div>
        </div>
    </div>--}}
    <div class="row">
        <div class="col-md-3">
            <input type="hidden" name="business_type_id" value="1">
            <div class="form-group required {{ $errors->has('supplier_id') ? 'has-danger' : '' }}">
                <label class="control-label">Supplier</label>
                <div class="ui fluid  search selection dropdown supplier-drop-down {{ $errors->has('supplier_id') ? 'error' : '' }}">
                    <input type="hidden" name="supplier_id">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a supplier</div>
                    <div class="menu"></div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('supplier_id') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            {!! form()->bsText('date', 'Credit date', null, ['placeholder' => 'pick credit date', 'class' => 'form-control datepicker']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('amount', 'Credit amount', null, ['placeholder' => 'credit amount', 'class' => 'form-control']) !!}
        </div>
        <div class="col-md-3">
            <div class="form-group {{ $errors->has('bill_id') ? 'has-danger' : '' }}">
                <label class="control-label">Reference</label>
                <div class="ui fluid  search selection dropdown reference-drop-down {{ $errors->has('bill_id') ? 'error' : '' }}">
                    <input type="hidden" name="bill_id">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a reference</div>
                    <div class="menu"></div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('bill_id') }}</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter credit related notes here...', 'rows' => '4'], false) !!}
        </div>
    </div>
</div>
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection

@section('script')
    @parent
    @include('purchases.credit._inc.script')
@endsection