<div class="card">
    <div class="card-body">
        <h3><b>SALES ORDERS</b> <span class="pull-right">Total Sales Orders: @{{ orders.length }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Order no</th>
                    <th>Order date</th>
                    <th>Delivery date</th>
                    <th>Status</th>
                    <th class="text-right">Amount</th>
                    <th class="text-right">Invoiced</th>
                    <th class="text-right">Paid</th>
                    <th class="text-right">Balance</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-show="orders.length" dir-paginate="order in orders | itemsPerPage:5" pagination-id="order_paginate">
                    <td>
                        <a target="_blank"
                           href="/sales/order/@{{ order.id }}">@{{ order.ref }}</a>
                    </td>
                    <td>@{{ order.order_date }}</td>
                    <td>@{{ order.delivery_date }}</td>
                    <td>
                        <span ng-class="statusLabelColor(order.status)">@{{ order.status }}</span>
                    </td>
                    <td class="text-right">@{{ order.total | number:2 }}</td>
                    <td class="text-right text-success">@{{ soOutstanding(order)['invoiced'] | number:2}}</td>
                    <td class="text-right text-green">@{{ soOutstanding(order)['paid'] | number:2}}</td>
                    <td class="text-right text-warning">@{{ soOutstanding(order)['balance'] | number:2}}</td>
                </tr>
                <tr ng-show="!orders.length">
                    <td>No Orders Found...</td>
                </tr>
                </tbody>
            </table>
            <hr ng-if="orders.length > 5">
            <div class="pull-right">
                <dir-pagination-controls pagination-id="order_paginate"></dir-pagination-controls>
            </div>
        </div>
    </div>
</div>