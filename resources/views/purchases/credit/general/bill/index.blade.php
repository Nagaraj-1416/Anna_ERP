<div id="add_bill_form" ng-controller="AddBillController" class="custom-form-body" ng-show="addBillForm">
    {!! form()->model($credit, ['url' => route('purchase.payment.store', [$credit]), 'method' => 'POST']) !!}
    <div class="form-body">
        <h3 class="box-title box-title-with-margin">Apply To Bills</h3>
        <hr>
        <div class="row">
            <div class="col-md-3">
                <div class="alert alert-info">
                    <h5 class="text-info">
                        <i class="fa fa-exclamation-circle"></i> Credit Remaining
                        - {{ number_format($credit->amount - getSupplierCreditUsed($credit), 2) }}
                    </h5>

                </div>
            </div>
            <div class="col-md-12">
                <div class="alert alert-danger" ng-show="hasError('payment')">
                    <h5 class="text-danger">
                        <i class="fa fa-exclamation-circle"></i> @{{ getErrorMsg('payment') }}
                    </h5>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="ui fluid  search selection dropdown reference-drop-down">
                        <i class="dropdown icon"></i>
                        <div class="default text">choose a reference</div>
                        <div class="menu"></div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="row" ng-repeat="(key , bill) in bills" bill-directive>
                    <div class="col-md-3">
                        <div class="form-group required">
                            <input type="hidden" name="bill_id" value="@{{ bill.id }}">
                            <input disabled class="form-control"
                                   value="@{{ bill.bill_no }} (Bill Date - @{{ bill.bill_date }} | Balance - @{{ bill.balance | number:2 }} )"
                                   placeholder="enter payment"
                                   type="text"
                                   id="bill"
                                   autocomplete="off">
                            <p class="form-control-feedback"></p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group required" ng-class="hasError('payment_date.'+ key) ? 'has-danger' : ''"
                        >
                            <input class="form-control datepicker"
                                   placeholder=" pick payment date" name="payment_date" type="text"
                                   id="payment_date"
                                   ng-model="data.payment_date[key]">
                            <p class="form-control-feedback">@{{ getErrorMsg('payment_date.' + key) }}</p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group  required" ng-class="hasError('payment_type') ? 'has-danger' : ''">
                            <div class="ui fluid selection dropdown payment-type-dropdown" data-index="@{{ key }}">
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
                    <div class="col-md-2">
                        <div class="form-group required" ng-class="hasError('account.'+ key) ? 'has-danger' : ''">
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
                            <p class="form-control-feedback">@{{ getErrorMsg('account.' + key) }}</p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group required" ng-class="hasError('payment.' + key) ? 'has-danger' : ''"
                        >
                            <input class="form-control"
                                   placeholder="enter payment" name="payment" type="text"
                                   id="payment" ng-model="data.payment[key]">
                            <p class="form-control-feedback">@{{ getErrorMsg('payment.' + key) }}</p>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="button" ng-click="removeData(key)"
                                class="btn waves-effect waves-light btn-danger">
                            <i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <hr>
                <button ng-show="bills.length" type="button"
                        class="btn btn-btn btn-success waves-effect waves-light m-r-10 cus-save-sidebar"
                        data-ng-click="submitForm($event)">
                    <i class="fa fa-check"></i>
                    Apply
                </button>
                <button data-ng-click="cancelForm($event)" type="button"
                        class="btn btn-inverse waves-effect waves-light">
                    <i class="fa fa-remove"></i> Cancel
                </button>
            </div>
        </div>
    </div>
    {{ form()->close() }}
</div>

@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection

@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
@endsection
