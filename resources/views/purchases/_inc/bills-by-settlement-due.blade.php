<div class="card border-default">
    <div class="card-body">
        <div class="d-flex no-block">
            <h4 class="card-title text-megna">Bills By Settlement Due</h4>
            <div class="ml-auto"></div>
        </div>
        <h6 class="card-subtitle">{{ carbon()->now()->format('F j, Y') }}</h6>
        <hr>
        <div class="table-responsive">
            <table class="table stylish-table">
                <thead>
                <tr>
                    <th>Bill Details</th>
                    <th class="text-right">Amount</th>
                </tr>
                </thead>
                <tbody>
                <tr dir-paginate="bill in bills | itemsPerPage:5" pagination-id="bill">
                    <td>
                        <h6>
                            <a target="_blank" href="purchase/bill/@{{ bill.id }}" class="link">
                                @{{ ( bill.supplier ? bill.supplier.display_name : ' ') }}
                            </a>
                        </h6>
                        <small class="text-muted">@{{ bill.bill_no }} | @{{ bill.bill_date }}</small>
                    </td>
                    <td class="text-right"><h6>@{{ bill.amount ? bill.amount : '0.00' }}</h6></td>
                </tr>
                </tbody>
            </table>
        </div>
        <hr ng-if="bills.length > 5">
        <div class="pull-right">
            <dir-pagination-controls pagination-id="bill"></dir-pagination-controls>
        </div>
    </div>
</div>