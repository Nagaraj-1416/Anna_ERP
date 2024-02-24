@extends('layouts.master')
@section('title', 'Sales Commission Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">{{ $commission->year.'/'.$commission->month }} | commission for {{ $commission->rep->name }}</h4>
                </div>
                <div class="card-body">
                    <!-- estimate summary and history -->
                    <div class="row">
                        <div class="col-md-8">
                            <table class="ui celled structured table collapse-table">
                                <thead>
                                <tr>
                                    <th class="text-center" colspan="2" width="50%">Debit</th>
                                    <th class="text-center" colspan="2" width="50%">Credit</th>
                                </tr>
                                <tr>
                                    <th class="">Particulars</th>
                                    <th class="text-right" width="15%">Amount</th>
                                    <th>Particulars</th>
                                    <th class="text-right" width="15%">Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Credit sales</td>
                                    <td class="text-right">
{{--                                        <a target="_blank" href="{{ route('finance.commission.credit.sales', [$commission->rep_id, $commission->year, $commission->month]) }}">--}}
                                            {{ number_format($commission->credit_sales, 2) }}
{{--                                        </a>--}}
                                    </td>
                                    <td>Total sales</td>
                                    <td class="text-right">
                                        <a target="_blank" href="{{ route('finance.commission.total.sales', [$commission->rep_id, $commission->year, $commission->month]) }}">
                                            {{ number_format($commission->total_sales, 2) }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Cheques received for same day orders</td>
                                    <td class="text-right">
                                        <a target="_blank" href="{{ route('finance.commission.cheque.received', [$commission->rep_id, $commission->year, $commission->month]) }}">
                                            {{ number_format($commission->cheque_received, 2) }}
                                        </a>
                                    </td>
                                    <td>Cash collection for credit orders</td>
                                    <td class="text-right">
                                        <a target="_blank" href="{{ route('finance.commission.cash.collection', [$commission->rep_id, $commission->year, $commission->month]) }}">
                                            {{ number_format($commission->cash_collection, 2) }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Cheques collections for credit orders</td>
                                    <td class="text-right">
                                        <a target="_blank" href="{{ route('finance.commission.cheque.collection', [$commission->rep_id, $commission->year, $commission->month]) }}">
                                            {{ number_format($commission->cheque_collection_dr, 2) }}
                                        </a>
                                    </td>
                                    <td>Cheques collections for credit orders</td>
                                    <td class="text-right">
                                        <a target="_blank" href="{{ route('finance.commission.cheque.collection', [$commission->rep_id, $commission->year, $commission->month]) }}">
                                            {{ number_format($commission->cheque_collection_cr, 2) }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sales returned</td>
                                    <td class="text-right">
                                        <a target="_blank" href="{{ route('finance.commission.sales.returns', [$commission->rep_id, $commission->year, $commission->month]) }}">
                                            {{ number_format($commission->sales_returned, 2) }}
                                        </a>
                                    </td>
                                    <td><span class="text-primary"><b>Cheques that are realized in last month</b></span></td>
                                    <td class="text-right">
                                        <a target="_blank" href="{{ route('finance.commission.cheque.realized', [$commission->rep_id, $commission->year, $commission->month]) }}">
                                            {{ number_format($commission->cheque_realized, 2) }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">Cheque Returned</td>
                                    <td class="text-right">
                                        <a target="_blank" href="{{ route('finance.commission.cheques.returned', [$commission->rep_id, $commission->year, $commission->month]) }}">
                                            {{ number_format($commission->cheque_returned, 2) }}
                                        </a>
                                    </td>
                                    <td style="vertical-align: middle;"></td>
                                    <td style="vertical-align: middle;"></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">Sales target</td>
                                    <td class="text-right">
                                        {{ number_format($commission->sales_target, 2) }}
                                    </td>
                                    <td colspan="2" style="vertical-align: middle;">
                                        <b>Commission for visited customers</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;"></td>
                                    <td class="text-right"></td>
                                    <td style="vertical-align: middle;">
                                        <div class="row">
                                            <div class="col-md-4"></div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Count</label>
                                                    <input type="text" value="{{ number_format($commission->customer_visited_count, 2) }}" name="customer_visited_count" class="form-control text-right customer-visited-qty" readonly/>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Rate</label>
                                                    <input type="text" value="{{ number_format($commission->customer_visited_rate, 2) }}" name="customer_visited_rate" class="form-control text-right customer-visited-rate" readonly/>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <br />
                                        <input type="text" value="{{ number_format($commission->customer_visited, 2) }}" name="customer_visited" class="form-control text-right customer-visited" readonly />
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">Special target</td>
                                    <td class="text-right">
                                        {{ number_format($commission->special_target, 2) }}
                                    </td>
                                    <td style="vertical-align: middle;" colspan="2"><b>Commission for sold products</b></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;"></td>
                                    <td class="text-right"></td>
                                    <td style="vertical-align: middle;">
                                        <div class="row">
                                            <div class="col-md-4"></div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Count</label>
                                                    <input type="text" value="{{ number_format($commission->product_sold_count, 2) }}" name="product_sold_count" class="form-control text-right sold-product-qty" readonly/>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Rate</label>
                                                    <input type="text" value="{{ number_format($commission->product_sold_rate, 2) }}" name="product_sold_rate" class="form-control text-right sold-product-rate" readonly/>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <br />
                                        <input type="text" value="{{ number_format($commission->product_sold, 2) }}" name="product_sold" class="form-control text-right sold-product" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;" colspan="2"></td>
                                    <td style="vertical-align: middle;">Special commission</td>
                                    <td class="text-right">
                                        <input type="text" value="{{ number_format($commission->special_commission, 2) }}" name="special_commission" class="form-control text-right special-commission" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;" class="text-right">
                                        <b>DEBIT BALANCE</b>
                                    </td>
                                    <td class="text-right">
                                        <input type="text" value="{{ number_format($commission->debit_balance, 2) }}" name="debit_balance" class="form-control text-right debit-balance" readonly />
                                    </td>
                                    <td style="vertical-align: middle;" class="text-right">
                                        <b>CREDIT BALANCE</b>
                                    </td>
                                    <td class="text-right">
                                        <input type="text" value="{{ number_format($commission->credit_balance, 2) }}" name="credit_balance" class="form-control text-right credit-balance" readonly />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="vertical-align: middle;" class="text-center">
                                        <p>Please note that the generated sales commission is <span style="font-weight: 600; font-size: 18px;">1%</span> of the balance | <b>{{ number_format($commissionAmount, 2) }}</b></p>
                                        <p>
                                            Sales returns taken with the reason of <span style="font-weight: 600;">Product was expired: </span>
                                            <a target="_blank" href="{{ route('finance.commission.expired.sales.returns', [$commission->rep_id, $commission->year, $commission->month]) }}">
                                                {{ number_format($expiredSalesReturn, 2) }}
                                            </a>
                                        </p>
                                        <p>Generated sales commission<b>: {{ number_format($generatedCommission , 2) }}</b></p>
                                        <p>The awarding sales commission is equal to <b> ({{ number_format($generatedCommission , 2) }} - {{ number_format($expiredSalesReturn , 2) }})</b></p>
                                        <input style="font-weight: 600; font-size: 22px;" type="text" value="Generated Commission: {{ number_format($awardingCommission, 2) }}" name="" class="form-control text-center credit-balance" readonly />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        Total working day of Rep<b>: {{ $repTotalWorkingDays }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <h3>Drivers' Commission</h3>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="ui celled structured table collapse-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Drivers</th>
                                                            <th style="text-align: center; width: 20%;">Total Working Days</th>
                                                            <th style="text-align: right; width: 15%;">Commission</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($driverIds as $driverId)
                                                        <tr>
                                                            <td>{{ getDriverData($driverId)->full_name }}</td>
                                                            <td style="text-align: center;">{{ getDriversWorkingDay($rep, $driverId, $startDate, $endDate) }}</td>
                                                            <td style="text-align: right;">
                                                                {{ round(awardedDriverCommission($rep, $driverId, $startDate, $endDate, $repTotalWorkingDays, $eligibleDriverCommission), 2) }}
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                        <tr>
                                                            <td colspan="2" class="text-right">Drivers' Eligible Commission</td>
                                                            <td class="text-right">
                                                                 <b>{{ number_format($eligibleDriverCommission, 2) }}</b>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" class="text-right">Total Awarded</td>
                                                            <td class="text-right">
                                                                <b>{{ number_format($awardedDriversCommission, 2) }}</b>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" class="text-right">Balance</td>
                                                            <td class="text-right">
                                                                <b>{{ number_format(($eligibleDriverCommission - $awardedDriversCommission), 2) }}</b>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <h3>Labours' Commission</h3>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="ui celled structured table collapse-table">
                                                    <thead>
                                                    <tr>
                                                        <th>Labours</th>
                                                        <th style="text-align: center; width: 20%;">Total Working Days</th>
                                                        <th style="text-align: right; width: 15%;">Commission</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($labourIds as $labourId)
                                                        <tr>
                                                            <td>{{ getLabourData($labourId)->full_name }}</td>
                                                            <td style="text-align: center;">{{ getLaboursWorkingDay($rep, $labourId, $startDate, $endDate) }}</td>
                                                            <td style="text-align: right;">
                                                                {{ round(awardedLabourCommission($rep, $labourId, $startDate, $endDate, $repTotalWorkingDays, $eligibleLabourCommission), 2) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="2" class="text-right">Labours' Eligible Commission</td>
                                                        <td class="text-right">
                                                            <b>{{ number_format($eligibleLabourCommission, 2) }}</b>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="text-right">Total Awarded</td>
                                                        <td class="text-right">
                                                            <b>{{ number_format($awardedLaboursCommission, 2) }}</b>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="text-right">Balance</td>
                                                        <td class="text-right">
                                                            <b>{{ number_format(($eligibleLabourCommission - $awardedLaboursCommission), 2) }}</b>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <h4>Drivers who went to sales without labours</h4>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="ui celled structured table collapse-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Drivers</th>
                                                            <th style="text-align: center; width: 20%;">Total Working Days</th>
                                                            <th style="text-align: right; width: 15%;">Commission</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($driverAloneIds as $driverAloneId)
                                                            <tr>
                                                                <td>{{ getDriverData($driverAloneId)->full_name }}</td>
                                                                <td style="text-align: center;">{{ getDriversWorkingDayAlone($rep, $driverAloneId, $startDate, $endDate) }}</td>
                                                                <td style="text-align: right;">
                                                                    {{ round((($eligibleLabourCommission - $awardedLaboursCommission) / $allocationDriverAloneCount) * getDriversWorkingDayAlone($rep, $driverAloneId, $startDate, $endDate), 2) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="vertical-align: middle;">
                                        <p>Drivers <b>+</b> Labours Commission (<b>8%</b> of Rep's Commission | <b>XXX/8%</b>): <b>{{ number_format($driverAndLabourCommission, 2) }}</b></p>
                                        <p>Drivers' Eligible Commission: <b>{{ number_format($eligibleDriverCommission, 2) }}</b></p>
                                        <p>Labours' Eligible Commission: <b>{{ number_format($eligibleLabourCommission, 2) }}</b></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4"> 
                                        <input style="font-weight: 600; font-size: 16px;" type="text" value="Rep's Commission after Drivers' & Labours' Deduction: ({{ $awardingCommission }} - {{ round($driverAndLabourCommission, 2) }}) = {{ round($awardingCommission - $driverAndLabourCommission, 2) }}" name="" class="form-control text-center credit-balance" readonly />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="vertical-align: middle;">
                                        <p>Total Cash Shortages:
                                            <a target="_blank" href="{{ route('finance.commission.cash.shortages', [$commission->rep_id, $commission->year, $commission->month]) }}">
                                                <b>{{ number_format($cashShortages, 2) }}</b>
                                            </a>
                                        </p>
                                        <p>Total Stock Shortages:
                                            <a target="_blank" href="{{ route('finance.commission.stock.shortages', [$commission->rep_id, $commission->year, $commission->month]) }}">
                                                <b>{{ number_format($stockShortagesItems, 2) }}</b>
                                            </a>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <input style="font-weight: 600; font-size: 20px;" type="text" value="Final Rep's Commission: {{ round(($awardingCommission - $driverAndLabourCommission) - ($cashShortages + $stockShortagesItems), 2) }}" name="" class="form-control text-center credit-balance" readonly />
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <p><b>Status :</b>
                                <span class="{{ statusLabelColor($commission->status) }}">
                                    {{ $commission->status or 'None' }}
                                </span>
                            </p>
                            <p><b>Notes :</b> {{ $commission->notes or 'None' }}</p>
                            <p><b>Prepared by :</b> {{ $commission->preparedBy->name or 'None' }}</p>
                            <p><b>Prepared on :</b> {{ $commission->prepared_on or 'None' }}</p>
                            <p><b>Approved by :</b> {{ $commission->approvedBy->name or 'None' }}</p>
                            <p><b>Approved on :</b> {{ $commission->approved_on or 'None' }}</p>
                            <p><b>Company :</b> {{ $commission->company->name or 'None' }}</p>

                            @if($commission->status == 'Drafted')
                                {!! form()->model($commission, ['url' => route('finance.commission.confirm', $commission), 'method' => 'PATCH']) !!}
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label>Final Commission: </label>
                                                <span style="font-size: 16px;">{{ round(($awardingCommission - $driverAndLabourCommission) - ($cashShortages + $stockShortagesItems), 2) }}</span>
                                                <br />
                                                <br />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group required {{ $errors->has('debit_account') ? 'has-danger' : '' }}">
                                                    <label class="control-label">Debit account</label>
                                                    <div class="ui fluid action input">
                                                        <div class="ui fluid  search selection dropdown account-drop-down {{ $errors->has('debit_account') ? 'error' : '' }}">
                                                            <input name="debit_account" type="hidden" value="{{ $drAccount->id }}">
                                                            <i class="dropdown icon"></i>
                                                            <div class="default text">choose an account</div>
                                                            <div class="menu">
                                                                <div class="item" data-value="{{ $drAccount->id }}">{{ $drAccount->name }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p class="form-control-feedback">{{ $errors->first('debit_account') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group required {{ $errors->has('credit_account') ? 'has-danger' : '' }}">
                                                    <label class="control-label">Credit account</label>
                                                    <div class="ui fluid action input">
                                                        <div class="ui fluid  search selection dropdown account-drop-down {{ $errors->has('credit_account') ? 'error' : '' }}">
                                                            <input name="credit_account" type="hidden" value="{{ $crAccount->id }}">
                                                            <i class="dropdown icon"></i>
                                                            <div class="default text">choose an account</div>
                                                            <div class="menu">
                                                                <div class="item" data-value="{{ $crAccount->id }}">{{ $crAccount->name }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p class="form-control-feedback">{{ $errors->first('credit_account') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" value="{{ round($awardingCommission - $driverAndLabourCommission, 2) }}" name="commission_amount" class="form-control text-right credit-balance" />
                                        @foreach($driverIds as $driverId)
                                            <input type="hidden" value="{{ round(awardedDriverCommission($rep, $driverId, $startDate, $endDate, $driversTotalWorkingDays, $eligibleDriverCommission), 2) }}" name="driver_commission_amount[{{ $driverId }}]" class="form-control text-right credit-balance" />
                                        @endforeach
                                        @foreach($labourIds as $labourId)
                                            <input type="hidden" value="{{ round(awardedLabourCommission($rep, $labourId, $startDate, $endDate, $laboursTotalWorkingDays, $eligibleLabourCommission), 2) }}" name="labour_commission_amount[{{ $labourId }}]" class="form-control text-right credit-balance" />
                                        @endforeach
                                    </div>
                                <br />
                                {!! form()->bsSubmit('Confirm', 'btn btn-success waves-effect waves-light m-r-10') !!}
                                {{ form()->close() }}
                            @else
                                <h3>Related Transactions</h3>
                                <table class="table color-table inverse-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
{{--                                            <th>Date</th>--}}
                                            <th>Narration</th>
                                            <th class="text-right">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($trans as $tranKey => $tran)
                                        <tr>
                                            <td>
                                                <a target="_blank" href="{{ route('finance.trans.show', [$tran]) }}">
                                                    {{ $tran->code }}
                                                </a>
                                            </td>
{{--                                            <td>{{ $tran->date }}</td>--}}
                                            <td>
                                                <a target="_blank" href="{{ route('finance.trans.show', [$tran]) }}">
                                                    {{ $tran->auto_narration }}
                                                </a>
                                            </td>
                                            <td class="text-right">{{ number_format($tran->amount, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var dropDown = $('.account-drop-down');
        dropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });
    </script>
@endsection