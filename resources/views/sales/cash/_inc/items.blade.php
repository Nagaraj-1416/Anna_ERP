<div class="row m-t-20">
    <div class="col-md-12">
        <div class="card card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger" ng-show="hasError('sales_items')">
                        <h5 class="text-danger">
                            <i class="fa fa-exclamation-circle"></i>
                            <small>
                                This submission requires at least one item into the list, please select items from above box to continue.
                            </small>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="row" style="font-size: 18px;">
                <div class="col-md-5">
                    <span><b>ITEMS</b></span>
                </div>
                <div class="col-md-2 text-center"><b>QTY</b></div>
                <div class="col-md-2 text-right"><b>RATE</b></div>
                <div class="col-md-2 text-right"><b>AMOUNT</b></div>
                <div class="col-md-1 text-right"></div>
            </div>
            <hr>
            <div class="sales-items" ng-show="!products.length">
                <span class="no-item-message text-warning">No sales items selected</span>
            </div>

            <!-- clone elements -->
            <div class="cloneable-sales-item" ng-repeat="(key, product) in  products" ng-show="products.length"
                 product-directive>
                <div class="row m-t-10 cloned-item">
                    <div class="col-md-5">
                        <input type="hidden" class="item_id" name="sales_items[@{{ product.id }}][id]"
                               value="@{{ product.id }}">
                        <input type="text" class="form-control item-name" readonly
                               ng-class="hasError('sales_items.'+ product.id +'.id') ? 'error' : ''"
                               name="sales_items[@{{ product.id }}][name]" value="" ng-model="product.name"
                               placeholder="item name">
                        <p class="form-control-feedback error">@{{ getErrorMsg('sales_items.'+ product.id +'.id') }}</p>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control text-center item-qty @{{ product.id + '-qty' }}"
                               ng-class="hasError('sales_items.'+ product.id +'.qty') ? 'error' : ''"
                               name="sales_items[@{{ product.id }}][qty]" placeholder="qty" ng-model="product.qty"
                               ng-change="getProductTotalAmount(product)">
                        <p class="form-control-feedback error">@{{ getErrorMsg('sales_items.'+ product.id +'.qty')
                            }}</p>
                    </div>
                    <div class="col-md-2">
                        <input type="text" readonly class="form-control text-right item-rate"
                               name="sales_items[@{{ product.id }}][retail_price]" placeholder="rate"
                               ng-model="product.selling_price"
                               ng-class="hasError('sales_items.'+ product.id +'.retail_price') ? 'error' : ''"
                               value="" ng-change="getProductTotalAmount(product)">
                        <p class="form-control-feedback error">@{{ getErrorMsg('sales_items.'+ product.id
                            +'.retail_price') }}</p>
                    </div>
                    <div class="col-md-2">
                        <input type="text" readonly class="form-control text-right item-amount"
                               name="sales_items[@{{ product.id }}][amount]" placeholder="amount"
                               ng-class="hasError('sales_items.'+ product.id +'.amount') ? 'error' : ''"
                               value="" ng-model="product.amount">
                        <p class="form-control-feedback error">@{{ getErrorMsg('sales_items.'+ product.id +'.amount')
                            }}</p>
                    </div>
                    <div class="col-md-1">
                        <button type="button"
                                class="remove-sales-item btn waves-effect waves-light btn-danger"
                                ng-click="removeProduct(key)">
                            <i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
            </div>
            <hr>
            {{--<div class="row">--}}
            {{--<div class="col-md-9">--}}
            {{--<div class="pull-right m-t-20 text-right">--}}
            {{--<h5><b>Sub total</b></h5>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-md-2">--}}
            {{--<input type="text" readonly--}}
            {{--class="form-control text-right m-t-10 sales-sub-total"--}}
            {{--name="sales_sub_total" placeholder="0.00" value="@{{ getTotal() | number:2 }}">--}}
            {{--</div>--}}
            {{--<div class="col-md-1"></div>--}}
            {{--</div>--}}
        </div>
    </div>
</div>