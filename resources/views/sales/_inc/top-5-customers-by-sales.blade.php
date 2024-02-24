<div class="card border-default">
    <div class="card-body">
        <div class="d-flex no-block">
            <h4 class="card-title text-megna">Top 10 Customers by Sales</h4>
            <div class="ml-auto"></div>
        </div>
        <h6 class="card-subtitle">{{ carbon()->now()->format('F j, Y') }}</h6>
        <hr>
        <div class="table-responsive">
            <table class="table stylish-table">
                <thead>
                <tr>
                    <th>Customer Details</th>
                    <th class="text-right">Sales</th>
                </tr>
                </thead>
                <tbody>
                <tr dir-paginate="customer in topCustomer | itemsPerPage:5" pagination-id="customer">
                    <td>
                        <h6>
                            <a target="_blank" href="sales/customer/@{{ customer.id }}" class="link">@{{ customer.display_name }}</a>
                        </h6>
                        <small class="text-muted">@{{ customer.code }} | @{{ customer.mobile }}</small>
                    </td>
                    <td class="text-right"><h6>@{{ customer.total_amount | number:2 }}</h6></td>
                </tr>
                </tbody>
            </table>
        </div>
        <hr ng-if="topCustomer.length > 5">
        <div class="pull-right">
            <dir-pagination-controls pagination-id="customer"></dir-pagination-controls>
        </div>
    </div>
</div>