<div class="row">
    <div class="col-md-4">
        {!! form()->bsText('reimbursed_on', 'Reimbursed date', null, ['placeholder' => 'pick reimbursed date', 'class' => 'form-control datepicker']) !!}
    </div>
    <div class="col-md-4">
        <div class="form-group required {{ $errors->has('reimbursed_paid_through') ? 'has-danger' : '' }}">
            <label class="control-label">Paid through</label>
            <div class="ui fluid  search selection dropdown paid-through-drop-down {{ $errors->has('reimbursed_paid_through') ? 'error' : '' }}">
                <input type="hidden" name="reimbursed_paid_through">
                <i class="dropdown icon"></i>
                <div class="default text">choose an account</div>
                <div class="menu"></div>
            </div>
            <p class="form-control-feedback">{{ $errors->first('reimbursed_paid_through') }}</p>
        </div>
    </div>
    <div class="col-md-4">
        {!! form()->bsText('reimbursed_amount', 'Amount', ($reimbursementAmount - $report->reimburses->sum('amount')), ['placeholder' => 'enter the amount']) !!}
    </div>
</div>

<div class="row m-t-10">
    <div class="col-md-12">
        {!! form()->bsTextarea('reimbursed_notes', 'Notes', null, ['placeholder' => 'enter reimbursement related notes here...', 'rows' => '4'], false) !!}
    </div>
</div>