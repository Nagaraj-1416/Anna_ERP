<div class="form-body">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group {{ $errors->has('prefix') ? 'has-danger' : '' }} required">
                <label class="control-label">Account prefix</label>
                <div class="ui fluid normal search selection dropdown drop-down">
                    @if(isset($account))
                        <input name="prefix" type="hidden" value="{{ old('_token') ? old('prefix'): $account->prefix }}">
                    @else
                        <input name="prefix" type="hidden" value="{{ old('_token') ? old('prefix'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a prefix</div>
                    <div class="menu">
                        <div class="item" data-value="General">General</div>
                        <div class="item" data-value="Cash">Cash</div>
                        <div class="item" data-value="CIH">CIH</div>
                        <div class="item" data-value="SPN">SPN</div>
                        <div class="item" data-value="Sales">Sales</div>
                        <div class="item" data-value="Purchase">Purchase</div>
                        <div class="item" data-value="General">General</div>
                        <div class="item" data-value="Company">Company</div>
                        <div class="item" data-value="Unit">Production Unit</div>
                        <div class="item" data-value="Store">Store</div>
                        <div class="item" data-value="Shop">Shop</div>
                        <div class="item" data-value="Staff">Staff</div>
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('prefix') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group {{ $errors->has('company_id') ? 'has-danger' : '' }} required">
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
                <p class="form-control-feedback">{{ $errors->first('company_id') }}</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('name', 'Account name', null, ['placeholder' => 'enter account name']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('short_name', 'Account short name', null, ['placeholder' => 'enter account short name']) !!}
        </div>
        <div class="col-md-3">
            <div class="form-group {{ $errors->has('account_type_id') ? 'has-danger' : '' }} required">
                <label class="control-label">Account type</label>
                <div class="ui fluid normal search selection dropdown drop-down">
                    @if(isset($account))
                        <input name="account_type_id" type="hidden" value="{{ old('_token') ? old('account_type_id'): $account->account_type_id }}">
                    @else
                        <input name="account_type_id" type="hidden" value="{{ old('_token') ? old('account_type_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose an account type</div>
                    <div class="menu">
                        @foreach(accTypeDropDown() as $key => $accountType)
                            <div class="item" data-value="{{ $key }}">{{ $accountType }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('account_type_id') }}</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group {{ $errors->has('parent_account_id') ? 'has-danger' : '' }}">
                <label class="control-label">Parent account</label>
                <div class="ui fluid normal search selection dropdown drop-down">
                    @if(isset($account))
                        <input name="parent_account_id" type="hidden" value="{{ old('_token') ? old('parent_account_id'): $account->parent_account_id }}">
                    @else
                        <input name="parent_account_id" type="hidden" value="{{ old('_token') ? old('parent_account_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a parent account</div>
                    <div class="menu">
                        @foreach(accDropDown() as $key => $parentAcc)
                            <div class="item" data-value="{{ $key }}">{{ $parentAcc }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('parent_account_id') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group {{ $errors->has('group_id') ? 'has-danger' : '' }}">
                <label class="control-label">Account Group</label>
                <div class="ui fluid search normal selection dropdown drop-down {{ $errors->has('group_id') ? 'error' : '' }}">
                    @if(isset($account))
                        <input name="group_id" type="hidden" value="{{ old('_token') ? old('group_id'): $account->group_id }}">
                    @else
                        <input name="group_id" type="hidden" value="{{ old('_token') ? old('group_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a group</div>
                    <div class="menu">
                        @foreach(accGroupDropDown() as $key => $accGroup)
                            <div class="item" data-value="{{ $key }}">{{ $accGroup }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('group_id') }}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required {{ $errors->has('closing_bl_carried') ? 'has-danger' : '' }}">
                <label class="control-label">Is this account's closing balance carry forward?</label>
                <div class="demo-radio-button">
                    <input name="closing_bl_carried" value="Yes" type="radio" class="with-gap" id="Yes">
                    <label for="Yes">Yes</label>
                    <input name="closing_bl_carried" value="No" type="radio" class="with-gap" id="No" checked>
                    <label for="No">No</label>
                </div>
                <p class="form-control-feedback">{{ $errors->first('closing_bl_carried') }}</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('opening_balance', 'Opening balance', null, ['placeholder' => 'opening balance'], false) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('opening_balance_at', 'Opening balance as at', null, ['placeholder' => 'enter account short name', 'class' => 'form-control datepicker'], false) !!}
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">Opening balance type</label>
                <div class="demo-radio-button">
                    <input name="opening_balance_type" value="Debit" type="radio" class="with-gap" id="Debit"
                           checked="" {{ (old('opening_balance_type') == 'Debit') ? 'checked' : ''}}>
                    <label for="Debit">Debit</label>
                    <input name="opening_balance_type" value="Credit" type="radio" class="with-gap"
                           id="Credit" {{ (old('opening_balance_type') == 'Credit') ? 'checked' : ''}}>
                    <label for="Credit">Credit</label>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
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

