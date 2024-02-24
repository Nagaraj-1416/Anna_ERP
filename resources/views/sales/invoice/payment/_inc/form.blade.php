<div class="row">
    <div class="col-md-6">
        <div class="form-group required {{ $errors->has('daily_sale_id') ? 'has-danger' : '' }}">
            <label class="control-label">Allocation</label>
            <div class="ui fluid search normal selection dropdown drop-down {{ $errors->has('daily_sale_id') ? 'error' : '' }}">
                <input name="daily_sale_id" type="hidden"
                       value="{{ old('_token') ? old('daily_sale_id'): isset($invoice) && $invoice->daily_sale_id ?? $invoice->daily_sale_id }}">
                <i class="dropdown icon"></i>
                <div class="default text">choose a allocation</div>
                <div class="menu">
                    @foreach(getAllocationsByCompany() as $key => $allocation)
                        <div class="item" data-value="{{ $allocation->id }}">{{ $allocation->code.' ('.$allocation->from_date.')'.' ('.$allocation->route->name.')' }}</div>
                    @endforeach
                </div>
            </div>
            <p class="form-control-feedback">{{ $errors->first('daily_sale_id') }}</p>
        </div>
    </div>
</div>
<div class="row m-t-10">
    <div class="col-md-6">
        <div class="form-group required">
            <label class="control-label">Payment type</label>
            <div class="demo-radio-button">
                <input name="payment_type" value="Advanced" type="radio" class="with-gap advanced" id="Advanced"
                       checked="" {{ (old('payment_type') == 'Advanced') ? 'checked' : ''}}>
                <label for="Advanced">Advanced</label>
                <input name="payment_type" value="Partial Payment" type="radio" class="with-gap partial-payment"
                       id="Partial Payment" {{ (old('payment_type') == 'Partial Payment') ? 'checked' : ''}}>
                <label for="Partial Payment">Partial Payment</label>
                <input name="payment_type" value="Final Payment" type="radio" class="with-gap final-payment"
                       id="Final Payment" {{ (old('payment_type') == 'Final Payment') ? 'checked' : ''}}>
                <label for="Final Payment">Final Payment</label>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group required">
            <label class="control-label">Payment mode</label>
            <div class="demo-radio-button">
                <input name="payment_mode" value="Cash" type="radio" class="with-gap cash payment-mode"
                       id="Cash"
                       checked="" {{ (old('payment_mode') == 'Cash') ? 'checked' : ''}}>
                <label for="Cash">Cash</label>
                <input name="payment_mode" value="Cheque" type="radio" class="with-gap cheque payment-mode"
                       id="Cheque" {{ (old('payment_mode') == 'Cheque') ? 'checked' : ''}}>
                <label for="Cheque">Cheque</label>
                <input name="payment_mode" value="Direct Deposit" type="radio"
                       class="with-gap direct-deposit payment-mode"
                       id="Direct Deposit" {{ (old('payment_mode') == 'Direct Deposit') ? 'checked' : ''}}>
                <label for="Direct Deposit">Direct Deposit</label>
                <input name="payment_mode" value="Credit Card" type="radio" class="with-gap credit-card payment-mode"
                       id="CreditCard" {{ (old('payment_mode') == 'Credit Card') ? 'checked' : ''}}>
                <label for="CreditCard">Credit Card</label>
                <input name="payment_mode" value="Customer Credit" type="radio" class="with-gap customer-credit payment-mode"
                       id="CustomerCredit" {{ (old('payment_mode') == 'Customer Credit') ? 'checked' : ''}}>
                <label for="CustomerCredit">Customer Credit</label>
            </div>
        </div>
    </div>
</div>
<div class="row m-t-10">
    <div class="col-md-3">
        {!! form()->bsText('payment', 'Payment',  null, ['placeholder' => 'enter payment', 'class' => 'form-control']) !!}
    </div>
    <div class="col-md-3">
        {!! form()->bsText('payment_date', 'Payment date', null, ['placeholder' => 'pick a payment date', 'class' => 'form-control datepicker']) !!}
    </div>
    <div class="col-md-3">
        <div class="form-group required {{ $errors->has('deposited_to') ? 'has-danger' : '' }}">
            <label class="control-label">Deposited to</label>
            <div class="ui fluid search normal selection dropdown drop-down {{ $errors->has('deposited_to') ? 'error' : '' }}">
                <input name="deposited_to" id="deposited_to" type="hidden"
                       value="{{ old('_token') ? old('deposited_to'): isset($payment) && $payment->deposited_to ?? $payment->deposited_to }}">
                <i class="dropdown icon"></i>
                <div class="default text">choose an account</div>
                <div class="menu">
                    @foreach(depositedToAccDropDownNew() as $key => $account)
                        <div class="item" data-value="{{ $key }}">{{ $account }}</div>
                    @endforeach
                </div>
            </div>
            <p class="form-control-feedback">{{ $errors->first('deposited_to') }}</p>
        </div>
    </div>
    <div class="col-md-3"></div>
</div>

<div class="row cheque-data" style="display: none;">
    <div class="col-md-3">
        {!! form()->bsText('cheque_no', 'Cheque no', null, ['placeholder' => 'cheque no', 'class' => 'form-control']) !!}
    </div>
    <div class="col-md-3">
        {!! form()->bsText('cheque_date', 'Cheque date', !old('_token') ? carbon()->toDateString() : null, ['placeholder' => 'cheque date', 'class' => 'form-control datepicker']) !!}
    </div>
    <div class="col-md-3">
        <div class="form-group required {{ $errors->has('cheque_bank_id') ? 'has-danger' : '' }}">
            <label class="control-label">Cheque written bank</label>
            <div class="ui fluid search normal selection dropdown drop-down {{ $errors->has('cheque_bank_id') ? 'error' : '' }}">
                <input name="cheque_bank_id" id="cheque_bank_id" type="hidden"
                       value="{{ old('_token') ? old('cheque_bank_id'): isset($payment) && $payment->bank_id ?? $payment->bank_id }}">
                <i class="dropdown icon"></i>
                <div class="default text">choose a bank</div>
                <div class="menu">
                    @foreach(bankDropDown() as $key => $bank)
                        <div class="item" data-value="{{ $key }}">{{ $bank }}</div>
                    @endforeach
                </div>
            </div>
            <p class="form-control-feedback">{{ $errors->first('cheque_bank_id') }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group required ">
            <label class="control-label">Cheque type</label>
            <div class="demo-radio-button">
                <input name="cheque_type" value="Own" type="radio" class="with-gap own" id="Own"
                       checked="" {{ (old('cheque_type') == 'Own') ? 'checked' : ''}}>
                <label for="Own">Own</label>
                <input name="cheque_type" value="Third Party" type="radio" class="with-gap third-party"
                       id="Third Party" {{ (old('cheque_type') == 'Third Party') ? 'checked' : ''}}>
                <label for="Third Party">Third Party</label>
            </div>
        </div>
    </div>
    <div class="col-md-3"></div>
</div>

<div class="row direct-deposit-data" style="display: none;">
    <div class="col-md-3">
        {!! form()->bsText('account_no', 'Account no', null, ['placeholder' => 'enter account no', 'class' => 'form-control']) !!}
    </div>
    <div class="col-md-3">
        {!! form()->bsText('deposited_date', 'Deposited date', !old('_token') ? carbon()->toDateString() : null, ['placeholder' => 'pick a deposited date', 'class' => 'form-control datepicker']) !!}
    </div>
    <div class="col-md-3">
        <div class="form-group required {{ $errors->has('dd_bank_id') ? 'has-danger' : '' }}">
            <label class="control-label">Deposited bank</label>
            <div class="ui fluid search normal selection dropdown drop-down {{ $errors->has('dd_bank_id') ? 'error' : '' }}">
                <input name="dd_bank_id" id="dd_bank_id" type="hidden"
                       value="{{ old('_token') ? old('dd_bank_id'): isset($payment) && $payment->bank_id ?? $payment->bank_id }}">
                <i class="dropdown icon"></i>
                <div class="default text">choose a bank</div>
                <div class="menu">
                    @foreach(bankDropDown() as $key => $bank)
                        <div class="item" data-value="{{ $key }}">{{ $bank }}</div>
                    @endforeach
                </div>
            </div>
            <p class="form-control-feedback">{{ $errors->first('dd_bank_id') }}</p>
        </div>
    </div>
    <div class="col-md-3"></div>
</div>

<div class="row credit-card-data" style="display: none;">
    <div class="col-md-3">
        {!! form()->bsText('card_holder_name', 'Card holder name', null, ['placeholder' => 'enter card holder name', 'class' => 'form-control']) !!}
    </div>
    <div class="col-md-3">
        {!! form()->bsText('card_no', 'Card no', null, ['placeholder' => 'enter card no', 'class' => 'form-control']) !!}
    </div>
    <div class="col-md-3">
        {!! form()->bsText('expiry_date', 'Expiry date', null, ['placeholder' => 'enter card no', 'class' => 'form-control datepicker']) !!}
    </div>
    <div class="col-md-3">
        <div class="form-group required {{ $errors->has('cc_bank_id') ? 'has-danger' : '' }}">
            <label class="control-label">Bank</label>
            <div class="ui fluid search normal selection dropdown drop-down {{ $errors->has('cc_bank_id') ? 'error' : '' }}">
                <input name="cc_bank_id" id="cc_bank_id" type="hidden"
                       value="{{ old('_token') ? old('cc_bank_id'): isset($payment) && $payment->bank_id ?? $payment->bank_id }}">
                <i class="dropdown icon"></i>
                <div class="default text">choose a bank</div>
                <div class="menu">
                    @foreach(bankDropDown() as $key => $bank)
                        <div class="item" data-value="{{ $key }}">{{ $bank }}</div>
                    @endforeach
                </div>
            </div>
            <p class="form-control-feedback">{{ $errors->first('cc_bank_id') }}</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        {!! form()->bsTextarea('payment_notes', 'Notes', null, ['placeholder' => 'enter payment related notes here...', 'rows' => '4'], false) !!}
    </div>
</div>