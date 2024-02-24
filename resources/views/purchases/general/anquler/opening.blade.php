<div class="card opening-card">
    <div class="card-body">
        <h3><b>Opening Balance References</b> <span class="pull-right">Total References: @{{ references.length }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Bill no</th>
                        <th>Bill date</th>
                        <th>Bill due</th>
                        <th>Bill due age</th>
                        <th class="text-right">Bill amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-show="references.length" dir-paginate="reference in references | itemsPerPage:5" pagination-id="reference_pagination">
                        <td>@{{ reference.date }}</td>
                        <td>@{{ reference.bill_no }}</td>
                        <td>@{{ reference.bill_date }}</td>
                        <td>@{{ reference.bill_due }}</td>
                        <td>@{{ reference.bill_due_age }} days</td>

                        <td class="text-right">@{{ reference.bill_amount | number:2 }}</td>
                    </tr>
                    <tr ng-show="!references.length ">
                        <td>No References Found...</td>
                    </tr>
                </tbody>
            </table>
            <hr ng-if="references.length > 5">
            <div class="pull-right">
                <dir-pagination-controls pagination-id="reference_pagination"></dir-pagination-controls>
            </div>
        </div>
    </div>
</div>