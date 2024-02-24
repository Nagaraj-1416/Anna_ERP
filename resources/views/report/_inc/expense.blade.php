<div class="p-20">
    <div class="row">
        <div class="col-lg-4">
            <h4 class="card-title">Receipts</h4>
            <h6 class="card-subtitle">Get more insight into your spending by analyzing your company's expenses</h6>
            <ul class="list-icons">
                <li><a href="{{ route('report.expense.details') }}"><i class="fa fa-check text-info"></i> Expense Details</a></li>
                <li><a href="{{ route('report.expense.un.submitted') }}"><i class="fa fa-check text-info"></i> Unsubmitted Expenses</a></li>
                <li><a href="{{ route('report.expense.by.category') }}"><i class="fa fa-check text-info"></i> Expenses by Category</a></li>
                <li><a href="{{ route('report.expense.by.customer') }}"><i class="fa fa-check text-info"></i> Expenses by Customer</a></li>
                <li><a href="{{ route('report.expense.by.supplier') }}"><i class="fa fa-check text-info"></i> Expenses by Supplier</a></li>
{{--                <li><a href="{{ route('report.expense.by.emp') }}"><i class="fa fa-check text-info"></i> Expenses by Employee</a></li>--}}
                <li><a href="{{ route('report.mileage.expense.by.emp') }}"><i class="fa fa-check text-info"></i> Mileage Expenses by Employee</a></li>
            </ul>
        </div>
        <div class="col-lg-4">
            <h4 class="card-title">Reports</h4>
            <h6 class="card-subtitle">View statistics on expense reports and review.</h6>
            <ul class="list-icons">
                <li><a href="{{ route('report.expense.report.details') }}"><i class="fa fa-check text-info"></i> Expense Report Details</a></li>
            </ul>
        </div>
        <div class="col-lg-4">
            <h4 class="card-title">Reimbursements</h4>
            <h6 class="card-subtitle">View information on reimbursements made to employees.</h6>
            <ul class="list-icons">
                <li><a href="{{ route('report.expense.reimbursements') }}"><i class="fa fa-check text-info"></i> Reimbursements by Employee</a></li>
            </ul>
        </div>
    </div>
</div>