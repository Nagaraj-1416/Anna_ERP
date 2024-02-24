<div class="form-body" ng-controller="CommissionController">
    <div class="form-center">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2><b>Sales Commission</b></h2>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="btn-group">
                    <button type="button" ng-click="fnPreYear()" class="btn btn-warning">
                        <span class="mdi mdi-chevron-left"></span>
                    </button>
                    <span class="btn btn-info">{{ $year - 1 }}</span>
                </div>
                <div class="btn-group">
                    <span class="btn btn-success"> {{ $year }} </span>
                </div>
                <div class="btn-group">
                    <span class="btn btn-primary">{{ $year + 1 }}</span>
                    <button type="button" ng-click="fnNextYear()" class="btn btn-warning">
                        <span class="mdi mdi-chevron-right"></span>
                    </button>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group required {{ $errors->has('rep_id') ? 'has-danger' : '' }}">
                    <label class="control-label">Commission for</label>
                    <div class="ui fluid search normal selection dropdown rep-drop-down {{ $errors->has('rep_id') ? 'error' : '' }}">
                        @if(isset($rep))
                            <input name="rep_id" type="hidden" value="{{ old('_token') ? old('rep_id'): $rep->id }}">
                        @else
                            <input name="rep_id" type="hidden" value="{{ old('_token') ? old('rep_id'): '' }}">
                        @endif
                        <i class="dropdown icon"></i>
                        <div class="default text">choose a rep</div>
                        <div class="menu">
                            @foreach(repDropDown() as $keyRep => $valueRep)
                                <div class="item" data-value="{{ $keyRep }}">{{ $valueRep }}</div>
                            @endforeach
                        </div>
                    </div>
                    <p class="form-control-feedback">{{ $errors->first('rep_id') }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group required {{ $errors->has('month_id') ? 'has-danger' : '' }}">
                    <label class="control-label">Commission Month</label>
                    <div class="ui fluid search normal selection dropdown month-drop-down {{ $errors->has('month_id') ? 'error' : '' }}">
                        @if(isset($month))
                            <input name="month_id" type="hidden" value="{{ old('_token') ? old('month_id'): $month }}">
                        @else
                            <input name="month_id" type="hidden" value="{{ old('_token') ? old('month_id'): '' }}">
                        @endif
                        <i class="dropdown icon"></i>
                        <div class="default text">choose a month</div>
                        <div class="menu">
                            @foreach(monthsDropDown() as $keyMonth => $valueMonth)
                                <div class="item" data-value="{{ $keyMonth }}">{{ $valueMonth }}</div>
                            @endforeach
                        </div>
                    </div>
                    <p class="form-control-feedback">{{ $errors->first('month_id') }}</p>
                </div>
            </div>
            <div class="col-md-4" style="padding-top: 8px;">
                <button ng-click="filterCommission()" type="button" class="btn btn-danger btn-sm filter-btn" style="margin-top: 20px;">
                    <i class="fa fa-filter"></i> Filter
                </button>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
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
{{--                                <a target="_blank" href="{{ route('finance.commission.credit.sales', [$rep, $year, $month]) }}">--}}
                                    {{ number_format($creditSales, 2) }}
{{--                                </a>--}}
                                <input type="hidden" name="credit_sales" class="credit-sales" value="{{ $creditSales }}">
                            </td>
                            <td>Total sales</td>
                            <td class="text-right">
                                <a target="_blank" href="{{ route('finance.commission.total.sales', [$rep, $year, $month]) }}">
                                    {{ number_format($totalSales, 2) }}
                                </a>
                                <input type="hidden" name="total_sales" class="total-sales" value="{{ $totalSales }}">
                            </td>
                        </tr>
                        <tr>
                            <td>Cheques received for same day orders</td>
                            <td class="text-right">
                                <a target="_blank" href="{{ route('finance.commission.cheque.received', [$rep, $year, $month]) }}">
                                    {{ number_format($chequeReceived, 2) }}
                                </a>
                                <input type="hidden" name="cheque_received" class="cheque-received" value="{{ $chequeReceived }}">
                            </td>
                            <td>Cash collection for credit orders</td>
                            <td class="text-right">
                                <a target="_blank" href="{{ route('finance.commission.cash.collection', [$rep, $year, $month]) }}">
                                    {{ number_format($cashCollection, 2) }}
                                </a>
                                <input type="hidden" name="cash_collection" class="cash-collection" value="{{ $cashCollection }}">
                            </td>
                        </tr>
                        <tr>
                            <td>Cheques collections for credit orders</td>
                            <td class="text-right">
                                <a target="_blank" href="{{ route('finance.commission.cheque.collection', [$rep, $year, $month]) }}">
                                    {{ number_format($chequeCollection, 2) }}
                                </a>
                                <input type="hidden" name="cheque_collection_dr" class="cheque-received" value="{{ $chequeCollection }}">
                            </td>
                            <td>Cheques collections for credit orders</td>
                            <td class="text-right">
                                <a target="_blank" href="{{ route('finance.commission.cheque.collection', [$rep, $year, $month]) }}">
                                    {{ number_format($chequeCollection, 2) }}
                                </a>
                                <input type="hidden" name="cheque_collection_cr" class="cash-collection" value="{{ $chequeCollection }}">
                            </td>
                        </tr>
                        <tr>
                            <td>Sales returned</td>
                            <td class="text-right">
                                <a target="_blank" href="{{ route('finance.commission.sales.returns', [$rep, $year, $month]) }}">
                                    {{ number_format($salesReturn, 2) }}
                                </a>
                                <input type="hidden" name="sales_returned" class="sales-return" value="{{ $salesReturn }}">
                            </td>
                            <td><span class="text-primary"><b>Cheques that are realized in last month</b></span></td>
                            <td class="text-right">
                                <a target="_blank" href="{{ route('finance.commission.cheque.realized', [$rep, $year, $month]) }}">
                                    {{ number_format($chequeRealized, 2) }}
                                </a>
                                <input type="hidden" name="cheque_realized" class="cheque-realized" value="{{ $chequeRealized }}">
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: middle;">Cheque Returned</td>
                            <td class="text-right">
                                <a target="_blank" href="{{ route('finance.commission.cheques.returned', [$rep, $year, $month]) }}">
                                    {{ number_format($chequeReturned, 2) }}
                                </a>
                                <input type="hidden" name="cheque_returned" class="cheque-returned" value="{{ $chequeReturned }}">
                            </td>
                            <td style="vertical-align: middle;"></td>
                            <td style="vertical-align: middle;"></td>
                        </tr>
                        <tr>
                            <td style="vertical-align: middle;">Sales target</td>
                            <td class="text-right">
                                <input type="text" value="0" name="sales_target" class="form-control text-right sales-target" />
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
                                            <input type="text" value="{{ $visitedCustomers }}" name="customer_visited_count" class="form-control text-right customer-visited-qty" readonly/>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Rate</label>
                                            <input type="text" value="0" name="customer_visited_rate" class="form-control text-right customer-visited-rate" />
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-right">
                                <br />
                                <input type="text" value="0" name="customer_visited" class="form-control text-right customer-visited" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: middle;">Special target</td>
                            <td class="text-right">
                                <input type="text" value="0" name="special_target" class="form-control text-right special-target" />
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
                                            <input type="text" value="0" name="product_sold_count" class="form-control text-right sold-product-qty" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Rate</label>
                                            <input type="text" value="0" name="product_sold_rate" class="form-control text-right sold-product-rate" />
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-right">
                                <br />
                                <input type="text" value="0" name="product_sold" class="form-control text-right sold-product" readonly/>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: middle;" colspan="2"></td>
                            <td style="vertical-align: middle;">Special commission</td>
                            <td class="text-right">
                                <input type="text" value="0" name="special_commission" class="form-control text-right special-commission" />
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: middle;" class="text-right">
                                <b>DEBIT BALANCE</b>
                            </td>
                            <td class="text-right">
                                <input type="text" value="{{ $debitBalance }}" name="debit_balance" class="form-control text-right debit-balance" readonly />
                            </td>
                            <td style="vertical-align: middle;" class="text-right">
                                <b>CREDIT BALANCE</b>
                            </td>
                            <td class="text-right">
                                <input type="text" value="{{ $creditBalance }}" name="credit_balance" class="form-control text-right credit-balance" readonly />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <hr>
        <div>
            <button class="btn btn-danger m-r-10 calculate-btn" type="button">
                <i class="ti-arrows-vertical"></i> Calculate
            </button>
            <button class="btn btn-warning m-r-10 reset-btn" type="button" style="display: none;">
                <i class="ti-eraser"></i> Reset
            </button>
            {!! form()->bsSubmit('Save this commission', 'btn btn-success waves-effect waves-light m-r-10', 'Save', 'submit') !!}
            {!! form()->bsCancel('Cancel', 'finance.commission.index', carbon()->year) !!}
        </div>
    </div>
</div>
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
    <style>
        .form-center{
            padding-left: 200px;
            padding-right: 200px;
        }
    </style>
@endsection
@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script>
        app.controller('CommissionController', ['$scope', '$http', function ($scope, $http) {
            $scope.loading = true;

            $scope.rep = '{{ $rep->id }}';
            $scope.month = '{{ $month }}';
            $scope.year = '{{ $year }}';
            $scope.nextYear = '{{ $nextYear }}';
            $scope.preYear = '{{ $preYear }}';

            $scope.dropdowns = {
                rep: $('.rep-drop-down'),
                month: $('.month-drop-down')
            };

            $scope.el = {
                salesTarget: $('.sales-target'),
                specialTarget: $('.special-target'),
                specialCommission: $('.special-commission'),

                customerVisitedQty: $('.customer-visited-qty'),
                customerVisitedRate: $('.customer-visited-rate'),
                customerVisited: $('.customer-visited'),

                soldProductQty: $('.sold-product-qty'),
                soldProductRate: $('.sold-product-rate'),
                soldProduct: $('.sold-product'),

                debitBalance: $('.debit-balance'),
                creditBalance: $('.credit-balance'),

                calculateBtn: $('.calculate-btn'),
                resetBtn: $('.reset-btn')
            };

            $scope.dropdowns.rep.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val, name) {
                    $scope.rep = val;
                }
            });

            $scope.dropdowns.month.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val, name) {
                    $scope.month = val;
                }
            });

            $scope.filterCommission = function () {
                var rep = $scope.rep;
                var month = $scope.month;
                var year = $scope.year;
                window.location.replace('{{ url('/') }}/finance/commission/rep/' + rep + '/year/' + year + '/month/' + month + '/create');
            };

            $scope.fnPreYear = function () {
                var rep = $scope.rep;
                var month = $scope.month;
                var preYear = $scope.preYear;
                window.location.replace('{{ url('/') }}/finance/commission/rep/' + rep + '/year/' + preYear + '/month/' + month + '/create');
            };

            $scope.fnNextYear = function () {
                var rep = $scope.rep;
                var month = $scope.month;
                var nextYear = $scope.nextYear;
                window.location.replace('{{ url('/') }}/finance/commission/rep/' + rep + '/year/' + nextYear + '/month/' + month + '/create');
            };

            $scope.el.calculateBtn.on('click', function(){

                // calculate drBal
                var drBal = parseFloat($scope.el.debitBalance.val());
                var salesTargetVal = parseFloat($scope.el.salesTarget.val());
                var specialTargetVal = parseFloat($scope.el.specialTarget.val());
                var specialCommissionVal = parseFloat($scope.el.specialCommission.val());

                $scope.el.debitBalance.val(drBal + salesTargetVal + specialTargetVal);

                // calculate visited customer amount
                var customerVisitedQtyVal = parseFloat($scope.el.customerVisitedQty.val());
                var customerVisitedRateVal = parseFloat($scope.el.customerVisitedRate.val());
                $scope.el.customerVisited.val(customerVisitedQtyVal*customerVisitedRateVal);

                // calculate sold products
                var soldProductQtyVal = parseFloat($scope.el.soldProductQty.val());
                var soldProductRateVal = parseFloat($scope.el.soldProductRate.val());
                $scope.el.soldProduct.val(soldProductQtyVal*soldProductRateVal);

                // calculate crBal
                var crBal = parseFloat($scope.el.creditBalance.val());
                var customerVisitedVal = parseFloat($scope.el.customerVisited.val());
                var soldProductVal = parseFloat($scope.el.soldProduct.val());

                $scope.el.creditBalance.val(crBal + customerVisitedVal + soldProductVal + specialCommissionVal);

                //TODO calculate dr & cr variation

                // hide and show buttons
                $scope.el.calculateBtn.hide();
                $scope.el.resetBtn.show();

            });

            $scope.el.resetBtn.on('click', function(){
                $scope.el.resetBtn.hide();
                $scope.el.calculateBtn.show();

                $scope.el.salesTarget.val(0);
                $scope.el.specialTarget.val(0);

                $scope.el.customerVisitedQty.val(0);
                $scope.el.customerVisitedRate.val(0);
                $scope.el.customerVisited.val(0);

                $scope.el.soldProductQty.val(0);
                $scope.el.soldProductRate.val(0);
                $scope.el.soldProduct.val(0);

                $scope.el.debitBalance.val({{ $debitBalance }});
                $scope.el.creditBalance.val({{ $creditBalance }});
            });

        }]);
    </script>
@endsection