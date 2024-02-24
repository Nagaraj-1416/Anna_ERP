<div class="form-body">
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('date', 'Transfer date', null, ['placeholder' => 'pick a transfer date', 'class' => 'form-control', 'ng-model' => 'today']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('amount', 'Transfer amount', null, ['placeholder' => 'amount', 'class' => 'form-control transfer-amount']) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('sender') ? 'has-danger' : '' }}">
                <label class="control-label">Sender</label>
                <div class="ui fluid search normal selection dropdown sender-drop-down {{ $errors->has('sender') ? 'error' : '' }}">
                    <input name="sender" type="hidden" value="{{ old('_token') ? old('sender'): '' }}">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a sender</div>
                    <div class="menu">
                        @foreach(companyDropDown() as $key => $sender)
                            <div class="item" data-value="{{ $key }}">{{ $sender }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('sender') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('receiver') ? 'has-danger' : '' }}">
                <label class="control-label">Receiver</label>
                <div class="ui fluid search normal selection dropdown receiver-drop-down {{ $errors->has('receiver') ? 'error' : '' }}">
                    <input name="receiver" type="hidden" value="{{ old('_token') ? old('receiver'): '' }}">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a receiver</div>
                    <div class="menu">
                        @foreach(receiverCompanyDropDown() as $key => $receiver)
                            <div class="item" data-value="{{ $key }}">{{ $receiver }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('receiver') }}</p>
            </div>
            <input type="hidden" value="{{ $type }}" name="type">
        </div>
    </div>

    <div class="row m-t-10">
        <div class="col-md-12">
            <div class="form-group required">
                <label class="control-label">Transfer mode</label>
                <div class="demo-radio-button">
                    <input name="transfer_mode" value="ByHand" type="radio" class="with-gap transfer-mode" id="ByHand"
                           {{ (old('transfer_mode') == 'ByHand') ? 'checked' : '' }} checked>
                    <label for="ByHand">By Hand</label>
                    <input name="transfer_mode" value="DepositedToBank" type="radio" class="with-gap transfer-mode"
                           id="DepositedToBank" {{ (old('transfer_mode') == 'DepositedToBank') ? 'checked' : '' }} {{ $type == 'Cheque' ? 'disabled' : '' }}>
                    <label for="DepositedToBank">Deposited to Bank</label>
                </div>
            </div>
        </div>
    </div>

    <div class="row m-t-15 by-hand-panel" style="display: none;">
        {{--<div class="col-md-3">
            {!! form()->bsText('handed_over_date', 'Handed over date', null, ['placeholder' => 'handed over date', 'class' => 'form-control datepicker', 'autocomplete' => 'off']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('handed_over_time', 'Handed over time', null, ['placeholder' => 'handed over time', 'class' => 'form-control clockpicker', 'autocomplete' => 'off']) !!}
        </div>--}}
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('handed_order_to') ? 'has-danger' : '' }}">
                <label class="control-label">Handed over to</label>
                <div class="ui fluid search normal selection dropdown handed-order-to-drop-down {{ $errors->has('handed_order_to') ? 'error' : '' }}">
                    <input name="handed_order_to" type="hidden" value="{{ old('_token') ? old('handed_order_to'): '' }}">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a staff</div>
                    <div class="menu">
                        @foreach(handedOverDropDown() as $key => $staff)
                            <div class="item" data-value="{{ $key }}">{{ $staff }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('handed_order_to') }}</p>
            </div>
        </div>
    </div>

    <div class="row m-t-15 deposited-bank-panel" style="display: none;">
        {{--<div class="col-md-3">
            {!! form()->bsText('deposited_date', 'Deposited date', null, ['placeholder' => 'deposited date', 'class' => 'form-control datepicker', 'autocomplete' => 'off']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('deposited_time', 'Deposited time', null, ['placeholder' => 'deposited time', 'class' => 'form-control clockpicker', 'autocomplete' => 'off']) !!}
        </div>--}}
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('deposited_to') ? 'has-danger' : '' }}">
                <label class="control-label">Deposited to</label>
                <div class="ui fluid search normal selection dropdown deposited-to-drop-down {{ $errors->has('deposited_to') ? 'error' : '' }}">
                    <input name="deposited_to" type="hidden" value="{{ old('_token') ? old('deposited_to'): '' }}">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a bank</div>
                    <div class="menu">
                        @foreach(bankAccDropDown() as $key => $bank)
                            <div class="item" data-value="{{ $key }}">{{ $bank }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('deposited_to') }}</p>
            </div>
        </div>
        {{--<div class="col-md-3">
            <div class="form-group required" {{ $errors->has('deposited_receipt') ? 'has-danger' : '' }}>
                <label class="control-label">Deposited receipt</label>
                <input type="file" class="form-control" id="companyLogo" name="deposited_receipt" {{ $errors->has('deposited_receipt') ? 'error' : '' }}>
                <p class="form-control-feedback">{{ $errors->first('deposited_receipt') }}</p>
            </div>
        </div>--}}
    </div>

    <div class="row m-t-10 cih-panel" style="display: none;">
        <div class="col-md-12">
            <h6 class="box-title"><b>Cheques in Hand</b></h6>
            <hr>
            <table class="ui structured table collapse-table">
                <thead>
                <tr>
                    <th style="width: 1%;"></th>
                    <th style="width: 32%;">CHEQUE DETAILS</th>
                    <th style="width: 47%;">REFERENCES</th>
                    <th class="text-right" style="width: 20%;">TOTAL</th>
                </tr>
                </thead>
                <tbody>
                @if($cheques)
                    @foreach($cheques as $chequeKey => $chequeCollection)
                        @php
                            $chequeData = getChequeDataByNo($chequeCollection->first());
                        @endphp
                        <tr>
                            <td style="width: 1%;">
                                <div class="demo-checkbox">
                                    <input type="checkbox" id="{{ 'md_checkbox_29_' . $chequeKey }}"
                                           name="cheques[]"
                                           class="chk-col-cyan" value="{{ $chequeKey }}" data-id="{{ $chequeKey }}" data-value="{{ $chequeData['eachTotal'] }}">
                                    <label for="{{ 'md_checkbox_29_' . $chequeKey }}"></label>
                                </div>
                            </td>
                            <td style="width: 32%;">
                                <b>Cheque# </b><code style="font-size: 14px;">{{ chequeKeyToArray($chequeKey)['cheque_no'] }}</code><br />
                                <span class="text-warning">{{ $chequeData['date'] }}</span>,
                                <span class="text-info">{{ $chequeData['bank'] }}</span>
                            </td>
                            <td style="width: 47%;">
                                @if($chequeCollection)
                                    @foreach($chequeCollection as $cheque)
                                        <div class="clearfix">
                                            <div class="pull-left">
                                                <a target="_blank" href="/sales/customer/{{ $cheque->customer->id }}">
                                                    {{ $cheque->customer->display_name }}
                                                </a><br />
                                                @if($cheque->chequeable)
                                                    <a target="_blank" href="/sales/invoice/{{ $cheque->chequeable->invoice_id }}">
                                                        {{ $cheque->chequeable->invoice->ref }} ({{ $cheque->chequeable->invoice->invoice_date }})
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="pull-right">
                                                {{ number_format($cheque->amount, 2) }}
                                            </div>
                                        </div>
                                        <br />
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-right" style="vertical-align: bottom; width: 20%;"><b>{{ number_format($chequeData['eachTotal'], 2) }}</b></td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">No cheques found!</td>
                    </tr>
                @endif
                <tr style="font-size: 16px;">
                    <td class="text-right" colspan="3"><b>TOTAL</b></td>
                    <td class="text-right" style="width: 15%;"><b>{{ number_format($grandTotal, 2) }}</b></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row m-t-10">
        <div class="col-md-12">
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter transfer related notes here...', 'rows' => '4'], false) !!}
        </div>
    </div>
</div>