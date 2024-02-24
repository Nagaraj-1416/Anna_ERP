<div class="card">
    <div class="card-body">
        <h3><b>PRODUCTS</b> <span class="pull-right">Total Products: @{{ products.length }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th style="width: 20%; text-align: center;">Available Qty</th>
                    <th style="width: 20%; text-align: center;">Default Allocation Qty</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-show="products.length" dir-paginate="product in products | itemsPerPage:10"
                    pagination-id="product_pagination">
                    <td>
                        <a target="_blank" href="/setting/product/@{{ product.id }}">
                            @{{ product.code }}
                        </a>
                    </td>
                    <td>@{{ product.name }}</td>
                    <td>@{{ product.type }}</td>
                    <td class="text-center">@{{ product.stock.available_stock ? product.stock.available_stock : 0}}</td>
                    <td class="text-center">@{{ product.pivot.default_qty }}</td>
                </tr>
                <tr ng-show="!products.length">
                    <td colspan="2">No Products Found...</td>
                </tr>
                </tbody>
            </table>

            <hr ng-if="products.length > 5">
            <div class="pull-right">
                <dir-pagination-controls pagination-id="product_pagination"></dir-pagination-controls>
            </div>
        </div>
    </div>
</div>