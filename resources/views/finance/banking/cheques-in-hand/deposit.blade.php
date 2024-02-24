@extends('layouts.master')
@section('title', 'Deposit to Bank')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Deposit to Bank</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($chequeKey, ['url' => route('finance.cheques.hand.do.deposit', $chequeKey), 'method' => 'PATCH', 'enctype' => 'multipart/form-data']) !!}
                    @php
                    [$chequeNo] = chequeKeyToArray($chequeKey);
                    $chequeData = getChequeDataByNo($chequeKey);
                    @endphp
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <b>Cheque#</b> <code style="font-size: 16px;"><b>{{ $chequeNo }}</b></code><br />
                                <span class="text-warning">{{ $chequeData['formattedDate'] }}</span>,
                                <span class="text-info">{{ $chequeData['bank'] }}</span> <br />
                                <b>Amount:</b> <span class="text-default">{{ number_format($chequeData['eachTotal'], 2) }}</span> <br /><br />
                                <input type="hidden" name="cheque_amount" value="{{ $chequeData['eachTotal'] }}">
                                <input type="hidden" name="cheque_no" value="{{ $chequeNo }}">
                                <input type="hidden" name="cheque_customer" value="{{ $chequeData['customerId'] }}">
                                <b>Customer:</b> <a target="_blank" href="/sales/customer/{{ $chequeData['customerId'] }}">{{ $chequeData['customer'] }}</a><br /><br />

                                <input name="credited_to" type="hidden" value="{{ $chequeData['transferredTo'] }}">

                                <div class="form-group required {{ $errors->has('deposited_to') ? 'has-danger' : '' }}">
                                    <label class="control-label">Deposited to</label>
                                    <div class="ui fluid search normal selection dropdown deposited-to-drop-down {{ $errors->has('deposited_to') ? 'error' : '' }}">
                                        <input name="deposited_to" type="hidden" value="{{ old('_token') ? old('deposited_to'): '' }}">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose a bank account</div>
                                        <div class="menu">
                                            @foreach(bankAccDropDown() as $key => $account)
                                                <div class="item" data-value="{{ $key }}">{{ $account }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="form-control-feedback">{{ $errors->first('deposited_to') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    {!! form()->bsSubmit('Deposit', 'btn btn-success waves-effect waves-light m-r-10', 'Deposit', 'submit') !!}
                    {!! form()->bsCancel('Cancel', 'finance.cheques.hand.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script>
        var depositedToDropDown = $('.deposited-to-drop-down');
        depositedToDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });

        var creditedToDropDown = $('.credited-to-drop-down');
        creditedToDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });

    </script>
@endsection
