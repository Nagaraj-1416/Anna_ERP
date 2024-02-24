<div class="card">
    <div class="card-body">
        <h3><b>ESTIMATES</b> <span class="pull-right">Total Estimates: @{{ estimates.length }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Estimate no</th>
                    <th>Estimate date</th>
                    <th>Expiry date</th>
                    <th>Status</th>
                    <th class="text-right">Amount</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-show="estimates.length" dir-paginate="estimate in estimates | itemsPerPage:5" pagination-id="estimates_pagination">
                    <td>
                        <a target="_blank" href="/sales/estimate/@{{ estimate.id }}">@{{ estimate.estimate_no }}</a>
                    </td>
                    <td>@{{ estimate.estimate_date }}</td>
                    <td>@{{ estimate.expiry_date }}</td>
                    <td>
                        <span ng-class="statusLabelColor(estimate.status) ">@{{ estimate.status }}</span>
                    </td>
                    <td class="text-right">@{{ estimate.total | number:2 }}</td>
                </tr>
                <tr ng-show="!estimates.length ">
                    <td>No Estimates Found...</td>
                </tr>
                </tbody>
            </table>
            <hr ng-if="estimates.length > 5">
            <div class="pull-right">
                <dir-pagination-controls pagination-id="estimates_pagination"></dir-pagination-controls>
            </div>
        </div>
    </div>
</div>