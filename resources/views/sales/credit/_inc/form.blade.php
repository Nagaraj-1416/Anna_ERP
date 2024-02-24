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
            <div class="form-group required {{ $errors->has('customer_id') ? 'has-danger' : '' }}">
                <label class="control-label">Customer</label>
                <div class="ui fluid  search selection dropdown customer-drop-down {{ $errors->has('customer_id') ? 'error' : '' }}">
                    <input type="hidden" name="customer_id">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a customer</div>
                    <div class="menu"></div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('customer_id') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            {!! form()->bsText('date', 'Credit date', null, ['placeholder' => 'pick credit date', 'class' => 'form-control datepicker']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('amount', 'Credit amount', null, ['placeholder' => 'credit amount', 'class' => 'form-control']) !!}
        </div>
        <div class="col-md-3">
            <div class="form-group {{ $errors->has('invoice_id') ? 'has-danger' : '' }}">
                <label class="control-label">Reference</label>
                <div class="ui fluid  search selection dropdown reference-drop-down {{ $errors->has('invoice_id') ? 'error' : '' }}">
                    <input type="hidden" name="invoice_id">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a reference</div>
                    <div class="menu"></div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('invoice_id') }}</p>
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
    @include('sales.credit._inc.script')
@endsection