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
        <div class="ui fluid action input">
            <div class="ui fluid search selection dropdown brand-drop-down" ng-class="hasError('brand', $index) ? 'error' : ''" data-index="@{{ $index }}">
                <input type="hidden"  ng-class="hasError('brand', $index) ? 'error' : ''" name="brand[]">
                <i class="dropdown icon"></i>
                <div class="default text">choose a brand</div>
                <div class="menu"></div>
            </div>
            <button type="button" class="ui blue right icon button brand-drop-down-add-btn" data-index="@{{ $index }}">
                <i class="fa fa-plus"></i>
            </button>
        </div>
    </div>
</td>
<td>
    {{--<div class="form-group required">
        <div class="ui fluid  search selection dropdown store-drop-down" ng-class="hasError('store', $index) ? 'error' : ''"   data-index="@{{ $index }}">
            <input type="hidden" name="store[]" ng-class="hasError('store', $index) ? 'error' : ''">
            <i class="dropdown icon"></i>
            <div class="default text">choose a store</div>
            <div class="menu"></div>
        </div>
    </div>--}}
    <input type="text" class="form-control" placeholder="enter batch no" ng-class="hasError('batch_no', $index) ? 'error' : ''" ng-model="product.batch_no" name="batch_no[]">
</td>
<td>
    <input type="text" class="form-control" ng-class="hasError('quantity', $index) ? 'error' : ''" ng-model="product.quantity" name="quantity[]" ng-change="changedValue($index)">

    <div style="margin-top: 2px;">
        <input type="text" ng-show="product.is_expirable == 'Yes'" class="form-control product-datepicker" placeholder="enter manufacture date" ng-class="hasError('manufacture_date', $index) ? 'error' : ''" ng-model="product.manufacture_date" name="manufacture_date[]">
    </div>
</td>
<td>
    <div class="input-group">
        <span class="input-group-addon tool-tip-info" data-html="true" data-toggle="tooltip" data-index="product-tool-tip-@{{ $index  }}" data-ng-show="product.show_info">
            <i class="text-green mdi mdi-book-open-page-variant"></i>
        </span>
        <input type="text" class="form-control text-right" ng-class="hasError('rate', $index) ? 'error' : ''" ng-model="product.rate" name="rate[]" ng-change="changedValue($index)">
    </div>
    <div style="margin-top: 2px;">
        <input type="hidden" value="@{{ product.is_expirable }}" name="is_expirable[]">
        <input type="text" ng-show="product.is_expirable == 'Yes'" class="form-control product-datepicker" placeholder="enter expired date" ng-class="hasError('expired_date', $index) ? 'error' : ''" ng-model="product.expired_date" name="expired_date[]">
    </div>
</td>
<td>
    <div class="ui right labeled input">
        <input  step="0.01" ng-model="product.discount_rate" name="item_discount_rate[]" ng-class="hasError('item_discount_rate', $index) ? 'error' : ''" class="item_discount_rate_input text-right form-control" placeholder="discount" style="width: 10%;" ng-change="changedValue($index)">
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