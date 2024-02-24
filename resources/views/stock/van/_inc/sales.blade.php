<div class="row" ng-show="getLength(vehicles)">
    <div class="col-md-6" ng-repeat="vehicle in vehicles" card-directive>
        <div class="ribbon-wrapper card">
            <div class="ribbon ribbon-default">@{{ vehicle.vehicle_no }}</div>
            <div class="row">
                <div class="col-md-12">
                    <div class="m-b-10 m-r-5">
                        <input type="text" id="demo-input-search2" ng-model="vehicle.search"
                               placeholder="search for product here" class="form-control"
                               ng-change="searchProduct(vehicle, this)">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="cus-create-preloader">
                        <svg class="circular" viewBox="25 25 50 50">
                            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                                    ng-show="searchingObject[vehicle.id]"
                                    stroke-miterlimit="10"/>
                        </svg>
                    </div>
                    <div class="cardScroll" ng-show="!searchingObject[vehicle.id]">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Products</th>
                                <th class="text-center td-bg-info">Allocated Qty</th>
                                <th class="text-center td-bg-warning">Sold Qty</th>
                                <th class="text-center td-bg-warning">Replaced Qty</th>
                                <th class="text-center td-bg-danger">Restored Qty</th>
                                <th class="text-center td-bg-success">Available Qty</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="(key, product) in getProductData(vehicle)">
                                <td>
                                    <a target="_blank" href="/setting/product/@{{ getProduct(key).id }}">@{{ getProduct(key).name }}</a>
                                </td>
                                <td class="text-center text-info">@{{ getProductStats(product, 'quantity') }}</td>
                                <td class="text-center text-warning">@{{ getProductStats(product, 'sold_qty') }}</td>
                                <td class="text-center text-warning">@{{ getProductStats(product, 'replaced_qty') }}</td>
                                <td class="text-center text-danger">@{{ getProductStats(product, 'restored_qty') }}</td>
                                <td class="text-center text-green">@{{ getAvailableQty(product) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row" ng-show="!getLength(vehicles) && !loading">
    <div class="col-md-12">
        <p>No vehicle stock available...</p>
    </div>
</div>