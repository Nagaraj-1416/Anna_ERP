<div class="row" ng-show="store">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-2">
                <p class="text-info">Total stocks: <b>@{{ stocks.length }}</b></p>
            </div>
            <div class="col-md-2">
                <p class="text-danger">Out of stocks: <b>@{{ noStocks }}</b></p>
            </div>
        </div>
        <div class="ribbon-wrapper card">
            <div class="ribbon ribbon-default">@{{ store.name }}</div>
            <div class="row">
                <div class="col-md-12">
                    <div class="m-b-10 m-r-5">
                        <input type="text" id="demo-input-search2" ng-model="query.searchQuery"
                               placeholder="search for product here" class="form-control" ng-change="searchProduct()">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="cardScroll">
                        <table class="table table-scroll">
                            <thead>
                            <tr>
                                <th class="table-active">Products</th>
                                <th class="text-center table-info">Min stock level</th>
                                <th class="text-center table-success">IN Stock</th>
                                <th class="text-center table-warning">OUT Stock</th>
                                <th class="text-center table-success">Available</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="(key, stock) in stocks"
                                ng-class="stock.stock_as_at == 0 ? 'td-bg-danger' : ''">
                                <td>
                                    <a target="_blank" href="/stock-summary/stock/@{{ stock.id }}">@{{ stock.product.name }}</a>
                                </td>
                                <td class="text-center text-info">@{{ stock.min_stock_level }}</td>
                                <td class="text-center text-green">@{{ stock.in_stock_as_at }}</td>
                                <td class="text-center text-warning">@{{ stock.out_stock_as_at }}</td>
                                <td class="text-center text-green">@{{ stock.stock_as_at }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row" ng-show="!store && !loading">
    <div class="col-md-12">
        <span class="text-muted">Please choose the store to generate the stock report</span>
    </div>
</div>