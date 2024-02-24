<div class="form-body">
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('registered_date', 'Registered date', null, ['placeholder' => 'registered date', 'class' => 'form-control datepicker']) !!}
        </div>
        <div class="col-md-3">
            <div class="form-group required">
                <label class="control-label">Company</label>
                <div class="ui fluid search normal selection dropdown drop-down">
                    @if(isset($store))
                        <input name="company_id" type="hidden" value="{{ old('_token') ? old('company_id'): $store->company_id }}">
                    @else
                        <input name="company_id" type="hidden" value="{{ old('_token') ? old('company_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a company</div>
                    <div class="menu">
                        @foreach(companyDropDown() as $key => $company)
                            <div class="item" data-value="{{ $key }}">{{ $company }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group required">
                <label class="control-label">Customer</label>
                <div class="ui fluid search normal selection dropdown drop-down">
                    @if(isset($cheque))
                        <input name="customer_id" type="hidden" value="{{ old('_token') ? old('customer_id'): $cheque->customer_id }}">
                    @else
                        <input name="customer_id" type="hidden" value="{{ old('_token') ? old('customer_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a customer</div>
                    <div class="menu">
                        @foreach(customerDropDown() as $key => $customer)
                            <div class="item" data-value="{{ $key }}">{{ $customer }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('cheque_date', 'Cheque date', null, ['placeholder' => 'cheque date', 'class' => 'form-control datepicker']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('cheque_no', 'Cheque number', null, ['placeholder' => 'cheque number']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('amount', 'Cheque amount', null, ['placeholder' => 'cheque amount']) !!}
        </div>
        <div class="col-md-3">
            <div class="form-group required">
                <label class="control-label">Cheque type</label>
                <div class="ui fluid search normal selection dropdown drop-down">
                    @if(isset($cheque))
                        <input name="cheque_type" type="hidden" value="{{ old('_token') ? old('cheque_type'): $cheque->cheque_type }}">
                    @else
                        <input name="cheque_type" type="hidden" value="{{ old('_token') ? old('cheque_type'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a type</div>
                    <div class="menu">
                        <div class="item" data-value="Own">Own</div>
                        <div class="item" data-value="Third Party">Third Party</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group required">
                <label class="control-label">Written bank</label>
                <div class="ui fluid search normal selection dropdown drop-down">
                    @if(isset($cheque))
                        <input name="bank_id" type="hidden" value="{{ old('_token') ? old('bank_id'): $cheque->bank_id }}">
                    @else
                        <input name="bank_id" type="hidden" value="{{ old('_token') ? old('bank_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a bank</div>
                    <div class="menu">
                        @foreach(bankDropDown() as $key => $bank)
                            <div class="item" data-value="{{ $key }}">{{ $bank }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group required">
                <label class="control-label">Deposited to</label>
                <div class="ui fluid search normal selection dropdown drop-down">
                    @if(isset($cheque))
                        <input name="deposited_to" type="hidden" value="{{ old('_token') ? old('deposited_to'): $cheque->deposited_to }}">
                    @else
                        <input name="deposited_to" type="hidden" value="{{ old('_token') ? old('deposited_to'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a deposited account</div>
                    <div class="menu">
                        @foreach(depositedToAccDropDownCheque() as $key => $account)
                            <div class="item" data-value="{{ $key }}">{{ $account }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-12">
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter account related notes here...', 'rows' => '3']) !!}
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

