<div class="row" ng-show="shop">
    <div class="col-md-12">
        <div class="ribbon-wrapper card">
            <div class="ribbon ribbon-default">@{{ shop.name }}</div>
            <div class="row">
                <div class="col-md-12">
                    <div class="cardScroll">
                        <table class="table table-scroll">
                            <thead>
                                <tr>
                                    <th class="table-active" style="width: 200px;">PRODUCTS</th>
                                    <th class="text-center table-info">CF QTY</th>
                                    <th class="text-center table-info">ALLOCATED QTY</th>
                                    <th class="text-center table-warning">SOLD QTY</th>
                                    <th class="text-center table-warning">REPLACED QTY</th>
                                    <th class="text-center table-danger">RETURNED QTY</th>
                                    <th class="text-center table-danger">SHORTAGE QTY</th>
                                    <th class="text-center table-danger">DAMAGED QTY</th>
                                    <th class="text-center table-warning">RESTORED QTY</th>
                                    <th class="text-center table-success">AVAILABLE QTY</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="item in items">
                                    <td style="width: 200px;">@{{ item.product.name }}</td>
                                    <td class="text-center">@{{ item.cf_qty ? item.cf_qty : 0}}</td>
                                    <td class="text-center">@{{ item.quantity ? item.quantity : 0 }}</td>
                                    <td class="text-center">@{{ item.sold_qty ? item.sold_qty : 0 }}</td>
                                    <td class="text-center">@{{ item.replaced_qty ? item.replaced_qty : 0 }}</td>
                                    <td class="text-center">@{{ item.returned_qty ? item.returned_qty : 0 }}</td>
                                    <td class="text-center">@{{ item.shortage_qty ? item.shortage_qty : 0 }}</td>
                                    <td class="text-center">@{{ item.damaged_qty ? item.damaged_qty : 0 }}</td>
                                    <td class="text-center">@{{ item.restored_qty ? item.restored_qty : 0 }}</td>
                                    <td class="text-center">
                                        @{{ (item.quantity + item.cf_qty + item.returned_qty) - (item.sold_qty + item.restored_qty + item.replaced_qty + item.shortage_qty) }}
                                    </td>
                                </tr>
                                <tr ng-show="!items">
                                    <td colspan="10"><code>No stock available in this shop!</code></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row" ng-show="!shop && !loading">
    <div class="col-md-12">
        <span class="text-muted">Please choose the shop to generate the stock report</span>
    </div>
</div>