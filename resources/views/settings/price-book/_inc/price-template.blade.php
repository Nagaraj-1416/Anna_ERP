<td>
    <div class="form-group required">
        <div class="ui fluid  search selection dropdown product-drop-down form-control" data-index="@{{ index }}">
            <input type="hidden" name="products[]">
            <i class="dropdown icon"></i>
            <div class="default text">choose an item</div>
            <div class="menu"></div>
        </div>
    </div>
</td>
<td>
    <div class="form-group required">
        <input type="text" class="form-control start-range" placeholder="Range starts from"
               ng-model="price.rangeStartFrom"
               name="range_start_from[]" data-index="@{{ index }}">
    </div>
</td>
<td>
    <div class="form-group required">
        <input type="text" class="form-control end-range" placeholder="Range ends to"
               ng-model="price.rangeEndTo"
               name="range_end_to[]" data-index="@{{ index }}">
    </div>
</td>
<td>
    <div class="form-group required">
        <input type="text" class="form-control amount" placeholder="enter amount" ng-model="price.amount"
               name="amount[]" data-index="@{{ index }}">
    </div>

</td>
<td>
    <button type="Button" class="btn btn-danger remove_row_btn" ng-show="prices.length > 1"
            ng-click="removePrice(index)">
        <i class="fa fa-remove"></i>
    </button>
</td>
<input type="hidden" value="@{{ price.id }}" name="ids[]">