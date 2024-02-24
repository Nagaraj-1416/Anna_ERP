<td>
    <div class="form-group required">
        <div class="ui fluid  search selection dropdown product-drop-down " ng-class="hasError('product', $index) ? 'error' : ''" data-index="@{{ $index }}">
            <input type="hidden"  ng-class="hasError('product', $index) ? 'error' : ''" name="product[]">
            <i class="dropdown icon"></i>
            <div class="default text">choose an item</div>
            <div class="menu"></div>
        </div>
    </div>
    <div style="margin-top: 2px;">
        <textarea class="form-control product_notes_input"  ng-class="hasError('product_notes', $index) ? 'error' : ''" name="product_notes[]" ng-model="product.notes" rows="2" placeholder="line item related notes..."></textarea>
    </div>
</td>
<td>
    <div class="form-group required">
        <div class="ui fluid  search selection dropdown store-drop-down" ng-class="hasError('store', $index) ? 'error' : ''"   data-index="@{{ $index }}">
            <input type="hidden" name="store[]" ng-class="hasError('store', $index) ? 'error' : ''">
            <i class="dropdown icon"></i>
            <div class="default text">choose a store</div>
            <div class="menu"></div>
        </div>
    </div>
</td>
<td>
    <input type="text" class="form-control" ng-class="hasError('quantity', $index) ? 'error' : ''" ng-model="product.quantity" name="quantity[]" ng-change="changedQuantity($index)">
    <p class="text-danger">@{{ getError('quantity', $index) }}</p>
</td>
<td>
    <input type="text" class="form-control text-right" ng-readonly="diableProductRate" ng-class="hasError('rate', $index) ? 'error' : ''" ng-model="product.rate" name="rate[]" ng-change="changedValue($index)">
</td>
<td>
    <div class="ui right labeled input">
        <input  step="0.01" ng-model="product.discount_rate" name="item_discount_rate[]" ng-class="hasError('item_discount_rate', $index) ? 'error' : ''" class="item_discount_rate_input text-right" placeholder="discount" style="width: 10%;" ng-change="changedValue($index)">
        <div class="ui dropdown label item-discount-type" ng-class="hasError('item_discount_type', $index) ? 'error' : ''" data-index="@{{ $index }}">
            <input type="hidden" value="Amount" name="item_discount_type[]" ng-class="hasError('item_discount_type', $index) ? 'error' : ''">
            <div class="text">LKR</div>
            <i class="dropdown icon"></i>
            <div class="menu">
                <div data-value="Percentage" class="item">%</div>
                <div data-value="Amount" class="item">LKR</div>
            </div>
        </div>
    </div>
</td>
<td>
    <input type="text" class="form-control text-right" ng-model="product.amount" name="amount[]" ng-readonly="true">
</td>
<td>
    <button type="Button" ng-show="isRemoveable" ng-click="removeItem($index)" class="btn btn-danger remove_row_btn">
        <i class="fa fa-remove"></i>
    </button>
</td>