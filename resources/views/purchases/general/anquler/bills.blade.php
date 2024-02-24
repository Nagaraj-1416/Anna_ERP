<div class="card">
    <div class="card-body">
        <h3><b>BILLS</b> <span class="pull-right">Total Bills: @{{ bills.length }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Bill no</th>
                    <th>Bill date</th>
                    <th>Due date</th>
                    <th>Status</th>
                    <th class="text-right">Amount</th>
                    <th class="text-right">Paid</th>
                    <th class="text-right">Balance</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-show="bills.length" dir-paginate="bill in bills | itemsPerPage:5"
                    pagination-id="bills_paginate">
                    <td>
                        <a target="_blank" href="/purchase/bill/@{{ bill.id }}">@{{ bill.bill_no }}</a>
                    </td>
                    <td>@{{ bill.bill_date }}</td>
                    <td>@{{ bill.due_date }}</td>
                    <td>
                        <span class="@{{ statusLabelColor(bill.status) }}">@{{ bill.status }}</span>
                    </td>
                    <td class="text-right">@{{ bill.amount | number:2 }}</td>
                    <td class="text-right text-green">@{{ billOutstanding(bill)['paid'] | number:2 }}</td>
                    <td class="text-right text-warning">@{{ billOutstanding(bill)['balance'] | number:2 }}</td>
                </tr>
                <tr ng-show="!bills.length">
                    <td>No Invoices Found...</td>
                </tr>
                </tbody>
            </table>

            <hr ng-if="bills.length > 5">
            <div class="pull-right">
                <dir-pagination-controls pagination-id="bills_paginate"></dir-pagination-controls>
            </div>
        </div>
    </div>
</div>