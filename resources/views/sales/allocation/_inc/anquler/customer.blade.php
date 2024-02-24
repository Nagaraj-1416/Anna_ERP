<div class="card">
    <div class="card-body">
        <div class="pull-left">
            <h3 style="display:inline"><b>CUSTOMERS</b>
            </h3>
            <a href="{{ route('sales.allocation.customer.export', [$allocation]) }}"
               class="btn waves-effect waves-light btn-pdf btn-sm m-b-5">
                <i class="fa fa-file-pdf-o"></i> PDF
            </a>
            <a href="{{ route('sales.allocation.customer.export', ['allocation' => $allocation, 'type' => 'excel']) }}"
               class="btn waves-effect waves-light btn-excel btn-sm m-b-5">
                <i class="fa fa-file-excel-o"></i> Excel
            </a>
        </div>
        <div class="pull-right ">
            <h3><span>Total Customers: @{{ customers.length }}</span></h3>
        </div>
        <div class="clearfix">

        </div>
        <hr>
        <div class="m-b-10">
            <input type="text" style="margin-left: 0 !important;"
                   ng-model="customerSearch" placeholder="type your keywords here and search for customers"
                   class="form-control" ng-change="getCustomers()"
                   autocomplete="off">
        </div>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Customer details</th>
                    <th>Outstanding</th>
                    <th class="text-center">Is route customer?</th>
                    <th class="text-center">Is visited?</th>
                    <th>Visit remarks</th>
                    <th>Visited at</th>
                    <th>Distance</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-show="customers.length" dir-paginate="customer in customers | itemsPerPage:10"
                    pagination-id="customer_pagination"
                    ng-class="customer.is_visited == 'No' && customer.reason != null ? 'td-bg-danger' : '' ||
                    customer.is_visited == 'Yes' && customer.reason != null ? 'td-bg-success' : ''" customer-directive>
                    <td>
                        <a target="_blank" href="/sales/customer/@{{ customer.customer.id }}">
                            @{{ customer.customer.display_name }}
                        </a>
                        <small ng-if="customer.added_stage == 'Later'" class="text-danger"><br />Added during the sales</small>
                    </td>
                    <td ng-class="customer.customer.total_outstanding != 0 ? 'text-danger' : '' ">
                        @{{ customer.customer.total_outstanding | number:2 }}
                    </td>
                    <td class="@{{ customer.route_customer === 'Yes' ? 'text-green' : 'text-danger' }} text-center">@{{
                        customer.route_customer }}
                    </td>
                    <td class="text-center @{{ customer.is_visited === 'No' ? 'text-danger' : 'text-green' }}">@{{
                        customer.is_visited }}
                    </td>
                    <td>@{{ customer.reason ? customer.reason : 'None' }}</td>
                    <td>
                        <span ng-if="customer.is_visited == 'Yes' && customer.reason">@{{ customer.visitedAt }}</span>
                        <span ng-if="customer.is_visited == 'Yes' && !customer.reason">@{{ customer.visitedAt }}</span>
                        <span ng-if="customer.is_visited == 'No' && customer.reason">@{{ customer.visitedAt }}</span>
                        <small class="text-danger" ng-if="customer.is_visited == 'No' && customer.reason == null">Visit
                            is not marked yet
                        </small>
                    </td>
                    <td>
                        <a href="@{{ customer.route }}" target="_blank" ng-show="customer.route">
                            @{{ customer.distance ? customer.distance : 0 | number:2 }} KM
                        </a>
                        <span ng-show="!customer.route">
                         ---
                        </span>
                    </td>
                </tr>
                <tr ng-show="!customers.length">
                    <td colspan="2">No Customers Found...</td>
                </tr>
                </tbody>
            </table>

            <hr ng-if="customers.length > 5">
            <div class="pull-right">
                <dir-pagination-controls pagination-id="customer_pagination"></dir-pagination-controls>
            </div>
        </div>

        <div id="map" style="width: 100%; height: 600px;"></div>
    </div>
</div>