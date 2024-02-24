<div class="card">
    <div class="card-body">
        <h3><b>RETURNS</b> <span class="pull-right">Total Returns: @{{ returns.length }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                    <tr>
                        <th>Allocation</th>
                        <th>Return no</th>
                        <th>Returned date</th>
                        <th>No of items</th>
                        {{--<th>Status</th>--}}
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-show="returns.length" dir-paginate="return in returns | itemsPerPage:5" pagination-id="returns_pagination">
                        <td>
                            <a target="_blank" href="/sales/allocation/@{{ return.allocation_id }}">
                                @{{ return.allocation_code }} (@{{ return.allocation_range }})
                            </a>
                        </td>
                        <td>
                            <a target="_blank" href="/sales/return/@{{ return.id }}">
                                @{{ return.code }}
                            </a>
                        </td>
                        <td>@{{ return.date }}</td>
                        <td>@{{ return.no_of_items }}</td>
                        {{--<td>
                            <span ng-class="statusLabelColor(return.status) ">@{{ return.status }}</span>
                        </td>--}}
                        <td class="text-right">@{{ return.return_amount | number:2 }}</td>
                    </tr>
                    <tr ng-show="!returns.length ">
                        <td>No Returns Found...</td>
                    </tr>
                </tbody>
            </table>
            <hr ng-if="returns.length > 5">
            <div class="pull-right">
                <dir-pagination-controls pagination-id="returns_pagination"></dir-pagination-controls>
            </div>
        </div>
    </div>
</div>