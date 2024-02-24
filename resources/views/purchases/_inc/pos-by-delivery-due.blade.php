<div class="card border-default">
    <div class="card-body">
        <div class="d-flex no-block">
            <h4 class="card-title text-megna">Purchase Orders By Delivery Due</h4>
            <div class="ml-auto"></div>
        </div>
        <h6 class="card-subtitle">{{ carbon()->now()->format('F j, Y') }}</h6>
        <hr>
        <div class="table-responsive">
            <table class="table stylish-table">
                <thead>
                <tr>
                    <th>Order Details</th>
                    <th class="text-right">Amount</th>
                </tr>
                </thead>
                <tbody>
                <tr dir-paginate="order in orders | itemsPerPage: 5" pagination-id="order">
                    <td>
                        <h6>
                            <a target="_blank" href="purchase/order/@{{ order.id }}" class="link">
                                @{{ ( order.supplier ? order.supplier.display_name : ' ') }}
                            </a>
                        </h6>
                        <small class="text-muted">@{{ order.po_no }} | @{{ order.order_date }}</small>
                    </td>
                    <td class="text-right"><h6>@{{ order.total }}</h6></td>
                </tr>
                </tbody>
            </table>
        </div>
        <hr ng-if="orders.length > 5">
        <div class="pull-right">
            <dir-pagination-controls pagination-id="order"></dir-pagination-controls>
        </div>
    </div>
</div>