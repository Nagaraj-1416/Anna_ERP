<div class="card">
    <div class="card-body">
        <div class="d-flex no-block">
            <h4 class="card-title">Sales Invoices By Settlement Due</h4>
            <div class="ml-auto"></div>
        </div>
        <h6 class="card-subtitle">{{ carbon()->now()->format('F j, Y') }}</h6>
        <hr>
        <div class="table-responsive">
            <table class="table stylish-table">
                <thead>
                <tr>
                    <th>Invoice Details</th>
                    <th>Status</th>
                    <th class="text-right">Amount</th>
                </tr>
                </thead>
                <tbody>
                <tr dir-paginate="invoice in settlementDueData | itemsPerPage:6" pagination-id="settlementDue">
                    <td>
                        <h6>
                            <a href="javascript:void(0)" class="link">
                                @{{ invoice.customer ? invoice.customer.display_name : '' }} -
                                @{{ invoice.invoice_date }}
                            </a>
                        </h6>
                        <small class="text-muted">Invoice No : @{{ invoice.invoice_no }}</small>
                    </td>
                    <td><span class="label" ng-class="getLabelColor(invoice.status)">@{{ invoice.status }}</span></td>
                    <td class="text-right"><h5>LKR @{{ invoice.amount | number:2 }}</h5></td>
                </tr>
                </tbody>
            </table>
        </div>
        <hr ng-if="settlementDueData.length > 5">
        <div class="pull-right">
            <dir-pagination-controls pagination-id="settlementDue"></dir-pagination-controls>
        </div>
    </div>
</div>