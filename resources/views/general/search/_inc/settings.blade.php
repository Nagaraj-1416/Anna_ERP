<div class="row">
    {{--  products --}}
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4" ng-show="products.length">
                <div class="ribbon-wrapper card ">
                    <div class="ribbon ribbon-success">PRODUCTS ( @{{ products.length }} )</div>
                    <table class="table custom-table m-t-10">
                        <tbody>
                        <tr dir-paginate="product in products | itemsPerPage:5"
                            pagination-id="products_paginate">
                            <td>
                                <a target="_blank" href="/setting/product/@{{ product.id }}">
                                    @{{ product.code }}
                                </a><br>
                                <small class="text-muted"> @{{ product.name }}</small>
                            </td>
                            <td class="text-right">
                                @{{ product.type }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <hr ng-if="products.length > 5">
                    <div class="pull-right">
                        <dir-pagination-controls
                                pagination-id="products_paginate"></dir-pagination-controls>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>