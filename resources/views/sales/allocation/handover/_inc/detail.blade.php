@if($handover)
    <h3>
        <b>CONFIRM SALES HANDOVER</b>
        <small></small>
        <span class="pull-right">#{{ $handover->code }}</span></h3>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="background-color: #EFEFEF;">
                    <div class="row">
                        <div class="col-md-4">
                            <h5><b>Collection from today's sales</b></h5>
                            <table class="ui celled structured table">
                                <tbody>
                                <tr>
                                    <td><b>Cash</b></td>
                                    <td class="text-right">{{ number_format($handover->cash_sales, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><b>Cheque</b></td>
                                    <td class="text-right">{{ number_format($handover->cheque_sales, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><b>Deposit</b></td>
                                    <td class="text-right">{{ number_format($handover->deposit_sales, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><b>Card</b></td>
                                    <td class="text-right">{{ number_format($handover->card_sales, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><b>Credit</b></td>
                                    <td class="text-right">{{ number_format($handover->credit_sales, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="td-bg-info"><b>Total</b></td>
                                    <td class="td-bg-success text-right">
                                        <b>{{ number_format($handover->sales, 2) }}</b>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        @if($allocation->sales_location == 'Van')
                            <div class="col-md-4">
                                <h5><b>Collection from old sales</b></h5>
                                <table class="ui celled structured table">
                                    <tbody>
                                    <tr>
                                        <td><b>Cash</b></td>
                                        <td class="text-right">{{ number_format($handover->old_cash_sales, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Cheque</b></td>
                                        <td class="text-right">{{ number_format($handover->old_cheque_sales, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Deposit</b></td>
                                        <td class="text-right">{{ number_format($handover->old_deposit_sales, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Card</b></td>
                                        <td class="text-right">{{ number_format($handover->old_card_sales, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Credit</b></td>
                                        <td class="text-right">{{ number_format($handover->old_credit_sales, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="td-bg-info"><b>Total</b></td>
                                        <td class="td-bg-success text-right">
                                            <b>{{ number_format($handover->old_sales, 2) }}</b>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        <div class="col-md-4">
                            <h5><b>Total Collection</b></h5>
                            <table class="ui celled structured table">
                                <tbody>
                                <tr>
                                    <td><b>Cash</b></td>
                                    <td class="text-right">{{ number_format(($handover->cash_sales + $handover->old_cash_sales), 2) }}</td>
                                </tr>
                                <tr>
                                    <td><b>Cheque</b></td>
                                    <td class="text-right">{{ number_format(($handover->cheque_sales + $handover->old_cheque_sales), 2) }}</td>
                                </tr>
                                <tr>
                                    <td><b>Deposit</b></td>
                                    <td class="text-right">{{ number_format(($handover->deposit_sales + $handover->old_deposit_sales), 2) }}</td>
                                </tr>
                                <tr>
                                    <td><b>Card</b></td>
                                    <td class="text-right">{{ number_format(($handover->card_sales + $handover->old_card_sales), 2) }}</td>
                                </tr>
                                <tr>
                                    <td><b>Credit</b></td>
                                    <td class="text-right">{{ number_format(($handover->credit_sales + $handover->old_credit_sales), 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="td-bg-info"><b>Total collection</b></td>
                                    <td class="text-right td-bg-success">
                                        <b>{{ number_format($handover->total_collect, 2) }}</b>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif