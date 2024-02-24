<!-- net income -->
<div class="card bg-light-info border-info">
    <div class="card-body">
        <h3 class="card-title text-info">Net Income</h3>
        <hr>
        <div>
            <table class="table">
                <tbody>
                <tr>
                    <td><b>Fiscal Years</b></td>
                    <td class="text-right"><b>{{ carbon()->now()->subYear(1)->format('Y') }}</b></td>
                    <td class="text-right"><b>{{ carbon()->now()->year }}</b></td>
                </tr>
                <tr>
                    <td>Income</td>
                    <td class="text-right">
                        @if(yearlyIncome()['preYearIncome'])
                            <a href="" data-route="{{ route('dashboard.income.data', ['preYear' => true]) }}"
                               class="sidebar-btn">{{ number_format(yearlyIncome()['preYearIncome'], 2) }}</a>
                        @else
                            {{ number_format(yearlyIncome()['preYearIncome'], 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if(yearlyIncome()['thisYearIncome'])
                            <a href="" data-route="{{ route('dashboard.income.data') }}"
                               class="sidebar-btn">{{ number_format(yearlyIncome()['thisYearIncome'], 2) }}</a>
                        @else
                            {{ number_format(yearlyIncome()['thisYearIncome'], 2) }}
                        @endif

                    </td>
                </tr>
                <tr>
                    <td>Expense</td>
                    <td class="text-right">
                        @if(yearlyExpenses()['preYearExpense'])
                            <a href="" data-route="{{ route('dashboard.expense.data', ['preYear' => true]) }}"
                               class="sidebar-btn">{{ number_format(yearlyExpenses()['preYearExpense'], 2) }}</a>
                        @else
                            {{ number_format(yearlyExpenses()['preYearExpense'], 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if(yearlyExpenses()['thisYearExpense'])
                            <a href="" data-route="{{ route('dashboard.expense.data') }}"
                               class="sidebar-btn">{{ number_format(yearlyExpenses()['thisYearExpense'], 2) }}</a>
                        @else
                            {{ number_format(yearlyExpenses()['thisYearExpense'], 2) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-bg-success"><b>Net Income</b></td>
                    <td class="td-bg-success text-right">
                        <b>{{ number_format((yearlyIncome()['preYearIncome'] - yearlyExpenses()['preYearExpense']), 2) }}</b>
                    </td>
                    <td class="td-bg-success text-right">
                        <b>{{ number_format((yearlyIncome()['thisYearIncome'] - yearlyExpenses()['thisYearExpense']), 2) }}</b>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>