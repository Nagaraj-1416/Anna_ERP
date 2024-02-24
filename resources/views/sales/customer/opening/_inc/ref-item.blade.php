<td>
    <div class="form-group required">
        <div class="ui fluid  search selection dropdown product-drop-down" ng-class="getProductError(productIndex, 'product_id') ? 'error' : ''" data-index="@{{ key }}">
            <input type="hidden">
            <i class="dropdown icon"></i>
            <div class="default text">choose a product</div>
            <div class="menu"></div>
        </div>
    </div>
    <span class="text-danger m-t-5">@{{ getProductError(productIndex, 'product_id') }}</span>
</td>
<td>
    <input type="text" class="form-control text-right" ng-class="getProductError(productIndex, 'quantity') ? 'error' : ''"  ng-model="product.quantity" ng-change="changedQuantity(productIndex, refIndex)">
    <span class="text-danger m-t-5">@{{ getProductError(productIndex, 'quantity') }}</span>
</td>
<td>
    <input type="text" class="form-control text-right"  ng-class="getProductError(productIndex, 'rate') ? 'error' : ''"  ng-model="product.rate"  ng-change="changedRate(productIndex, refIndex)">
    <span class="text-danger m-t-5">@{{ getProductError(productIndex, 'rate') }}</span>
</td>
<td>
    <input readonly type="text" class="form-control text-right"  ng-model="product.total">
</td>
<td>
    <span class="btn btn-danger" ng-click="removeProduct($index)" ng-show="productDeletable">
        <i class="mdi mdi-delete"></i>
    </span>
</td>
