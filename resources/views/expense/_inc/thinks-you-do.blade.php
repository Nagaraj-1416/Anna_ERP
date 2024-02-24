<div class="col-lg-3 col-md-6">
    <div class="card border-info">
        <div class="card-body">
            <h4 class="card-title text-info">Things You Could Do</h4>
            <hr/>
            <ul class="feeds">
                <li>
                    <div class="bg-light-info">
                        <i class="ti-receipt"></i>
                    </div>
                    <a target="_blank" href="{{ route('expense.receipt.create') }}">New expense receipt</a>
                </li>
                <li>
                    <div class="bg-light-danger">
                        <i class="ti-files"></i>
                    </div>
                    <a target="_blank" href="{{ route('expense.reports.create') }}">New expense report</a>
                </li>
            </ul>

        </div>
    </div>

    <div class="card border-purple">
        <div class="card-body">
            <h4 class="card-title test-purple">Recent Expense Reports</h4>
            <div class="table-responsive m-t-5">
                <table class="table stylish-table">
                    <tbody>
                    <tr dir-paginate="report in topReports | itemsPerPage:6" pagination-id="topReports">
                        <td>
                            <h6>
                                <a target="_blank" href="/expense/reports/@{{ report.id }}" class="link">
                                    @{{ report.title }}
                                </a>
                            </h6>
                            <small class="text-muted">@{{ report.status }} | From @{{ report.report_from }} To @{{
                                report.report_to }}
                            </small>
                        </td>
                        <td class="text-right"><h6>@{{ report.amount | number:2 }}</h6></td>
                    </tr>
                    <tr ng-if="!topReports.length">
                        <td>No Reports Found...</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <hr ng-if="topReports.length > 5">
            <div class="pull-right">
                <dir-pagination-controls pagination-id="topReports"></dir-pagination-controls>
            </div>
        </div>
    </div>
</div>