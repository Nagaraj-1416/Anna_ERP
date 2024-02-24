<div id="stats-sidebar" class="card card-outline-inverse disabled-dev" ng-controller="StatsController" style="border: none !important;">
    <div class="stats-preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
        </svg>
    </div>
    <div class="card-header ">
        <h4 class="m-b-0 text-white">@{{ mainHeader }}</h4>
        <h6 class="card-subtitle text-white"><span ng-show="subHeader">@{{ subHeader }} <b> : </b></span> <b
                    style="font-size: 20px;"> @{{ total | number:2 }}</b>
        </h6>
    </div>
    <div class="card-body" id="list-stats-body">

        <div class="row" ng-show="header_section">
            <div class="col-md-3">
                <a target="_blank" class="btn btn-excel btn-sm" href="/sales/allocation/@{{ allocation.id }}/sales-sheet">
                    <i class="fa fa-book"></i> View Sales Sheet
                </a>
                <table class="table custom-table m-t-10">
                    <tbody>
                        <tr style="font-size: 16px;">
                            <td class="td-bg-default text-megna"><b>ORDERS </b></td>
                            <td class="text-megna"><b>@{{ allocation.orders.length }}</b></td>
                        </tr>
                        <tr>
                            <td class="td-bg-info text-info"><b>PRODUCTS: </b></td>
                            <td class="text-info"><b>@{{ allocation.items.length }}</b></td>
                        </tr>
                        <tr>
                            <td><b>Rep</b></td>
                            <td>@{{ allocation.rep.name }}</td>
                        </tr>
                        <tr>
                            <td><b>Route</b></td>
                            <td>@{{ allocation.route.name }}</td>
                        </tr>
                        <tr>
                            <td><b>Prepared by</b></td>
                            <td>@{{ allocation.prepared_by.name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-3">
                <table class="table custom-table" style="margin-top: 36px;">
                    <tbody>
                    <tr>
                        <td class="td-bg-warning text-warning" width="30%"><b>CUSTOMERS: </b></td>
                        <td class="text-warning"><b>@{{ allocation.customers.length }}</b></td>
                    </tr>
                    <tr>
                        <td><b>Vehicle</b></td>
                        <td>@{{ allocation.vehicle.vehicle_no }}</td>
                    </tr>
                    <tr>
                        <td><b>Driver</b></td>
                        <td>@{{ allocation.driver.short_name }}</td>
                    </tr>
                    <tr>
                        <td><b>Labour</b></td>
                        <td>@{{ allocation.labour.short_name }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-3">
                <table class="table custom-table" style="margin-top: 36px;">
                    <tbody>
                    <tr>
                        <td class="td-bg-success text-green" width="35%"><b>VISITED: </b></td>
                        <td class="text-green"><b>@{{ allocation.visited_customers }}</b></td>
                    </tr>
                    <tr>
                        <td><b>ODO starts at</b></td>
                        <td>@{{ allocation.odo_meter_reading.starts_at }}</td>
                    </tr>
                    <tr>
                        <td><b>ODO ends at</b></td>
                        <td>@{{ allocation.odo_meter_reading.ends_at }}</td>
                    </tr>
                    <tr>
                        <td><b>Total travel</b></td>
                        <td>@{{ allocation.odo_meter_reading.ends_at ? (allocation.odo_meter_reading.ends_at - allocation.odo_meter_reading.starts_at) : 'N/A' }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-md-3">
                <table class="table custom-table" style="margin-top: 36px;">
                    <tbody>
                    <tr>
                        <td class="td-bg-danger text-danger" width="35%"><b>NOT VISITED: </b></td>
                        <td class="text-danger"><b>@{{ allocation.not_visited_customers }}</b></td>
                    </tr>
                    <tr>
                        <td><b>Sales starts at</b></td>
                        <td>@{{ allocation.sales_starts_at }}</td>
                    </tr>
                    <tr>
                        <td><b>Sales ends at</b></td>
                        <td>@{{ allocation.sales_ends_at }}</td>
                    </tr>
                    <tr>
                        <td><b>Total sales time</b></td>
                        <td>@{{ allocation.sales_ends_at != 'None' ? allocation.sales_time : 'N/A' }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="demo-foo-addrow" class="table table-hover no-wrap contact-list"
                           data-page-size="10">
                        <thead>
                        <tr>
                            <th class="table-success" ng-class="(column === 'dueAmount' || column === 'cashSales'
                            || column === 'chequeSales' || column === 'depositSales' || column === 'cardSales' || column === 'total_received' || column === 'balance') ? 'text-right' : ''"
                                ng-repeat="(key, column) in columns">
                                @{{ key }}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr dir-paginate="value in data | itemsPerPage:15" ng-show="getLength(data)"
                            current-page="myCurrentPage" stats-directive>
                            <td ng-class="(column === 'dueAmount' || column === 'cashSales'
                            || column === 'chequeSales' || column === 'depositSales' || column === 'cardSales' || column === 'total_received' || column === 'balance') ? 'text-right' : ''"
                                ng-repeat="(key, column) in columns">
                                <a target="_blank" href="@{{ value.relation_route }}"
                                   ng-show="value.relationColumn === column">
                                    @{{ value[column] }}
                                </a>
                                <a target="_blank" href="@{{ value.showRoute }}" ng-show="value.showColumn === column">
                                    @{{ value[column] }}
                                </a>
                                <span ng-show="value.showColumn !== column && value.relationColumn !== column && column !== 'distance'"
                                      class="@{{ (column === 'status' || column === 'is_credit_sales') ? statusLabelColor(value[column]) : '' }}">
                                    @{{ value[column] }}
                                </span>
                                <a target="_blank" href="@{{ value['distance_show_route'] }}"
                                   ng-show="column === 'distance'">
                                    @{{ value[column] }}
                                </a>
                            </td>
                        </tr>
                        <tr ng-hide="getLength(data)">
                            <td colspan="getLength(columns)">
                                <p>No data to display...</p>
                            </td>
                        </tr>
                        </tbody>
                        <tfoot>
                            <tr ng-show="getLength(total_columns)">
                                <td colspan="@{{ getLength(columns, getLength(total_columns))  }}"
                                    class="text-right table-primary"><b>Total</b>
                                </td>
                                <td ng-repeat="(key, column) in total_columns" class="table-warning text-right">
                                    <b>@{{ column }}</b>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <hr ng-if="data.length > 10">
                    <div class="pull-right">
                        <dir-pagination-controls></dir-pagination-controls>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>