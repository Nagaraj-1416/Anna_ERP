<td>
    <div class="form-group required">
        <div class="ui fluid  search selection dropdown account-drop-down" data-index="@{{ $index }}" ng-class="hasError('account_id', $index) ? 'error' : ''">
            <input type="hidden" name="account_id[]">
            <i class="dropdown icon"></i>
            <div class="default text">choose an account</div>
            <div class="menu">
                @foreach(accDropDownByCompany() as $key => $type)
                    <div class="item" data-value="{{ $key }}">{{ $type }}</div>
                @endforeach
            </div>
        </div>
    </div>
</td>
<td>
    <input type="text" name="debit[]" ng-readonly="record.readonly.debit" ng-change="calculateTotal($index, 'debit')" ng-class="hasError('debit', $index) ? 'is-invalid' : ''" ng-model="record.debit" placeholder="enter debit" class="form-control text-right">
</td>
<td>
    <input type="text" name="credit[]" ng-readonly="record.readonly.credit" ng-change="calculateTotal($index, 'credit')" ng-class="hasError('debit', $index) ? 'is-invalid' : ''" ng-model="record.credit" placeholder="enter credit" class="form-control text-right">
</td>
<td>
    <button ng-show="(transaction.records.length > 2)" type="button" ng-click="removeRecord($index)" class="btn btn-danger btn-sm"><i class="fa fa-remove"></i> Remove</button>
</td>