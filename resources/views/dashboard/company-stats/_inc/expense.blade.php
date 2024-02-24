<div class="col-md-12">
    <div class="cus-create-preloader loading" ng-show="expenseLoading">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                    stroke-miterlimit="10"/>
        </svg>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><b>Expense Details</b></h4>
            <small class="text-muted">Receipts and Reports Summary</small>
            <hr>
            <div class="row" ng-show="expenseFilter">
                <div class="col-md-3">
                    <h6><b>Receipts Summary</b></h6>
                    <table class="table color-table muted-table m-t-15">
                        <tbody>
                        <tr>
                            <td class="text-left">No of receipts</td>
                            <td class="text-right">@{{ expense_data.receipts_data.count }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Unreported</td>
                            <td class="text-right">@{{ expense_data.receipts_data.unreported | number:2 }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Unsubmitted</td>
                            <td class="text-right">@{{ expense_data.receipts_data.unsubmitted | number:2}}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Submitted</td>
                            <td class="text-right">@{{ expense_data.receipts_data.submitted | number:2}}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Approved</td>
                            <td class="text-right">@{{ expense_data.receipts_data.approved | number:2 }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Rejected</td>
                            <td class="text-right">@{{ expense_data.receipts_data.rejected | number:2 }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Reimbursed</td>
                            <td class="text-right">@{{ expense_data.receipts_data.reimbursed | number:2 }}</td>
                        </tr>
                        <tr>
                            <td class="text-right td-bg-info"><b>Total</b></td>
                            <td class="text-right td-bg-success"><b>@{{ expense_data.receipts_data.total | number:2
                                    }}</b></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-3">
                    <h6><b>Reports Summary</b></h6>
                    <table class="table color-table muted-table m-t-15">
                        <tbody>
                        <tr>
                            <td class="text-left">No of reports</td>
                            <td class="text-right">@{{ expense_data.reports_data.count }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Drafted</td>
                            <td class="text-right">@{{ expense_data.reports_data.drafted | number:2 }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Submitted</td>
                            <td class="text-right">@{{ expense_data.reports_data.submitted| number:2 }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Approved</td>
                            <td class="text-right">@{{ expense_data.reports_data.approved | number:2}}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Rejected</td>
                            <td class="text-right">@{{ expense_data.reports_data.rejected | number:2}}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Partially Reimbursed</td>
                            <td class="text-right">@{{ expense_data.reports_data.partially_reimbursed | number:2}}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Reimbursed</td>
                            <td class="text-right">@{{ expense_data.reports_data.reimbursed | number:2 }}</td>
                        </tr>
                        <tr>
                            <td class="text-right td-bg-info"><b>Total</b></td>
                            <td class="text-right td-bg-success"><b>@{{ expense_data.reports_data.total | number:2
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
                            <td class="text-right">@{{ expense_data.payment.cash | number:2 }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Cheque</td>
                            <td class="text-right">@{{ expense_data.payment.cheque | number:2 }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Deposit</td>
                            <td class="text-right">@{{ expense_data.payment.deposit | number:2 }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Credit Card</td>
                            <td class="text-right">@{{ expense_data.payment.credit_card | number:2 }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row" ng-show="!expenseFilter">
                <div class="col-md-12 text-center">
                    <p>No data to display...</p>
                </div>
            </div>
        </div>
    </div>
</div>