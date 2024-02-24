<td>
    <div class="form-group required">
        <div class="ui fluid  search selection dropdown product-drop-down" ng-class="hasError('product_id', $index) ? 'error' : ''" data-index="@{{ $index }}">
            <input type="hidden"  ng-class="hasError('product', $index) ? 'error' : ''" name="product_id[]">
            <i class="dropdown icon"></i>
            <div class="default text">choose an item</div>
            <div class="menu"></div>
        </div>
    </div>
</td>
<td>
    <input type="text" placeholder="line item related notes..." class="form-control" ng-class="hasError('product_notes', $index) ? 'error' : ''" ng-model="product.notes" name="product_notes[]">
</td>
<td>
    <input type="text" class="form-control" ng-class="hasError('quantity', $index) ? 'error' : ''" ng-model="product.quantity" name="quantity[]">
</td>
<td>
    <input type="text" placeholder="pick delivery date" class="form-control delivery-date" ng-class="hasError('delivery_date', $index) ? 'error' : ''" ng-model="product.delivery_date" name="delivery_date[]">
</td>
<td>
    <button type="Button" ng-show="isRemoveable" ng-click="removeItem($index)" class="btn btn-danger remove_row_btn">
        <i class="fa fa-remove"></i>
    </button>
</td>