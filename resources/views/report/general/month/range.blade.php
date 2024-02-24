<div class="row">
    <div class="col-md-3">
        {!! form()->bsText('year', 'Year', null, ['placeholder' => 'pick a from year', 'class' => 'form-control from-year-datepicker', 'ng-model' => 'query.year', 'ng-change' => 'handleChange()'], false) !!}
    </div>
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-6">
                {!! form()->bsText('from_month', 'From Month', null, ['placeholder' => 'pick a from month', 'class' => 'form-control from-month-datepicker', 'ng-model' => 'query.fromMonth', 'ng-change' => 'handleFromDateChange()'], false) !!}
            </div>
            <div class="col-md-6">
                {!! form()->bsText('to_month', 'To Month', null, ['placeholder' => 'pick a to month', 'class' => 'form-control to-month-datepicker', 'ng-model' => 'query.toMonth'], false) !!}
            </div>
        </div>
    </div>
</div>