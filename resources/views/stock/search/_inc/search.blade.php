<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12" ng-show="productChooesed">
                <table class="table table-scroll">
                    <thead>
                    <tr>
                        <th class="table-active">Store / Sales Location</th>
                        <th class="text-center table-info">Available Qty</th>
                        <th class="text-center table-warning">Last In</th>
                        <th class="text-center table-danger">Last Out</th>
                        <th class="text-center table-success">Last Transaction At</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="(key, stoke) in stokes">
                        <td>@{{ stoke.name }}</td>
                        <td class="text-right text-info">@{{ stoke.available_stock }}</td>
                        <td class="text-right text-warning">@{{ stoke.last_in }}</td>
                        <td class="text-right text-danger">@{{ stoke.last_out }}</td>
                        <td class="text-center text-green">@{{ stoke.last_transaction_at }}</td>
                    </tr>
                    <tr ng-show="stokes.length == 0 && productChooesed">
                        <td colspan="5">There are no history found.</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-12" ng-show="!productChooesed">
                <span class="text-muted">Please choose the product to generate the stock report</span>
            </div>
        </div>
    </div>
</div>
