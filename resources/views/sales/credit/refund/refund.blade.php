<div ng-controller="RefundController">
    @if($credit->refunds()->count())
        <div class="card">
            <div class="card-body">
                <h3><b>REFUNDS</b> <span class="pull-right">Total Refunds: {{ $credit->refunds()->count() }}</span></h3>
                <hr>
                <div class="table-responsive">
                    <table class="table color-table muted-table">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Mode</th>
                            <th>Paid through</th>
                            <th>Status</th>
                            <th class="text-right">Amount</th>
                            <th style="width: 10%;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($credit->refunds()->get() as $refund)
                            <tr>
                                <td>{{ $refund->refunded_on ?? 'None'}}</td>
                                <td>{{ $refund->payment_mode ?? 'None'}}</td>
                                <td>{{ $refund->account->name ?? 'None' }}</td>
                                <td>{{ $refund->status ?? 'None' }}</td>
                                <td class="text-right">{{ number_format($refund->amount, 2) }}</td>
                                <td>
                                    @can('edit', $refund)
                                        <a ng-click="editRefund($event)" href=""
                                           class="btn btn-primary btn-sm sidebar-btn"
                                           data-id="{{ $refund->id }}">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $refund)
                                        <a href="" ng-click="deleteRefund($event)"
                                           class="btn btn-danger btn-sm delete-payment-btn"
                                           data-id="{{ $refund->id }}">
                                            <i class="fa fa-remove"></i>
                                        </a>
                                    @endcan
                                    @can('print', $refund)
                                        <a target="_blank"
                                           href="{{ route('sales.credit.refund.print', ['credit' => $credit, 'refund' => $refund]) }}"
                                           class="btn btn-inverse btn-sm"><i class="fa fa-print"></i></a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <div id="refund-sidebar" class="card card-outline-inverse disabled-dev">
        <div class="cus-create-preloader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
            </svg>
        </div>
        <div class="card-header ">
            <h3 class="m-b-0 text-white">@{{ edit ? 'Edit Refund' : 'Create Refund' }}</h3>
            <h6 class="card-subtitle text-white">@{{ edit ? 'Edit refund' : 'Add new refund' }}</h6>
        </div>
        <div class="card-body" id="add-cus-body">
            <div class="form">
                <div class="form-body">

                    <div class="alert alert-warning">
                        <h5 class="text-warning">
                            <i class="fa fa-exclamation-circle"></i> Credit Remaining - @{{ getCreditRemain() | number:2
                            }}
                        </h5>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="box-title">Refund Details</h4>
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group  required" ng-class="hasError('payment_mode') ? 'has-danger' : ''">
                                <label for="mobile" class="control-label form-control-label">Payment Mode</label>
                                <div class="ui fluid selection dropdown payment-mode-dropdown">
                                    <input type="hidden" name="payment_mode">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a payment mode</div>
                                    <div class="menu">
                                        <div class="item" ng-repeat="mode in paymentMode" data-value="@{{ mode.name }}">
                                            @{{ mode.name }}
                                        </div>
                                    </div>
                                </div>
                                <p class="form-control-feedback">@{{ getErrorMsg('payment_mode') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group  required" ng-class="hasError('amount') ? 'has-danger' : ''">
                                <label for="refunded_on" class="control-label form-control-label">Refund Amount</label>
                                <input ng-model="refund.amount" class="form-control" placeholder="eneter amount"
                                       name="amount" type="text" id="amount">
                                <p class="form-control-feedback">@{{ getErrorMsg('amount') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group  required" ng-class="hasError('refunded_on') ? 'has-danger' : ''">
                                <label for="refunded_on" class="control-label form-control-label">Refunded on</label>
                                <input ng-model="refund.refunded_on" class="form-control datepicker"
                                       placeholder=" pick refunded date" name="refunded_on" type="text"
                                       id="refunded_on">
                                <p class="form-control-feedback">@{{ getErrorMsg('refunded_on') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group  required" ng-class="hasError('refunded_from') ? 'has-danger' : ''">
                                <label for="mobile" class="control-label form-control-label">Refunded from</label>
                                <div class="ui fluid selection dropdown refunded-from-dropdown">
                                    <input type="hidden" name="refunded_from">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose an account</div>
                                    <div class="menu">
                                        <div class="item" ng-repeat="(key, value) in refundedOn" data-value="@{{ key}}">
                                            @{{ value }}
                                        </div>
                                    </div>
                                </div>
                                <p class="form-control-feedback">@{{ getErrorMsg('refunded_from') }}</p>
                            </div>
                        </div>
                    </div>
                    {{--cheque data--}}
                    <div class="row cheque-data" ng-show="refund.payment_mode == 'Cheque'">
                        <div class="col-md-12">
                            <h4>Cheque details</h4>
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group  required" ng-class="hasError('cheque_no') ? 'has-danger' : ''">
                                <label for="cheque_no" class="control-label form-control-label">Cheque no</label>
                                <input ng-model="refund.cheque_no" class="form-control" placeholder="enter cheque no"
                                       name="cheque_no" type="text" id="cheque_no">
                                <p class="form-control-feedback">@{{ getErrorMsg('cheque_no') }}</p>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group  required" ng-class="hasError('cheque_date') ? 'has-danger' : ''">
                                <label for="cheque_date" class="control-label form-control-label">Cheque date</label>
                                <input ng-model="refund.cheque_date" class="form-control datepicker"
                                       placeholder="pick cheque date"
                                       name="cheque_date" type="text" id="cheque_date">
                                <p class="form-control-feedback">@{{ getErrorMsg('cheque_date') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group  required" ng-class="hasError('bank_id') ? 'has-danger' : ''">
                                <label for="mobile" class="control-label form-control-label">Cheque written bank</label>
                                <div class="ui fluid selection dropdown cheque-bank-dropdown">
                                    <input type="hidden" name="bank_id">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a bank</div>
                                    <div class="menu">
                                        <div class="item" ng-repeat="(key, value) in banks" data-value="@{{ key}}">
                                            @{{ value }}
                                        </div>
                                    </div>
                                </div>
                                <p class="form-control-feedback">@{{ getErrorMsg('bank_id') }}</p>
                            </div>
                        </div>
                    </div>

                    {{--Direct Deposit--}}
                    <div class="row cheque-data" ng-show="refund.payment_mode == 'Direct Deposit'">
                        <div class="col-md-12">
                            <h4>Direct Deposit details</h4>
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group  required" ng-class="hasError('account_no') ? 'has-danger' : ''">
                                <label for="account_no" class="control-label form-control-label">Account no</label>
                                <input ng-model="refund.account_no" class="form-control" placeholder="enter account no"
                                       name="account_no" type="text" id="account_no">
                                <p class="form-control-feedback">@{{ getErrorMsg('account_no') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group  required" ng-class="hasError('deposited_date') ? 'has-danger' : ''">
                                <label for="deposited_date" class="control-label form-control-label">Deposited
                                    date</label>
                                <input ng-model="refund.deposited_date" class="form-control datepicker"
                                       placeholder="pick deposited date"
                                       name="deposited_date" type="text" id="deposited_date">
                                <p class="form-control-feedback">@{{ getErrorMsg('deposited_date') }}</p>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group  required" ng-class="hasError('bank_id') ? 'has-danger' : ''">
                                <label for="mobile" class="control-label form-control-label">Deposit bank</label>
                                <div class="ui fluid selection dropdown cheque-bank-dropdown">
                                    <input type="hidden" name="bank_id">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a bank</div>
                                    <div class="menu">
                                        <div class="item" ng-repeat="(key, value) in banks" data-value="@{{ key}}">
                                            @{{ value }}
                                        </div>
                                    </div>
                                </div>
                                <p class="form-control-feedback">@{{ getErrorMsg('bank_id') }}</p>
                            </div>
                        </div>
                    </div>

                    {{--Notes Data--}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group  required" ng-class="hasError('notes') ? 'has-danger' : ''">
                                <label for="refunded_on" class="control-label form-control-label">Notes</label>
                                <textarea ng-model="refund.notes" rows="3" class="form-control"
                                          placeholder="refund related notes" name="notes" type="text"
                                          id="notes"></textarea>
                                <p class="form-control-feedback">@{{ getErrorMsg('notes') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <button type="button"
                                    class="btn btn-btn btn-success waves-effect waves-light m-r-10 cus-save-sidebar"
                                    data-ng-click="saveRefund($event)">
                                <i class="fa fa-check"></i>
                                @{{ edit ? 'Update':'Submit' }}
                            </button>
                            <button type="button" class="btn btn-inverse waves-effect waves-light"
                                    data-ng-click="closeSideBar($event)">
                                <i class="fa fa-remove"></i> Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('script')
    @parent
    @include('sales.credit.refund._inc.script')
@endsection