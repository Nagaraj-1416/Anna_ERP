<div class="card">
    <div class="card-body">
        <h3><b>RENEWALS</b> <span class="pull-right">Total Renewals: @{{ renewals.length }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Renewal type</th>
                    <th>Renewal date</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-show="renewals.length" dir-paginate="renewal in renewals | itemsPerPage:10"
                    pagination-id="renewals_pagination">
                    <td>@{{ renewal.type }}</td>
                    <td>@{{ renewal.date }}</td>
                </tr>
                <tr ng-show="!renewals.length">
                    <td colspan="2">No Renewals Found...</td>
                </tr>
                </tbody>
            </table>

            <hr ng-if="renewals.length > 5">
            <div class="pull-right">
                <dir-pagination-controls pagination-id="renewals_pagination"></dir-pagination-controls>
            </div>
        </div>
    </div>
</div>