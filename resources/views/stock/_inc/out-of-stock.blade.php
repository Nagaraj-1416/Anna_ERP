<div class="card border-danger">
    <div class="card-body">
        <div class="d-flex no-block">
            <h4 class="card-title text-danger">Out Of Stock Items</h4>
            <div class="ml-auto"></div>
        </div>
        <h6 class="card-subtitle">{{ carbon()->format('F d, Y') }}</h6>
        <div class="table-responsive">
            <table class="table stylish-table">
                <thead>
                <tr>
                    <th>Product Description</th>
                    <th class="text-center">Available</th>
                    <th class="text-center">Reorder Level</th>
                </tr>
                </thead>
                <tbody>
                <tr dir-paginate="stock in outOfStockItems | itemsPerPage:10" pagination-id="out_of_item_pagination"
                    ng-show="outOfStockItemsCount">
                    <td>
                        <h6>
                            <a href="/stock-summary/stock/@{{ stock.id }}" target="_blank" class="link">
                                @{{ stock.product.name }} & @{{ stock.product.type }}
                            </a>
                        </h6>
                        <small class="text-muted">Product code : @{{ stock.product.code }}</small>
                    </td>
                    <td class="text-center"><h5>@{{ stock.available_stock }}</h5></td>
                    <td class="text-center"><h5>@{{ stock.min_stock_level }}</h5></td>
                </tr>
                <tr ng-hide="outOfStockItemsCount">
                    <td colspan="3">
                        <p>No Stocks Found...</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <hr ng-if="outOfStockItemsCount > 10">
        <div class="pull-right">
            <dir-pagination-controls pagination-id="out_of_item_pagination"></dir-pagination-controls>
        </div>
    </div>
</div>