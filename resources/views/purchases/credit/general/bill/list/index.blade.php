<div ng-controller="BillController">
    @if($credit->payments()->count())
        <div class="card">
            <div class="card-body">
                <h3><b>BILLS CREDITED</b> <span
                            class="pull-right">Total Bills Credited: {{ $credit->payments()->count() }}</span>
                </h3>
                <hr>
                <div class="table-responsive">
                    <table class="table color-table muted-table">
                        <thead>
                        <tr>
                            <th>Bill no</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Mode</th>
                            <th>Paid through</th>
                            <th class="text-right">Amount</th>
                            <th style="width: 10%;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($credit->payments()->get() as $payment)
                            <tr>
                                <td><a target="_blank"
                                       href="{{ route('purchase.bill.show', [$payment->bill]) }}">{{ $payment->bill->bill_no ?? 'None'}}</a>
                                </td>
                                <td>{{ $payment->payment_date ?? 'None'}}</td>
                                <td>{{ $payment->payment_type ?? 'None'}}</td>
                                <td>{{ $payment->payment_mode ?? 'None'}}</td>
                                <td>{{ $payment->paidThrough->name or 'None'}}</td>
                                <td class="text-right">{{ number_format($payment->payment, 2) }}</td>
                                <td>
                                    <a href="" ng-click="editPayment($event)"
                                       class="btn btn-primary btn-sm bill-sidebar-btn"
                                       data-id="{{ $payment->id }}">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a href="" ng-click="deletePayment($event)"
                                       class="btn btn-danger btn-sm delete-payment-btn"
                                       data-id="{{ $payment->id }}">
                                        <i class="fa fa-remove"></i>
                                    </a>
                                    <a target="_blank"
                                       href="{{ route('purchase.payment.print', ['credit' => $credit, 'payment' => $payment]) }}"
                                       class="btn btn-inverse btn-sm"><i class="fa fa-print"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <div id="bill-sidebar" class="card card-outline-inverse disabled-dev">
        <div class="cus-create-preloader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
            </svg>
        </div>
        <div class="card-header ">
            <h3 class="m-b-0 text-white">@{{ 'Edit Used Credits' }}</h3>
        </div>
        <div class="card-body" id="add-cus-body">
            <div class="form">
                <div class="form-body">
                    <div class="alert alert-info">
                        <h5 class="text-info">
                            <i class="fa fa-exclamation-circle"></i> Credit Remaining - @{{ getCreditRemain() | number:2
                            }}
                        </h5>
                    </div>
                    <div class="alert alert-danger" ng-show="hasError('total')">
                        <p class="text-danger">
                            <i class="fa fa-exclamation-circle"></i>
                            @{{ getErrorMsg('total') }}
                        </p>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group  required" ng-class="hasError('payment_date') ? 'has-danger' : ''">
                                <label for="payment_date" class="control-label form-control-label">Payment Date</label>
                                <input ng-model="payment.payment_date" class="form-control"
                                       placeholder="enter payment date"
                                       name="payment_date" type="text" id="payment_date">
                                <p class="form-control-feedback">@{{ getErrorMsg('payment_date') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group  required" ng-class="hasError('payment_type') ? 'has-danger' : ''">
                                <label for="payment_type" class="control-label form-control-label">Payment Type</label>
                                <div class="ui fluid selection dropdown payment-type-dropdown">
                                    <input type="hidden" name="payment_type">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a payment type</div>
                                    <div class="menu">
                                        <div class="item" ng-repeat="mode in paymentType" data-value="@{{ mode.name }}">
                                            @{{ mode.name }}
                                        </div>
                                    </div>
                                </div>
                                <p class="form-control-feedback">@{{ getErrorMsg('payment_type') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group required" ng-class="hasError('paid_through') ? 'has-danger' : ''">
                                <label for="paid_through" class="control-label form-control-label">Paid Through</label>
                                <div class="ui fluid search normal selection dropdown account-drop-down"
                                     data-index="@{{ key }}">
                                    <input name="paid_through" id="paid_through" type="hidden">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose an account</div>
                                    <div class="menu">
                                        @foreach(paidThroughAccDropDown() as $key => $account)
                                            <div class="item" data-value="{{ $key }}">{{ $account }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                <p class="form-control-feedback">@{{ getErrorMsg('paid_through') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group required" ng-class="hasError('payment') ? 'has-danger' : ''">
                                <label for="payment" class="control-label form-control-label">Payment</label>
                                <input ng-model="payment.payment" class="form-control" placeholder="enter payment"
                                       name="payment" type="text" id="payment">
                                <p class="form-control-feedback">@{{ getErrorMsg('payment') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <button type="button"
                                    class="btn btn-btn btn-success waves-effect waves-light m-r-10 cus-save-sidebar"
                                    data-ng-click="updatePayment($event)">
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