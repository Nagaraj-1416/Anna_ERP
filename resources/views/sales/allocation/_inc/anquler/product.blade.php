<div class="card">
    <div class="card-body">
        <div class="pull-left">
            <h3 style="display:inline"><b>PRODUCTS</b>
            </h3>
            <a href="{{ route('sales.allocation.product.export', [$allocation]) }}"
               class="btn waves-effect waves-light btn-pdf btn-sm m-b-5">
                <i class="fa fa-file-pdf-o"></i> PDF
            </a>
            <a href="{{ route('sales.allocation.product.export', ['allocation' => $allocation, 'type' => 'excel']) }}"
               class="btn waves-effect waves-light btn-excel btn-sm m-b-5">
                <i class="fa fa-file-pdf-o"></i> Excel
            </a>
        </div>
        <div class="pull-right">
            <h3><span>Total Products: @{{ products.length }}</span></h3>
        </div>
        <div class="clearfix">
        </div>
        <hr>
        <div class="m-b-10">
            <input type="text" style="margin-left: 0 !important;"
                   ng-model="productSearch" placeholder="type your keywords here and search for products"
                   class="form-control" ng-change="getProducts()"
                   autocomplete="off">
        </div>
        <table class="table color-table muted-table">
            <thead>
            <tr>
                <th>Product details</th>
                @if($allocation->sales_location == 'Van')
                    <th class="text-center">Is route product?</th>
                @endif
                <th class="text-center">CF Qty</th>
                <th class="text-center">Allocated Qty</th>
                <th class="text-center">Sold Qty</th>
                @if($allocation->sales_location == 'Van')
                    <th class="text-center">Replaced Qty</th>
                @endif
                <th class="text-center">Returned Qty</th>
                <th class="text-center">Shortage Qty</th>
                <th class="text-center">Excess Qty</th>
                <th class="text-center">Damaged Qty</th>
                <th class="text-center">Restored Qty</th>
                <th class="text-center">Available Qty</th>
                <th class="text-center"></th>
            </tr>
            </thead>
            <tbody>
            <tr ng-show="products.length" dir-paginate="product in products | itemsPerPage:10"
                pagination-id="product_pagination" ng-class="product.added_stage == 'Later' ? 'td-bg-info' : ''">
                <td>
                    <a target="_blank" href="/setting/product/@{{ product.product.id }}">
                        @{{ product.product.name }}
                    </a>
                    <small ng-if="product.added_stage == 'Later'" class="text-danger">
                        <br/>Added during the sales
                    </small>
                    <div class="mytooltip tooltip-effect-1" ng-if="product.added_stage == 'Later'">
                        <br/>
                        <span class="tooltip-item btn-sm" style="padding: 5px;"><i class="fa fa-history"></i> History</span>
                        <div class="tooltip-content clearfix" style="margin: 2px 0 20px 0;">
                            <div class="tooltip-text" style="padding: 0; !important;">
                                <table class="table btn-sm" style="border-radius: 0 !important;">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th class="text-center">QTY</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="border-bottom: solid 3px #00887b;">
                                            <td><b>Carry Forwarded</b></td>
                                            <td class="text-center">@{{ product.cf_qty ? product.cf_qty : 0 }}</td>
                                        </tr>
                                        <tr ng-repeat="history in product.histories">
                                            <td>&nbsp&nbsp&nbsp&nbsp&nbsp@{{ history.transaction }}</td>
                                            <td class="text-center">@{{ history.quantity }}</td>
                                        </tr>
                                        <tr style="border-top: solid 3px #00887b;">
                                            <td><b>Total Available</b></td>
                                            <td class="text-center">
                                                @{{ (product.quantity + product.cf_qty + product.returned_qty) - (product.sold_qty + product.restored_qty +
                    product.replaced_qty + product.shortage_qty) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </td>
                @if($allocation->sales_location == 'Van')
                    <td class="@{{ product.route_product === 'Yes' ? 'text-green' : 'text-danger' }} text-center">
                        @{{
                        product.route_product }}
                    </td>
                @endif
                <td class="text-center text-info">@{{ product.cf_qty ? product.cf_qty : 0 }}</td>
                <td class="text-center text-success">
                    @{{ product.quantity ? product.quantity : 0}}
                </td>
                <td class="text-center text-warning">@{{ product.sold_qty ? product.sold_qty : 0 }}</td>
                @if($allocation->sales_location == 'Van')
                    <td class="text-center text-warning">@{{ product.replaced_qty ? product.replaced_qty : 0 }}</td>
                @endif
                <td class="text-center text-danger">@{{ product.returned_qty ? product.returned_qty : 0 }}</td>
                <td class="text-center text-danger">@{{ product.shortage_qty ? product.shortage_qty : 0 }}</td>
                <td class="text-center text-warning">@{{ product.excess_qty ? product.excess_qty : 0 }}</td>
                <td class="text-center text-danger">@{{ product.damaged_qty ? product.damaged_qty : 0 }}</td>
                <td class="text-center text-danger">@{{ product.restored_qty ? product.restored_qty : 0 }}</td>
                {{--<td class="text-center text-green">
                    @{{ (product.quantity + product.cf_qty + product.returned_qty) - (product.sold_qty + product.restored_qty +
                    product.replaced_qty + product.shortage_qty + product.damaged_qty) }}
                </td>--}}
                <td class="text-center text-green">
                    @{{ (product.quantity + product.cf_qty + product.returned_qty + product.excess_qty) - (product.sold_qty + product.restored_qty +
                    product.replaced_qty + product.shortage_qty) }}
                </td>
                <td>
                    @if(!isNextDayAllocationAvailable($allocation))
                        @if($handover)
                            @if(isCashierLevelStaff() || isDirectorLevelStaff() || isAccountLevelStaff())
                                <a target="_blank" href="/sales/allocation/{{ $allocation->id }}/item/@{{ product.id }}/restore/stock" class="btn btn-danger btn-sm">
                                    <i class="ti-back-left"></i>
                                </a>
                            @endif
                        @endif
                    @endif
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