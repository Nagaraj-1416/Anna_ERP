<!-- list of performed sales -->
<div class="card" style="font-size: 16px;">
    <div class="card-body">
       <div class="pull-left">
           <h3 class="card-title"><b>PERFORMED CASH SALES</b></h3>
       </div>
        <div class="pull-right">
            {{--<button class="handover-btn btn btn-primary" ng-show="!(errors.error || collection.allocation.sales_handover)">
                Submit Daily Sales
            </button>--}}
        </div>
        <div class="clearfix"></div>
        <hr>
        <div class="contact-page-aside">
            <!-- .left-aside-column-->
            <div class="left-aside">
                <ul class="list-style-none">
                    <li class="text-muted">FILTER SALES BY</li>
                    <li class="divider"></li>
                </ul>
                <ul class="list-style-none">
                    <li>
                        <input class="form-control datepicker" placeholder="from" ng-model="query.from_date">
                    </li>
                </ul>
                <ul class="list-style-none m-t-10">
                    <li>
                        <input class="form-control datepicker" placeholder="to" ng-model="query.to_date">
                    </li>
                </ul>
                <ul class="list-style-none m-t-10">
                    <li>
                        <button class="btn btn-info btn-block" ng-click="fetchOrders()"><i class="fa fa-search"></i>
                            Search
                        </button>
                    </li>
                </ul>
                <ul class="list-style-none">
                    <li class="divider"></li>
                    <li class="text-muted" ng-click="resetFilters()">
                        <a class="text-primary" href="">Reset Filters</a>
                    </li>
                </ul>
            </div>
            <!-- /.left-aside-column-->
            <div class="right-aside custom-right-aside">
                <div class="row">
                    <div class="loading" ng-show="loading">
                        <div class="loading-dot"></div>
                        <div class="loading-dot"></div>
                        <div class="loading-dot"></div>
                        <div class="loading-dot"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="loading" ng-show="loading">
                        <p>loading sales</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b-20">
                        <table id="demo-foo-addrow" class="table m-t-10 table-hover no-wrap contact-list" data-page-size="10">
                            <thead>
                            <tr>
                                <th>Order details</th>
                                <th>Performed by</th>
                                <th class="text-center">No of items</th>
                                <th class="text-right">Amount</th>
                                <th class="text-right">Received</th>
                                <th class="text-right">Change Given</th>
                                {{--<th class="text-center">Action</th>--}}
                            </tr>
                            </thead>
                            <tbody class="text-muted">
                            <tr ng-repeat="order in orders">
                                <td style="vertical-align: middle;">
                                    <a target="_blank" href="/sales/order/@{{ order.id }}">
                                        @{{ order.ref }}
                                    </a><br />
                                    <p>@{{ order.created_at | date }}</p>
                                </td>
                                <td>@{{ order.prepared_by.name }}</td>
                                <td class="text-center">@{{ order.products.length }}</td>
                                <td class="text-right">@{{ order.total | number:2 }}</td>
                                <td class="text-right">@{{ order.received_cash | number:2 }}</td>
                                <td class="text-right">@{{ order.given_change | number:2 }}</td>
                                {{--<td class="text-center">
                                    <button ng-show="!edit && order.status === 'Canceled'"
                                            class="btn btn-warning btn-sm"
                                            ng-click="handleEditBtn($event)"
                                            data-id="@{{ order.id }}">Clone
                                    </button>
                                    <button ng-show="order.status !== 'Canceled'" class="btn btn-danger btn-sm"
                                            ng-click="cancelBtnClick(order.id)"
                                            data-id="@{{ order.id }}">Cancel
                                    </button>
                                </td>--}}
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    {{--<!-- pagination panel -->--}}
                    <div class="col-md-12" ng-show="checkPagination()">
                        @include('general.pagination.pagination')
                    </div>
                </div>
                <!-- if there no credit available message -->
                <div class="row" ng-hide="loading" ng-if="orders.length === 0">
                    <div class="col-md-12">
                        <div class="card text-center">
                            <div class="card-body">
                                <p class="card-text">There are <code>no</code> cash sales found.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- .left-aside-column-->
            </div>
            <!-- /.left-right-aside-column-->
        </div>
    </div>
</div>