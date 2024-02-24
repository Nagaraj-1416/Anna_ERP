<div class="card">
    <div class="card-body">
        <h3><b>JOURNALS</b> <span class="pull-right">Total Journals: @{{ journals.length }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Journal no</th>
                    <th>Date</th>
                    <th>Auto/Manual</th>
                    <th>Type</th>
                    <th class="text-right">Amount</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-show="journals.length" dir-paginate="journal in journals | itemsPerPage:5"
                    pagination-id="journals_pagination">
                    <td>
                        <a target="_blank" href="/finance/transaction/@{{ journal.id }}">@{{ journal.code }}</a>
                    </td>
                    <td>@{{ journal.date }}</td>
                    <td>@{{ journal.category }}</td>
                    <td>@{{ journal.type }}</td>
                    <td class="text-right">@{{ journal.amount | number:2 }}</td>
                </tr>
                <tr ng-show="!journals.length">
                    <td>No Journals Found...</td>
                </tr>
                </tbody>
            </table>
            <hr ng-if="journals.length > 5">
            <div class="pull-right">
                <dir-pagination-controls pagination-id="journals_pagination"></dir-pagination-controls>
            </div>
        </div>
    </div>
</div>