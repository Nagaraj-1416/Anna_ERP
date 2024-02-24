<div class="card">
    <div class="card-body">
        <div class="pull-left">
            <h3 style="display:inline"><b>STOCK HISTORIES (IN / OUT)</b>
            </h3>
            <a href="{{ route('sales.allocation.product.history.export', [$allocation]) }}"
               class="btn waves-effect waves-light btn-pdf btn-sm m-b-5">
                <i class="fa fa-file-pdf-o"></i> PDF
            </a>
        </div>
        <div class="pull-right">
            <h3><span>Total Histories: @{{ productHistories.length }}</span></h3>
        </div>
        <div class="clearfix">
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Product details</th>
                    <th>Description</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-center">Transaction</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-show="productHistories.length" dir-paginate="history in productHistories | itemsPerPage:10"
                    pagination-id="history_pagination">
                    <td>@{{ history.createdAt }}</td>
                    <td>
                        <a target="_blank" href="/stock-summary/stock/@{{ history.stock.id }}">
                            @{{ history.stock && history.stock.product ? history.stock.product.name + ' (' +
                            history.stock.product.code+ ')' : '' }}
                        </a>
                    </td>
                    <td>@{{ history.trans_description }}</td>
                    <td class="text-center">@{{ history.quantity }}</td>
                    <td class="text-center" ng-class="history.transaction == 'Out' ? 'text-danger' : 'text-green'">@{{ history.transaction }}</td>
                </tr>
                <tr ng-show="!productHistories.length">
                    <td colspan="2">No Histories Found...</td>
                </tr>
                </tbody>
            </table>

            <hr ng-if="productHistories.length > 5">
            <div class="pull-right">
                <dir-pagination-controls pagination-id="history_pagination"></dir-pagination-controls>
            </div>
        </div>
    </div>
</div>