<div class="card">
    <div class="card-body">
        <h3><b>INVOICES</b> <span class="pull-right">Total Invoices: @{{ invoices.length }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Invoice no</th>
                    <th>Invoice date</th>
                    <th>Due date</th>
                    <th>Status</th>
                    <th class="text-right">Amount</th>
                    <th class="text-right">Paid</th>
                    <th class="text-right">Balance</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-show="invoices.length" dir-paginate="invoice in invoices | itemsPerPage:5"
                    pagination-id="invoice_paginate">
                    <td>
                        <a target="_blank" href="/sales/invoice/@{{ invoice.id }}">@{{ invoice.ref }}</a>
                    </td>
                    <td>@{{ invoice.invoice_date }}</td>
                    <td>@{{ invoice.due_date }}</td>
                    <td>
                        <span class="@{{ statusLabelColor(invoice.status) }}">@{{ invoice.status }}</span>
                    </td>
                    <td class="text-right">@{{ invoice.amount | number:2 }}</td>
                    <td class="text-right text-green">@{{ invOutstanding(invoice)['paid'] | number:2 }}</td>
                    <td class="text-right text-warning">@{{ invOutstanding(invoice)['balance'] | number:2 }}</td>
                </tr>
                <tr ng-show="!invoices.length">
                    <td>No Invoices Found...</td>
                </tr>
                </tbody>
            </table>

            <hr ng-if="invoices.length > 5">
            <div class="pull-right">
                <dir-pagination-controls pagination-id="invoice_paginate"></dir-pagination-controls>
            </div>
        </div>
    </div>
</div>