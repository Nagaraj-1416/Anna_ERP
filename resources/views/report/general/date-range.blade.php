{{--<div class="row mb-3">--}}
    {{--<div class="col-md-3">--}}
        {{--<div class="form-group">--}}
            {{--<label class="control-label">Company</label>--}}
            {{--<div class="ui fluid  search selection dropdown company-dropdown">--}}
                {{--<input type="hidden" name="company_id">--}}
                {{--<i class="dropdown icon"></i>--}}
                {{--<div class="default text">choose a company</div>--}}
                {{--<div class="menu"></div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}
<div class="row">
    <div class="col-md-3">
        <div class="form-group required">
            <label class="control-label">Date range</label>
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
        {!! form()->bsText('from_date', 'From date', null, ['placeholder' => 'pick a from date', 'class' => 'form-control datepicker', 'ng-model' => 'query.fromDate', 'ng-disabled' => 'daterangeValue !== "custom"']) !!}
    </div>
    <div class="col-md-3">
        {!! form()->bsText('to_date', 'To date', null, ['placeholder' => 'pick a to date', 'class' => 'form-control datepicker', 'ng-model' => 'query.toDate', 'ng-disabled' => 'daterangeValue !== "custom"']) !!}
    </div>
</div>
