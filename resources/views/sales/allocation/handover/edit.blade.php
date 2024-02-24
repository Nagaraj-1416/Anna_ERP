@extends('layouts.master')
@section('title', 'Edit Handover Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Handover Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'url' => route('sales.allocation.handover.update', [$allocation, $handover]), 'method' => 'PATCH', 'files' => true]) }}
                    <div class="row">
                        <div class="col-md-3">
                            <h5><b>Collection from today's sales</b></h5>
                            <table class="ui celled structured table">
                                <tbody>
                                    <tr>
                                        <td>
                                            {!! form()->bsText('cash_sales', 'Cash', $handover->cash_sales, ['placeholder' => ''], false) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {!! form()->bsText('cheque_sales', 'Cheque', $handover->cheque_sales, ['placeholder' => ''], false) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {!! form()->bsText('deposit_sales', 'Deposit', $handover->deposit_sales, ['placeholder' => ''], false) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {!! form()->bsText('card_sales', 'Card', $handover->card_sales, ['placeholder' => ''], false) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {!! form()->bsText('credit_sales', 'Credit', $handover->credit_sales, ['placeholder' => ''], false) !!}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-3">
                            <h5><b>Collection from old sales</b></h5>
                            <table class="ui celled structured table">
                                <tbody>
                                    <tr>
                                        <td>
                                            {!! form()->bsText('old_cash_sales', 'Cash', $handover->old_cash_sales, ['placeholder' => ''], false) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {!! form()->bsText('old_cheque_sales', 'Cheque', $handover->old_cheque_sales, ['placeholder' => ''], false) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {!! form()->bsText('old_deposit_sales', 'Deposit', $handover->old_deposit_sales, ['placeholder' => ''], false) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {!! form()->bsText('old_card_sales', 'Card', $handover->old_card_sales, ['placeholder' => ''], false) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {!! form()->bsText('old_credit_sales', 'Credit', $handover->old_credit_sales, ['placeholder' => ''], false) !!}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-3">
                            <h5><b>Shortages & Excesses</b></h5>
                            <table class="ui celled structured table">
                                <tbody>
                                    <tr>
                                        <td>
                                            {!! form()->bsText('shortage', '', $handover->shortage, ['placeholder' => ''], false) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {!! form()->bsText('excess', '', $handover->excess, ['placeholder' => ''], false) !!}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    {!! form()->bsSubmit('Update', 'btn btn-primary waves-effect waves-light m-r-10', 'Save', 'submit') !!}
                    {!! form()->bsCancel('Cancel', 'sales.allocation.show', $allocation) !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection