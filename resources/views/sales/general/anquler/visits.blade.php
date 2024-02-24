<div class="card">
    <div class="card-body">
        <h3><b>SALES VISITS</b> <span class="pull-right">Total Visits: @{{ visits.length }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                    <tr>
                        <th>Allocation</th>
                        <th>Rep</th>
                        <th>Visited?</th>
                        <th>Visit remarks</th>
                        <th>Visited at</th>
                        <th>Distance</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-show="visits.length" dir-paginate="visit in visits | itemsPerPage:5" pagination-id="visits_pagination">
                        <td>
                            <a target="_blank" href="/sales/allocation/@{{ visit.allocation_id }}">
                                @{{ visit.allocation_code }} (@{{ visit.allocation_range }})
                            </a>
                        </td>
                        <td>@{{ visit.rep }}</td>
                        <td>@{{ visit.is_visited }}</td>
                        <td>@{{ visit.reason }}</td>
                        <td>@{{ visit.visitedAt }}</td>
                        <td>
                            <a href="#" target="_blank" ng-if="visit.distance">
                                @{{ visit.distance ? visit.distance : 0 | number:2 }} KM
                            </a>
                            <span ng-if="!visit.distance">N/A</span>
                        </td>
                    </tr>
                    <tr ng-show="!visits.length ">
                        <td>No Visits Found...</td>
                    </tr>
                </tbody>
            </table>
            <hr ng-if="visits.length > 5">
            <div class="pull-right">
                <dir-pagination-controls pagination-id="visits_pagination"></dir-pagination-controls>
            </div>
        </div>
    </div>
</div>