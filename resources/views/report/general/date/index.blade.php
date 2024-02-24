<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">Date Range</label>
            <div class="ui floating fluid search selection dropdown date-range">
                <div class="default text">choose a date range</div>
                <div class="menu">
                    @foreach(dateRangeDropDown() as $key => $value)
                        <div class="header">
                            {{ $key }}
                        </div>
                        @foreach($value as $index => $data)
                            <div class="item">
                                {!! $data !!}
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>

        </div>
    </div>
    <div class="col-md-3">
        {!! form()->bsText('from_date', 'Date', null, ['placeholder' => 'pick a date', 'class' => 'form-control datepicker', 'ng-model' => 'query.date', 'ng-disabled' => 'daterangeValue !== "custom"'], false) !!}
    </div>
</div>