<div class="card">
    <div class="card-body" style="background-color: #EFEFEF;">
        <h4 class="box-title"><b>Expenses</b></h4>
        <h6 style="padding-top: 2px;">
            <span>Pick expenses to confirm and generate receipts</span>
        </h6>
        <table class="ui table bordered celled striped">
            <thead>
            <tr>
                <th style="width: 3%;">#</th>
                <th style="width: 10%;">EXPENSE NO</th>
                <th style="width: 20%;">EXPENSE DATE</th>
                <th style="width: 20%;">EXPENSE TYPE</th>
                <th style="width: 20%;" class="text-right">AMOUNT</th>
            </tr>
            </thead>
            <tbody>
            @if($expenses->count())
                @foreach($expenses as $expense)
                    <tr>
                        <td style="width: 3%;">
                            <div class="demo-checkbox">
                                <input type="checkbox" id="{{ 'md_expenses_checkbox_28_' . $expense->id }}"
                                       data-id="{{ $expense->id }}"
                                       name="expenses[id][{{ $expense->id }}]"
                                       class="chk-col-cyan cheque-check"
                                       {{ old('_token') && old('expenses') && array_get(old('expenses'), 'id') && old('expenses')['id'][$expense->id] ? 'checked' : ''}}
                                       ng-click="expenseCheck($event)">
                                <label for="{{ 'md_expenses_checkbox_28_' . $expense->id }}"></label>
                            </div>
                        </td>
                        <td style="width: 10%;">
                            {{ $expense->code }}
                        </td>
                        <td style="width: 20%;">
                            {{ $expense->expense_date }}
                        </td>
                        <td style="width: 20%;">
                            {{ $expense->type->name ?? 'None'}}
                        </td>
                        <td style="width: 20%;" class="text-right">
                            <p class="text-right amount-p" data-amount="{{ $expense->amount }}"
                               ng-show="!getShow('{{ $expense->id }}', true)">{{ number_format($expense->amount, 2) }}</p>
                            <input ng-show="getShow('{{ $expense->id }}', true)"
                                   ng-init="addAmount('{{ $expense->id}}', '{{ $expense->amount}}')"
                                   type="text"
                                   class="form-control text-right"
                                   name="expenses[amount][{{ $expense->id }}]"
                                   ng-change="totalExpense()"
                                   ng-model="expenseAmounts['{{ $expense->id }}']"
                                   ng-class="hasErrorForCheque('expenses', 'amount', '{{ $expense->id }}', true)"
                                   value="{{ old('_token') && old('expenses') && array_get(old('expenses'), 'amount') && old('expenses')['amount'][$expense->id] ? old('expenses')['amount'][$expense->id] :  $expense->amount }}">
                            <p class="form-control-feedback"></p>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="2">No Expense Found...</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>