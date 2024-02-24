<div class="card">
    <div class="card-body">
        <div class="pull-left">
            <h3 style="display:inline"><b>PRODUCTS</b>
            </h3>
            @if(isset($exportRoute))
                <a href="{{ $exportRoute }}"
                   class="btn waves-effect waves-light btn-pdf btn-sm m-b-5">
                    <i class="fa fa-file-pdf-o"></i> PDF
                </a>
            @endif
            @if(isset($excelExport))
                <a href="{{ $excelExport }}"
                   class="btn waves-effect waves-light btn-excel btn-sm m-b-5">
                    <i class="fa fa-file-pdf-o"></i> Excel
                </a>
            @endif
        </div>
        <div class="pull-right">
            <h3><span class="pull-right">Total Products: @{{ products.length }}</span></h3>
        </div>
        <div class="clearfix">
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th style="width: 20%; text-align: center;">Default Allocation Qty</th>
                    <th></th>
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
                    <td class="text-center">@{{ product.pivot.default_qty }}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-danger" ng-click="removeProduct(product)">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </td>
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
