<div class="card">
    <div class="card-body">
        <div class="pull-left">
            <h3 style="display:inline"><b>CREDIT ORDERS</b>
            </h3>
            <a ng-show="orders.length" href="{{ route('sales.allocation.credit.order.export', [$allocation]) }}"
               class="btn waves-effect waves-light btn-pdf btn-sm m-b-5">
                <i class="fa fa-file-pdf-o"></i> PDF
            </a>
        </div>
        <div class="pull-right">
            <h3><span>Total Credit Orders: @{{ orders.length }}</span></h3>
        </div>
        <div class="clearfix">
        </div>
        <hr>
        <div class="m-b-10">
            <input type="text" style="margin-left: 0 !important;"
                   ng-model="orderSearch" placeholder="type your keywords here and search for credit orders"
                   class="form-control"
                   autocomplete="off">
        </div>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Customer</th>
                    {{--<th>Type</th>--}}
                    <th>Order no</th>
                    <th>Order date</th>
                    <th class="text-right">Amount</th>
                    <th class="text-right">Paid</th>
                    <th class="text-right">Balance</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-show="orders.length" ng-repeat="order in orders | filter:orderSearch">
                    <td>
                        <a target="_blank" href="/sales/customer/@{{ order.customer_id }}">
                            @{{ order.customer.display_name }}
                        </a>
                        <small ng-if="order.added_stage == 'Later'" class="text-danger"><br/>Added during the sales
                        </small>
                    </td>
                    <td>
                        <a target="_blank" href="/sales/order/@{{ order.sales_order_id }}">
                            @{{ order.order.ref }}
                        </a>
                    </td>
                    <td>@{{ order.order.order_date }}</td>
                    <td class="text-right ">@{{ order.order.total | number:2 }}</td>
                    <td class="text-right ">@{{ order.pay_paid | number:2 }}</td>
                    <td class="text-right ">@{{ order.pay_balance | number:2}}
                    </td>
                </tr>
                <tr ng-show="orders.length">
                    <td colspan="3" class="text-right td-bg-info"><b>Total</b></td>
                    <td class="td-bg-success text-right">
                        <b>@{{ totalData.orderTotal | number:2 }}</b>
                    </td>
                    <td class="td-bg-success text-right">
                        <b>@{{ totalData.paymentTotal | number:2 }}</b>
                    </td>
                    <td class="td-bg-success text-right">
                        <b>@{{ totalData.balance | number:2 }}</b>
                    </td>
                </tr>
                <tr ng-show="!orders.length">
                    <td colspan="2">No Credit Orders Found...</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>