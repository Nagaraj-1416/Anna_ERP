<div class="form-body" ng-controller="TransactionOrderController">
    <div class="row" style="display: none;">
        <div class="col-md-3">
            <div class="form-group required {{ ($errors->has('type')) ? 'has-danger' : '' }}">
                <label class="control-label">Deposit or Withdrawal</label>
                <div class="demo-radio-button">
                    <input name="type" value="Deposit" type="radio" class="with-gap" id="Deposit" checked="" {{ (old('type') == 'Deposit' || (isset($trans) && $trans->type  == 'Deposit')) ? 'checked' : ''}}>
                    <label for="Deposit">Deposit</label>
                    <input name="type" value="Withdrawal" type="radio" class="with-gap" id="Withdrawal" {{ (old('type') == 'Withdrawal' || (isset($trans) && $trans->type == 'Withdrawal'))  ? 'checked' : ''}}>
                    <label for="Withdrawal">Withdrawal</label>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('company_id') ? 'has-danger' : '' }}">
                <label class="control-label">Company</label>
                <div class="ui fluid search normal selection dropdown drop-down {{ $errors->has('company_id') ? 'error' : '' }}">
                    @if(isset($trans))
                        <input name="company_id" type="hidden" value="{{ old('_token') ? old('company_id'): $trans->company_id }}">
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
        <div class="col-md-3">
            {!! form()->bsText('date', 'Date', null, ['placeholder' => 'pick a transaction date', 'class' => 'form-control datepicker']) !!}
        </div>
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('tx_type_id') ? 'has-danger' : '' }}">
                <label class="control-label">Transaction type</label>
                <div class="ui fluid search normal selection dropdown drop-down {{ $errors->has('tx_type_id') ? 'error' : '' }}">
                    @if(isset($trans))
                        <input name="tx_type_id" type="hidden" value="{{ old('_token') ? old('tx_type_id'): $trans->tx_type_id }}">
                    @else
                        <input name="tx_type_id" type="hidden" value="{{ old('_token') ? old('tx_type_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a transaction type</div>
                    <div class="menu">
                        @foreach(transTypeDropDown() as $key => $type)
                            <div class="item" data-value="{{ $key }}">{{ $type }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('tx_type_id') }}</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('customer_id') ? 'has-danger' : '' }}">
                <label class="control-label">Customer</label>
                <div class="ui fluid search normal selection dropdown drop-down {{ $errors->has('customer_id') ? 'error' : '' }}">
                    @if(isset($trans))
                        <input name="customer_id" type="hidden" value="{{ old('_token') ? old('customer_id'): $trans->customer_id }}">
                    @else
                        <input name="customer_id" type="hidden" value="{{ old('_token') ? old('customer_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a customer</div>
                    <div class="menu">
                        @foreach(customerDropDown() as $key => $type)
                            <div class="item" data-value="{{ $key }}">{{ $type }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('customer_id') }}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('supplier_id') ? 'has-danger' : '' }}">
                <label class="control-label">Supplier</label>
                <div class="ui fluid search normal selection dropdown drop-down {{ $errors->has('supplier_id') ? 'error' : '' }}">
                    @if(isset($trans))
                        <input name="supplier_id" type="hidden" value="{{ old('_token') ? old('supplier_id'): $trans->supplier_id }}">
                    @else
                        <input name="supplier_id" type="hidden" value="{{ old('_token') ? old('supplier_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a supplier</div>
                    <div class="menu">
                        @foreach(supplierDropDown() as $key => $type)
                            <div class="item" data-value="{{ $key }}">{{ $type }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('supplier_id') }}</p>
            </div>
        </div>
    </div>
    @if($errors->has('credit'))
        <div class="alert alert-danger">
            Please ensure that the Debits and Credits are equal.
        </div>
    @endif
    <table class="ui table celled striped">
        <thead>
            <tr>
                <th>Account</th>
                <th width="15%" class="text-right">Debits</th>
                <th width="15%" class="text-right">Credits</th>
                <th  width="10%" class="text-right"></th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="record in transaction.records" account-loop>
                @include('finance.transaction._inc.item-template')
            </tr>
            <tr>
                <td class="text-right">Total</td>
                <td class="text-right">@{{ transaction.debit_total }}</td>
                <td class="text-right">@{{ transaction.credit_total }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4">
                    <button  type="button" ng-click="addRecord()" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> Add More</button>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="row m-t-15">
        <div class="col-md-6">
            {!! form()->bsTextarea('manual_narration', 'Transaction narration', null, ['placeholder' => 'enter transaction related narration here...', 'rows' => '3'], false) !!}
        </div>
        <div class="col-md-6">
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter transaction related notes here...', 'rows' => '3'], false) !!}
        </div>
    </div>
</div>
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection

@section('script')
    @parent
    @include('finance.transaction._inc.script')
@endsection