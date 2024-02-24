<div class="card">
    <div class="card-body">
        <h3><b>VEHICLE RUNNING HISTORY</b> <span class="pull-right">Total Histories: @{{ readings.length }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Allocation</th>
                    <th class="text-center">Start reading</th>
                    <th class="text-center">End reading</th>
                    <th class="text-center">Distance</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-show="readings.length" dir-paginate="reading in readings | itemsPerPage:10"
                    pagination-id="readings_pagination">
                    <td><a target="_blank" href="/sales/allocation/@{{ reading.daily_sale.id }}">@{{
                            reading.daily_sale.code }}</a></td>
                    <td class="text-center">@{{ reading.starts_at }}</td>
                    <td class="text-center">@{{ reading.ends_at }}</td>
                    <td class="text-center">@{{ (reading.ends_at - reading.starts_at) }}</td>
                </tr>
                <tr ng-show="!readings.length">
                    <td colspan="2">No Histories Found...</td>
                </tr>
                </tbody>
            </table>

            <hr ng-if="readings.length > 9">
            <div class="pull-right">
                <dir-pagination-controls pagination-id="readings_pagination"></dir-pagination-controls>
            </div>
        </div>
    </div>
</div>