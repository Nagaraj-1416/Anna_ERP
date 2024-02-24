<div class="card border-default">
    <div class="card-body">
        <div class="d-flex no-block">
            <h4 class="card-title text-megna">Top 10 Products by Purchase</h4>
            <div class="ml-auto"></div>
        </div>
        <h6 class="card-subtitle">{{ carbon()->now()->format('F j, Y') }}</h6>
        <hr>
        <div class="table-responsive">
            <table class="table stylish-table">
                <thead>
                <tr>
                    <th>Product Details</th>
                    <th class="text-right">Purchase</th>
                </tr>
                </thead>
                <tbody>
                <tr dir-paginate="product in topProducts | itemsPerPage:6" pagination-id="prod">
                    <td>
                        <h6>
                            <a href="javascript:void(0)" class="link">@{{ product.name }}</a>
                        </h6>
                        <small class="text-muted">@{{ product.code }}</small>
                    </td>
                    <td class="text-right"><h6>@{{ product.total_amount | number: 2 }}</h6></td>
                </tr>
                </tbody>
            </table>
        </div>
        <hr ng-if="topProducts.length > 5">
        <div class="pull-right">
            <dir-pagination-controls pagination-id="prod"></dir-pagination-controls>
        </div>
    </div>
</div>