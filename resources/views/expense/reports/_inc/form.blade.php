<div class="form-body" ng-controller="ExpenseReportFormController">
    {{--<div class="row">--}}
        {{--<div class="col-md-3">
            <div class="form-group required {{ $errors->has('business_type_id') ? 'has-danger' : '' }}">
                <label class="control-label">Business type</label>
                <div class="ui fluid  search selection dropdown bt-drop-down {{ $errors->has('business_type_id') ? 'error' : '' }}">
                    <input type="hidden" name="business_type_id">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a business type</div>
                    <div class="menu"></div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('business_type_id') }}</p>
            </div>
        </div>--}}
        {{--<div class="col-md-3">

        </div>--}}
    {{--</div>--}}
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('title', 'Report title', null, ['placeholder' => 'enter report title']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('report_from', 'Report from', null, ['placeholder' => 'pick report from date', 'class' => 'form-control', 'id' => 'from-date']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('report_to', 'Report to', null, ['placeholder' => 'pick report to date', 'class' => 'form-control', 'id' => 'to-date']) !!}
        </div>
        <div class="col-md-3">
            <input type="hidden" name="business_type_id" value="1">
            <div class="form-group {{ $errors->has('approved_by') ? 'has-danger' : '' }}">
                <label class="control-label">An approver for the report</label>
                <div class="ui fluid  search selection dropdown approved-by-drop-down {{ $errors->has('approved_by') ? 'error' : '' }}">
                    <input type="hidden" name="approved_by">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose an approver</div>
                    <div class="menu"></div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('approved_by') }}</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group required {{ $errors->has('expenses_id') ? 'has-danger' : '' }}" >
                <label class="control-label">Unreported expenses</label>
                <div class="ui fluid search multiple selection dropdown expense-drop-down {{ $errors->has('expenses_id') ? 'error' : '' }}">
                    <input type="hidden" name="expenses_id" multiple>
                    <i class="dropdown icon"></i>
                    <div class="default text">choose unreported expenses</div>
                    <div class="menu"></div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('expenses_id') }}</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {{--<h4>Added Expenses</h4>--}}
            <div class="table-responsive po-line-items">
                <table class="table color-table inverse-table so-table">
                    <thead>
                    <tr>
                        <th>Expense no</th>
                        <th style="width: 20%;">Expense date</th>
                        <th style="width: 20%;">Expense category</th>
                        <th style="width: 10%;" class="text-right">Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-if="expenses.length == 0">
                        <td colspan="4" class="text-center">No expenses added, choose existing ones to this report.</td>
                    </tr>
                    <tr ng-repeat="expense  in expenses">
                        <td>@{{ expense.expense_no }}</td>
                        <td>@{{ expense.expense_date }}</td>
                        <td>@{{ expense.category.name }}</td>
                        <td class="text-right">@{{ expense.amount | number}}</td>
                    </tr>
                    <tr ng-if="expenses.length != 0">
                        <td colspan="3" class="text-right"><b>Total Expense:</b></td>
                        <td class="text-right"><b>@{{ totalAmount() | number }}</b></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter report related notes here...', 'rows' => 4], false) !!}
        </div>
    </div>
</div>
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection
@section('script')
    @parent
    @include('expense.reports._inc.script')
@endsection