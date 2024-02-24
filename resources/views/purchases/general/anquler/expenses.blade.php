<div class="card">
    <div class="card-body">
        <h3><b>EXPENSES</b> <span class="pull-right">Total Expenses: @{{ expenses.length }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Expense no</th>
                    <th>Expense date</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th class="text-right">Amount</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-show="expenses.length" dir-paginate="expense in expenses | itemsPerPage:5"
                    pagination-id="expenses_pagination">
                    <td>
                        <a target="_blank" href="/expense/receipts/@{{ expense.id }}">@{{ expense.expense_no }}</a>
                    </td>
                    <td>@{{ expense.expense_date }}</td>
                    <td>@{{ expense.category.name }}</td>
                    <td>
                        <span ng-class="statusLabelColor(expense.status)">@{{ expense.status }}</span>
                    </td>
                    <td class="text-right text-success">@{{ expense.amount | number:2}}</td>
                </tr>
                <tr ng-show="!expenses.length">
                    <td>No Expenses Found...</td>
                </tr>
                </tbody>
            </table>

            <hr ng-if="expenses.length > 5">
            <div class="pull-right">
                <dir-pagination-controls pagination-id="expenses_pagination"></dir-pagination-controls>
            </div>
        </div>
    </div>
</div>