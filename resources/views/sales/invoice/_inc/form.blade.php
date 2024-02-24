<div class="form-body">
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('amount', 'Invoice amount', null, ['placeholder' => 'enter invoice amount', 'class' => 'form-control']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('invoice_date', 'Invoice date', null, ['placeholder' => 'pick an invoice date', 'class' => 'form-control datepicker']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('due_date', 'Due date', null, ['placeholder' => 'pick invoice due date', 'class' => 'form-control datepicker']) !!}
        </div>
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('invoice_type') ? 'has-danger' : '' }}">
                <label class="control-label">Invoice type</label>
                <div class="demo-radio-button">
                    <input name="invoice_type" value="Invoice" type="radio" class="with-gap invoice-type" id="Invoice" checked>
                    <label for="Invoice">Invoice</label>
                    <input name="invoice_type" value="Proforma Invoice" type="radio" class="with-gap invoice-type" id="Proforma Invoice">
                    <label for="Proforma Invoice">Proforma Invoice</label>
                </div>
                <p class="form-control-feedback">{{ $errors->first('invoice_type') }}</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter invoice related notes here...', 'rows' => '4'], false) !!}
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
@endsection