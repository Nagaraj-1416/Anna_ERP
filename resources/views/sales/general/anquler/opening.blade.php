<div class="card opening-card">
    <div class="card-body">
        <h3><b>Opening Balance References</b> <span class="pull-right">Total References: @{{ references.length }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                    <tr>
                        {{--<th>Date</th>--}}
                        <th>Invoice#</th>
                        <th>Invoice date</th>
                        <th class="text-right">Invoice amount</th>
                        <th class="text-right">Due amount</th>
                        <th class="text-right">Due age</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-show="references.length" dir-paginate="reference in references | itemsPerPage:5" pagination-id="reference_pagination">
                        {{--<td>@{{ reference.date }}</td>--}}
                        <td>@{{ reference.invoice_no }}</td>
                        <td>@{{ reference.invoice_date }}</td>
                        <td class="text-right">@{{ reference.invoice_amount | number:2 }}</td>
                        <td class="text-right">@{{ reference.invoice_due | number:2 }}</td>
                        <td class="text-right">@{{ reference.invoice_due_age }}</td>
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
