<li class="m-t-10">Date Range</li>
<li>
    <div class="ui fluid  search selection dropdown date-range">
        <i class="dropdown icon"></i>
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
</li>