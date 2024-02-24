<div class="card" ng-show="!filterd">
    <div class="card-body">
        {{--<h3><b>CUSTOMERS</b> <span class="pull-right">Total Customers: @{{ customers.length }}</span></h3>
        <hr>--}}
        <div class="">
            <span style="font-size: 18px;"><b>TOTAL CUSTOMERS: <code>@{{ customers.length }}</code></b></span>
            <div class="table-responsive m-t-5">
                <table class="table color-table muted-table table-scroll">
                    <thead>
                    <tr>
                        <th>Customer</th>
                        <th class="text-center">Is visited?</th>
                        <th>Visit remarks</th>
                        <th>Visited at</th>
                        <th>Distance</th>
                        <th width="10%" class="text-right">AMOUNT</th>
                        <th width="10%" class="text-right">RECEIVED</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-show="customers.length" ng-repeat="customer in customers"
                        ng-class="customer.isVisited === 'No' ? 'td-bg-danger' : ''"
                        customer-directive>
                        <td>
                            <a target="_blank" href="/sales/customer/@{{ customer.customer.id }}">
                                @{{ customer.customer.display_name }}
                            </a>
                        </td>
                        <td class="text-center @{{ customer.isVisited === 'No' ? 'text-danger' : 'text-green' }}">
                            <span>@{{ customer.isVisited }}</span>
                        </td>
                        <td>@{{ customer.reason ? customer.reason : 'None' }}</td>
                        <td>
                            <span ng-if="customer.is_visited == 'Yes' && customer.reason">@{{ customer.visitedAt }}</span>
                            <span ng-if="customer.is_visited == 'Yes' && !customer.reason">@{{ customer.visitedAt }}</span>
                            <span ng-if="customer.is_visited == 'No' && customer.reason">@{{ customer.visitedAt }}</span>
                            <small class="text-danger" ng-if="customer.is_visited == 'No' && customer.reason == null">
                                Visit is not marked yet
                            </small>
                        </td>
                        <td>
                            <a target="_blank" href="@{{ customer.route }}" ng-show="customer.route">
                                @{{ customer.distance ? customer.distance : 0 | number:2 }} KM
                            </a>
                            <span ng-show="!customer.route">
                            ---{{--@{{ customer.distance ? customer.distance : 0 | number:2 }} KM--}}
                        </span>
                        </td>
                        <td width="10%" class="text-right td-bg-info">@{{ getOrderTotal(customer.customer) | number:2 }}
                        </td>
                        <td width="10%" class="text-right td-bg-success">@{{ getPaid(customer.customer) | number:2
                            }}
                        </td>
                        {{--<td width="10%" class="text-right td-bg-danger">@{{ getBalanced(customer.customer) |
                            number:2 }}
                        </td>--}}
                    </tr>
                    <tr ng-show="!customers.length">
                        <td colspan="2">No Customers Found...</td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr ng-show="customers.length">
                        <td colspan="6" class="text-right td-bg-info"><b>TOTAL</b></td>
                        <td width="10%" class="text-right td-bg-success"><b>@{{
                                totalData.ordersTotal
                                |number:2 }}</b>
                        </td>
                        <td width="10%" class="text-right td-bg-success"><b>@{{
                                totalData.paymentsTotal
                                |number:2 }}</b>
                        </td>
                    </tr>
                    </tfoot>
                </table>

                <hr ng-if="customers.length > 5">
                <div class="pull-right">
                    <dir-pagination-controls pagination-id="customer_pagination"></dir-pagination-controls>
                </div>
            </div>
        </div>
    </div>
</div>