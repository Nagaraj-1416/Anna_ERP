<div class="card border-default">
    <div class="card-body">
        <div class="d-flex no-block">
            <h4 class="card-title text-megna">Top 10 Reps by Sales</h4>
            <div class="ml-auto"></div>
        </div>
        <h6 class="card-subtitle">{{ carbon()->now()->format('F j, Y') }}</h6>
        <hr>
        <div class="table-responsive">
            <table class="table stylish-table">
                <thead>
                <tr>
                    <th>Rep Details</th>
                    <th class="text-right">Sales</th>
                </tr>
                </thead>
                <tbody>
                <tr dir-paginate="rep in topSalesReps | itemsPerPage:5" pagination-id="salesReps">
                    <td>
                        <h6>
                            <a href="javascript:void(0)" class="link">@{{ rep.name }}</a>
                        </h6>
                        <small class="text-muted">@{{ rep.code }}</small>
                    </td>
                    <td class="text-right"><h6>@{{ rep.total_amount | number:2 }}</h6></td>
                </tr>
                </tbody>
            </table>
        </div>
        <hr ng-if="topSalesReps.length > 5">
        <div class="pull-right">
            <dir-pagination-controls pagination-id="salesReps"></dir-pagination-controls>
        </div>
    </div>
</div>