<div class="card">
    <div class="card-body">
        <h3><b>CREDITS</b> <span class="pull-right">Total Credits: @{{ credits.length }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Credit no</th>
                    <th>Credit date</th>
                    <th>Status</th>
                    <th class="text-right">Amount</th>
                    <th class="text-right">Refunded</th>
                    <th class="text-right">Credited</th>
                    <th class="text-right">Remaining</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-show="credits.length" dir-paginate="credit in credits | itemsPerPage:5"
                    pagination-id="credits_pagination">
                    <td>
                        <a target="_blank" href="/purchase/credit/@{{ credit.id }}">@{{ credit.code }}</a>
                    </td>
                    <td>@{{ credit.date }}</td>
                    <td>
                        <span class="@{{ statusLabelColor(credit.status) }}">@{{ credit.status }}</span>
                    </td>
                    <td class="text-right text-success">@{{ credit.amount | number:2 }}</td>
                    <td class="text-right text-green">@{{ getTotal(credit.refunds, 'amount') | number:2}}</td>
                    <td class="text-right text-green">@{{getTotal(credit.payments, 'payment') | number:2}}</td>
                    <td class="text-right text-warning">@{{ getSupplierCreditLimit(credit) | number:2 }}</td>
                </tr>
                <tr ng-show="!credits.length">
                    <td>No Credits Found...</td>
                </tr>
                </tbody>
            </table>
            <hr ng-if="credits.length > 5">
            <div class="pull-right">
                <dir-pagination-controls pagination-id="credits_pagination"></dir-pagination-controls>
            </div>
        </div>
    </div>
</div>