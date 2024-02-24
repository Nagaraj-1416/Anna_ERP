<div class="col-md-12">
    <div class="cus-create-preloader loading" ng-show="purchaseLoading">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                    stroke-miterlimit="10"/>
        </svg>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><b>Purchase Details</b></h4>
            <small class="text-muted">Orders, Payments and Credits Summary</small>
            <hr>
            <div class="row" ng-show="purchaseFilter">
                <div class="col-md-3">
                    <h6><b>Orders Summary</b></h6>
                    <table class="table color-table muted-table m-t-15">
                        <tbody>
                        <tr>
                            <td class="text-left">No of orders</td>
                            <td class="text-right">@{{ purchase_data.order_data.count }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Purchases</td>
                            <td class="text-right">@{{ purchase_data.order_data.purchase |number:2 }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Billed</td>
                            <td class="text-right">@{{ purchase_data.order_data.billed |number:2 }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Made</td>
                            <td class="text-right">@{{ purchase_data.order_data.made |number:2 }}</td>
                        </tr>
                        <tr>
                            <td class="text-right td-bg-info"><b>Balance</b></td>
                            <td class="text-right td-bg-success"><b>@{{ purchase_data.order_data.balance |number:2
                                    }}</b></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-3">
                    <h6><b>Payments Summary</b></h6>
                    <table class="table color-table muted-table m-t-15">
                        <tbody>
                        <tr>
                            <td class="text-left">Cash</td>
                            <td class="text-right">@{{purchase_data.payment_data.cash | number:2}}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Cheque</td>
                            <td class="text-right">@{{ purchase_data.payment_data.cheque | number:2 }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Deposit</td>
                            <td class="text-right">@{{ purchase_data.payment_data.direct_deposit | number:2 }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Credit Card</td>
                            <td class="text-right">@{{ purchase_data.payment_data.credit_card | number:2 }}</td>
                        </tr>
                        <tr>
                            <td class="text-right td-bg-info"><b>Received</b></td>
                            <td class="text-right td-bg-success"><b>@{{ purchase_data.payment_data.total | number:2
                                    }}</b>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-3">
                    <h6><b>Credits Summary</b></h6>
                    <table class="table color-table muted-table m-t-15">
                        <tbody>
                        <tr>
                            <td class="text-left">Credits</td>
                            <td class="text-right">@{{ purchase_data.credit_data.credits | number:2 }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Refunded</td>
                            <td class="text-right">@{{ purchase_data.credit_data.refunded | number:2 }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Credited</td>
                            <td class="text-right">@{{ purchase_data.credit_data.credited | number:2 }}</td>
                        </tr>
                        <tr>
                            <td class="text-right td-bg-info"><b>Remaining</b></td>
                            <td class="text-right td-bg-success"><b>@{{ purchase_data.credit_data.total | number:2
                                    }}</b></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row" ng-show="!purchaseFilter">
                <div class="col-md-12 text-center">
                    <p>No data to display...</p>
                </div>
            </div>
        </div>
    </div>
</div>