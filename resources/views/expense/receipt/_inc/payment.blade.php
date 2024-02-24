<div class="cheque-data m-t-20" style="display: none;">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('cheque_type') ? 'has-danger' : '' }}">
                <label class="control-label">Cheque type</label>
                <div class="ui fluid search normal selection dropdown cheque-type-drop-down {{ $errors->has('cheque_type') ? 'error' : '' }}">
                    <input name="cheque_type" id="cheque_type" type="hidden"
                           value="{{ old('_token') ? old('cheque_type'): isset($expense) && $expense->cheque_type ?? $expense->cheque_type }}">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose cheque type</div>
                    <div class="menu">
                        <div class="item" data-value="Own">Own</div>
                        <div class="item" data-value="Third Party">Third Party</div>
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('cheque_type') }}</p>
            </div>
        </div>
    </div>

    <div class="row cheque-details-panel-1" style="display: none;">
        <div class="col-md-9">
            <div class="form-group required {{ $errors->has('third_party_cheques') ? 'has-danger' : '' }}">
                <label class="control-label">Third Party Cheques</label>
                <div class="ui fluid search normal selection dropdown multiple third-party-cheque-drop-down {{ $errors->has('third_party_cheques') ? 'error' : '' }}">
                    <input name="third_party_cheques" id="third_party_cheques" type="hidden"
                           value="{{ old('_token') ? old('third_party_cheques'): isset($expense) && $expense->third_party_cheques ?? $expense->third_party_cheques }}">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose cheques</div>
                    <div class="menu">
                        @foreach(thirdPartyChequesDropDown() as $key => $tCheque)
                            <div class="item" data-value="{{ $key }}">{{ $key }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('third_party_cheques') }}</p>
            </div>
        </div>
    </div>
    
    <div class="row cheque-details-panel-2" style="display: none;">
        <div class="col-md-3">
            {!! form()->bsText('cheque_no', 'Cheque no', null, ['placeholder' => 'cheque no', 'class' => 'form-control']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('cheque_date', 'Cheque date', null, ['placeholder' => 'cheque date', 'class' => 'form-control datepicker']) !!}
        </div>
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('cheque_bank_id') ? 'has-danger' : '' }}">
                <label class="control-label">Cheque written bank</label>
                <div class="ui fluid search normal selection dropdown cheque-drop-down {{ $errors->has('cheque_bank_id') ? 'error' : '' }}">
                    <input name="cheque_bank_id" id="cheque_bank_id" type="hidden"
                           value="{{ old('_token') ? old('cheque_bank_id'): isset($expense) && $expense->bank_id ?? $expense->bank_id }}">
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
        <div class="col-md-3"></div>
    </div>
</div>

<div class="row direct-deposit-data m-t-20" style="display: none;">
    <div class="col-md-3">
        {!! form()->bsText('account_no', 'Account no', null, ['placeholder' => 'enter account no', 'class' => 'form-control']) !!}
    </div>
    <div class="col-md-3">
        {!! form()->bsText('deposited_date', 'Deposited date', null, ['placeholder' => 'pick a deposited date', 'class' => 'form-control datepicker']) !!}
    </div>
    <div class="col-md-3">
        <div class="form-group required {{ $errors->has('dd_bank_id') ? 'has-danger' : '' }}">
            <label class="control-label">Deposited bank</label>
            <div class="ui fluid search normal selection dropdown deposit-bank-drop-down {{ $errors->has('dd_bank_id') ? 'error' : '' }}">
                <input name="dd_bank_id" id="dd_bank_id" type="hidden"
                       value="{{ old('_token') ? old('dd_bank_id'): isset($expense) && $expense->bank_id ?? $expense->bank_id }}">
                <i class="dropdown icon"></i>
                <div class="default text">choose an account</div>
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

<div class="row credit-card-data m-t-20" style="display: none;">
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
            <div class="ui fluid search normal selection dropdown cc-drop-down {{ $errors->has('cc_bank_id') ? 'error' : '' }}">
                <input name="cc_bank_id" id="cc_bank_id" type="hidden"
                       value="{{ old('_token') ? old('cc_bank_id'): isset($expense) && $expense->bank_id ?? $expense->bank_id }}">
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

@section('script')
    @parent
    <script>
        $(document).ready(function () {
            $invoiceFormEl = {
                chequeData: $('.cheque-data'),
                directDepositData: $('.direct-deposit-data'),
                creditCardData: $('.credit-card-data'),
                paymentMode: $('.payment-mode'),
                depositBankDropDown: $('.deposit-bank-drop-down'),
                chequeDropDown: $('.cheque-drop-down'),
                ccBankDropDown: $('.cc-drop-down'),
                paymentModeHidden_: $('.payment-mode-hidden'),
                companyValue: $('.company-value'),
                branchValue: $('.branch-value'),
                expenseModeHidden_: $('.expense-mode-hidden'),
                expenseCategoryHidden_: $('.expense-category-hidden'),
                paidThroughDropDown_: $('.paid-through-drop-down')
            };

            $invoiceFormEl.depositBankDropDown.dropdown();
            $invoiceFormEl.chequeDropDown.dropdown();
            $invoiceFormEl.ccBankDropDown.dropdown();

            $invoiceFormEl.paymentMode.change(function (e) {
                e.preventDefault();
                if ($(this).val() === 'Cheque') {
                    $invoiceFormEl.chequeData.show();
                    $invoiceFormEl.directDepositData.hide();
                    $invoiceFormEl.creditCardData.hide();
                    $invoiceFormEl.paymentModeHidden_.val($(this).val());

                    $invoiceFormEl.paidThroughDropDown_.dropdown('clear');
                    if($invoiceFormEl.expenseModeHidden_.val() === 'ForOthers' && $invoiceFormEl.expenseCategoryHidden_.val() === 'Office'){
                        cihPaidThroughAccountDropDown_($invoiceFormEl.branchValue.val());
                    }else{
                        cihPaidThroughAccountDropDown_($invoiceFormEl.companyValue.val());
                    }

                } else if ($(this).val() === 'Direct Deposit') {
                    $invoiceFormEl.chequeData.hide();
                    $invoiceFormEl.directDepositData.show();
                    $invoiceFormEl.creditCardData.hide();
                    $invoiceFormEl.paymentModeHidden_.val($(this).val());

                    $invoiceFormEl.paidThroughDropDown_.dropdown('clear');
                    othersPaidThroughAccountDropDown_($invoiceFormEl.companyValue.val());

                } else if ($(this).val() === 'Credit Card') {
                    $invoiceFormEl.chequeData.hide();
                    $invoiceFormEl.directDepositData.hide();
                    $invoiceFormEl.creditCardData.show();
                    $invoiceFormEl.paymentModeHidden_.val($(this).val());

                    $invoiceFormEl.paidThroughDropDown_.dropdown('clear');
                    othersPaidThroughAccountDropDown_($invoiceFormEl.companyValue.val());

                } else if ($(this).val() === 'Bank') {
                    $invoiceFormEl.chequeData.hide();
                    $invoiceFormEl.directDepositData.hide();
                    $invoiceFormEl.creditCardData.hide();
                    $invoiceFormEl.paymentModeHidden_.val($(this).val());

                    $invoiceFormEl.paidThroughDropDown_.dropdown('clear');
                    if($invoiceFormEl.expenseModeHidden_.val() === 'ForOthers' && $invoiceFormEl.expenseCategoryHidden_.val() === 'Office'){
                        othersPaidThroughAccountDropDown_($invoiceFormEl.branchValue.val());
                    }else{
                        othersPaidThroughAccountDropDown_($invoiceFormEl.companyValue.val());
                    }

                } else if ($(this).val() === 'Cash') {
                    $invoiceFormEl.chequeData.hide();
                    $invoiceFormEl.directDepositData.hide();
                    $invoiceFormEl.creditCardData.hide();
                    $invoiceFormEl.paymentModeHidden_.val($(this).val());

                    $invoiceFormEl.paidThroughDropDown_.dropdown('clear');
                    if($invoiceFormEl.expenseModeHidden_.val() === 'ForOthers' && $invoiceFormEl.expenseCategoryHidden_.val() === 'Office'){
                        cashPaidThroughAccountDropDown_($invoiceFormEl.branchValue.val());
                    }else{
                        cashPaidThroughAccountDropDown_($invoiceFormEl.companyValue.val());
                    }
                }
            });

            @if(old('_token'))
                @if(old('payment_mode') == 'Cheque')
                    $invoiceFormEl.chequeData.show();
                    $invoiceFormEl.directDepositData.hide();
                    $invoiceFormEl.creditCardData.hide();

                    $invoiceFormEl.paidThroughDropDown_.dropdown('clear');
                    if($invoiceFormEl.expenseModeHidden_.val() === 'ForOthers' && $invoiceFormEl.expenseCategoryHidden_.val() === 'Office'){
                        cihPaidThroughAccountDropDown_($invoiceFormEl.branchValue.val());
                    }else{
                        cihPaidThroughAccountDropDown_($invoiceFormEl.companyValue.val());
                    }
                @endif
                @if(old('payment_mode') == 'Direct Deposit')
                    $invoiceFormEl.chequeData.hide();
                    $invoiceFormEl.directDepositData.show();
                    $invoiceFormEl.creditCardData.hide();

                    $invoiceFormEl.paidThroughDropDown_.dropdown('clear');
                    othersPaidThroughAccountDropDown_($invoiceFormEl.companyValue.val());
                @endif
                @if(old('payment_mode') == 'Credit Card')
                    $invoiceFormEl.chequeData.hide();
                    $invoiceFormEl.directDepositData.hide();
                    $invoiceFormEl.creditCardData.show();

                    $invoiceFormEl.paidThroughDropDown_.dropdown('clear');
                    othersPaidThroughAccountDropDown_($invoiceFormEl.companyValue.val());
                @endif
                @if(old('payment_mode') == 'Bank')
                    $invoiceFormEl.chequeData.hide();
                    $invoiceFormEl.directDepositData.hide();
                    $invoiceFormEl.creditCardData.hide();

                    $invoiceFormEl.paidThroughDropDown_.dropdown('clear');
                    if($invoiceFormEl.expenseModeHidden_.val() === 'ForOthers' && $invoiceFormEl.expenseCategoryHidden_.val() === 'Office'){
                        othersPaidThroughAccountDropDown_($invoiceFormEl.branchValue.val());
                    }else{
                        othersPaidThroughAccountDropDown_($invoiceFormEl.companyValue.val());
                    }
                @endif
                @if(old('payment_mode') == 'Cash')
                    $invoiceFormEl.chequeData.hide();
                    $invoiceFormEl.directDepositData.hide();
                    $invoiceFormEl.creditCardData.hide();

                    $invoiceFormEl.paidThroughDropDown_.dropdown('clear');
                    if($invoiceFormEl.expenseModeHidden_.val() === 'ForOthers' && $invoiceFormEl.expenseCategoryHidden_.val() === 'Office'){
                        cashPaidThroughAccountDropDown_($invoiceFormEl.branchValue.val());
                    }else{
                        cashPaidThroughAccountDropDown_($invoiceFormEl.companyValue.val());
                    }
                @endif
                @if(old('type_id') == '2')
                    $('.fuel-data-panel').show();
                @else
                    $('.fuel-data-panel').hide();
                @endif
            @endif

            @if(isset($expense))
                var paymentModeOnLoad = '{{ $expense->payment_mode }}';
                if (paymentModeOnLoad === 'Cheque') {
                    $invoiceFormEl.chequeData.show();
                    $invoiceFormEl.directDepositData.hide();
                    $invoiceFormEl.creditCardData.hide();

                    $invoiceFormEl.paidThroughDropDown_.dropdown('clear');
                    if($invoiceFormEl.expenseModeHidden_.val() === 'ForOthers' && $invoiceFormEl.expenseCategoryHidden_.val() === 'Office'){
                        cihPaidThroughAccountDropDown_($invoiceFormEl.branchValue.val());
                    }else{
                        cihPaidThroughAccountDropDown_($invoiceFormEl.companyValue.val());
                    }
                } else if (paymentModeOnLoad === 'Direct Deposit') {
                    $invoiceFormEl.chequeData.hide();
                    $invoiceFormEl.directDepositData.show();
                    $invoiceFormEl.creditCardData.hide();

                    $invoiceFormEl.paidThroughDropDown_.dropdown('clear');
                    othersPaidThroughAccountDropDown_($invoiceFormEl.companyValue.val());

                } else if (paymentModeOnLoad === 'Credit Card') {
                    $invoiceFormEl.chequeData.hide();
                    $invoiceFormEl.directDepositData.hide();
                    $invoiceFormEl.creditCardData.show();

                    $invoiceFormEl.paidThroughDropDown_.dropdown('clear');
                    othersPaidThroughAccountDropDown_($invoiceFormEl.companyValue.val());

                } else if (paymentModeOnLoad === 'Bank') {
                    $invoiceFormEl.chequeData.hide();
                    $invoiceFormEl.directDepositData.hide();
                    $invoiceFormEl.creditCardData.hide();

                    $invoiceFormEl.paidThroughDropDown_.dropdown('clear');
                    if($invoiceFormEl.expenseModeHidden_.val() === 'ForOthers' && $invoiceFormEl.expenseCategoryHidden_.val() === 'Office'){
                        othersPaidThroughAccountDropDown_($invoiceFormEl.branchValue.val());
                    }else{
                        othersPaidThroughAccountDropDown_($invoiceFormEl.companyValue.val());
                    }
                } else if (paymentModeOnLoad === 'Cash') {
                    $invoiceFormEl.chequeData.hide();
                    $invoiceFormEl.directDepositData.hide();
                    $invoiceFormEl.creditCardData.hide();

                    $invoiceFormEl.paidThroughDropDown_.dropdown('clear');
                    if($invoiceFormEl.expenseModeHidden_.val() === 'ForOthers' && $invoiceFormEl.expenseCategoryHidden_.val() === 'Office'){
                        cashPaidThroughAccountDropDown_($invoiceFormEl.branchValue.val());
                    }else{
                        cashPaidThroughAccountDropDown_($invoiceFormEl.companyValue.val());
                    }
                }
                var expTypeId = '{{ $expense->type_id }}';
                if (expTypeId === '2') {
                    $('.fuel-data-panel').show();
                }else{
                    $('.fuel-data-panel').hide();
                }
            @endif

            function cashPaidThroughAccountDropDown_(company) {
                var url_ = '{{ route('finance.cash.paid.through.account.by.company.search', ['companyId']) }}';
                url_ = url_.replace('companyId', company);
                $invoiceFormEl.paidThroughDropDown_.dropdown('setting', {
                    forceSelection: false,
                    apiSettings: {
                        url: url_ + '/{query}',
                        cache:false,
                    },
                    saveRemoteData:false
                });
            }

            function cihPaidThroughAccountDropDown_(company) {
                var url_ = '{{ route('finance.cih.paid.through.account.by.company.search', ['companyId']) }}';
                url_ = url_.replace('companyId', company);
                $invoiceFormEl.paidThroughDropDown_.dropdown('setting', {
                    forceSelection: false,
                    apiSettings: {
                        url: url_ + '/{query}',
                        cache:false,
                    },
                    saveRemoteData:false
                });
            }

            function othersPaidThroughAccountDropDown_(company) {
                var url = '{{ route('finance.others.paid.through.account.by.company.search', ['companyId']) }}';
                url = url.replace('companyId', company);
                $invoiceFormEl.paidThroughDropDown_.dropdown('setting', {
                    forceSelection: false,
                    apiSettings: {
                        url: url + '/{query}',
                        cache:false,
                    },
                    saveRemoteData:false
                });
            }

        });
    </script>
@endsection