<div class="card">
    <div class="card-body">
        <h3><b>EXPENSES</b> <span class="pull-right">Total Expenses: {{ count($expenses) }}</span></h3>
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
                    @if(count($expenses))
                        @foreach($expenses as $expenseKey => $expense)
                        <tr>
                            <td>
                                <a target="_blank" href="{{ route('expense.receipt.show', [$expense]) }}">{{ $expense->expense_no }}</a>
                            </td>
                            <td>{{ $expense->expense_date }}</td>
                            <td>{{ $expense->category->name or 'None' }}</td>
                            <td>
                                <span class="{{ statusLabelColor($expense->status) }}">{{ $expense->status }}</span>
                            </td>
                            <td class="text-right text-success">{{ number_format($expense->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>No Expenses Found...</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>