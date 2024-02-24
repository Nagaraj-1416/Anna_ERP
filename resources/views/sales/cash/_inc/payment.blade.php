<div class="col-md-9">
    <div class="card card-body">
        <div class="row m-b-20">
            <div class="col-md-12">
                <h5><b>Payments Mode</b></h5>
                <hr>
                <div class="form-group required payment-mode-panel">
                    <div class="demo-radio-button">
                        <input name="payment_mode" value="Cash" type="radio"
                               class="with-gap cash payment-mode"
                               id="Cash"
                               checked="" {{ (old('payment_mode') == 'Cash') ? 'checked' : ''}}>
                        <label for="Cash">Cash</label>
                        <input name="payment_mode" value="Cheque" type="radio"
                               class="with-gap cheque payment-mode"
                               id="Cheque" {{ (old('payment_mode') == 'Cheque') ? 'checked' : ''}}>
                        <label for="Cheque">Cheque</label>
                        {{--<input name="payment_mode" value="Direct Deposit" type="radio"--}}
                               {{--class="with-gap direct-deposit payment-mode"--}}
                               {{--id="Direct Deposit" {{ (old('payment_mode') == 'Direct Deposit') ? 'checked' : ''}}>--}}
                        {{--<label for="Direct Deposit">Direct Deposit</label>--}}
                        <input name="payment_mode" value="Credit Card" type="radio"
                               class="with-gap credit-card payment-mode"
                               id="CreditCard" {{ (old('payment_mode') == 'Credit Card') ? 'checked' : ''}}>
                        <label for="CreditCard">Credit Card</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- cheque details panel -->
        <div class="row cheque-details" ng-show="payment.payment_mode === 'Cheque'">
            <div class="col-md-4">
                {!! form()->bsText('cheque_no', 'Cheque no', null, ['placeholder' => 'cheque no', 'class' => 'form-control']) !!}
            </div>
            <div class="col-md-4">
                {!! form()->bsText('cheque_date', 'Cheque date', !old('_token') ? carbon()->toDateString() : null, ['placeholder' => 'cheque date', 'class' => 'form-control datepicker']) !!}
            </div>
            <div class="col-md-4"></div>
        </div>

        <!-- direct deposit details panel -->
        <div class="row direct-deposit" ng-show="payment.payment_mode === 'Direct Deposit'">
            <div class="col-md-4">
                {!! form()->bsText('account_no', 'Account no', null, ['placeholder' => 'enter account no', 'class' => 'form-control']) !!}
            </div>
            <div class="col-md-4">
                {!! form()->bsText('deposited_date', 'Deposited date', !old('_token') ? carbon()->toDateString() : null, ['placeholder' => 'pick a deposited date', 'class' => 'form-control datepicker']) !!}
            </div>
            <div class="col-md-4"></div>
        </div>

        <!-- credit card details panel -->
        <div class="row credit-card" ng-show="payment.payment_mode === 'Credit Card'">
            <div class="col-md-4">
                {!! form()->bsText('card_holder_name', 'Card holder name', null, ['placeholder' => 'enter card holder name', 'class' => 'form-control']) !!}
            </div>
            <div class="col-md-4">
                {!! form()->bsText('card_no', 'Card no', null, ['placeholder' => 'enter card no', 'class' => 'form-control']) !!}
            </div>
            <div class="col-md-4">
                {!! form()->bsText('expiry_date', 'Expiry date', null, ['placeholder' => 'enter card no', 'class' => 'form-control datepicker']) !!}
            </div>
        </div>

        <!-- this same for cheque, direct deposit and card payment modes -->
        <div class="row not-cash" ng-show="payment.payment_mode !== 'Cash'">
            <div class="col-md-12">
                <div class="form-group required {{ $errors->has('cc_bank_id') ? 'has-danger' : '' }}">
                    <label class="control-label">Bank</label>
                    <div class="ui fluid search normal selection dropdown drop-down bank-drop-down {{ $errors->has('cc_bank_id') ? 'error' : '' }}">
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
    </div>
</div>