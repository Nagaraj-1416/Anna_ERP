@extends('layouts.master')
@section('title', 'Mark as Realised')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Mark as Realised</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($chequeKey, ['url' => route('finance.cheques.hand.do.realise', $chequeKey), 'method' => 'PATCH', 'enctype' => 'multipart/form-data']) !!}
                    @php
                    [$chequeNo] = chequeKeyToArray($chequeKey);
                    $chequeData = getChequeDataByNo($chequeKey);
                    @endphp
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <b>Cheque#</b> <code style="font-size: 16px;"><b>{{ $chequeNo }}</b></code><br />
                                <span class="text-warning">{{ $chequeData['formattedDate'] }}</span>,
                                <span class="text-info">{{ $chequeData['bank'] }}</span> <br />
                                <b>Amount:</b> <span class="text-default">{{ number_format($chequeData['eachTotal'], 2) }}</span> <br /><br />
                                <input type="hidden" name="cheque_amount" value="{{ $chequeData['eachTotal'] }}">
                                <input type="hidden" name="cheque_no" value="{{ $chequeNo }}">
                                <input type="hidden" name="cheque_customer" value="{{ $chequeData['customerId'] }}">
                                <b>Customer:</b> <a target="_blank" href="/sales/customer/{{ $chequeData['customerId'] }}">{{ $chequeData['customer'] }}</a><br /><br />

                                <p>Do you really want to mark this cheque as "Realized"? <span class="text-danger">You won't be able to revert this action!</span></p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    {!! form()->bsSubmit('Yes! Realised', 'btn btn-success waves-effect waves-light m-r-10', 'Yes! Realised', 'submit') !!}
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