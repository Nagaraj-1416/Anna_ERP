<div class="form-filter">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group @{{ hasError('company') ? 'has-danger' : '' }}">
                <label class="control-label">Company</label>
                <div class="ui fluid  search selection dropdown company-drop-down @{{ hasError('company') ? 'error' : '' }}">
                    <input type="hidden" name="company">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a company</div>
                    <div class="menu">
                        @foreach(companyDropDown() as $key => $company)
                            <div class="item" data-value="{{ $key }}">{{ $company }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">@{{ hasError('company') ? hasError('company') : '' }}</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group @{{ hasError('route') ? 'has-danger' : '' }}">
                <label class="control-label">Route</label>
                <div class="ui fluid  search selection dropdown route-drop-down @{{ hasError('route') ? 'error' : '' }}">
                    <input type="hidden" name="route">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a route</div>
                    <div class="menu">
                        @foreach(routeDropDown() as $key => $route)
                            <div class="item" data-value="{{ $key }}">{{ $route }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">@{{ hasError('route') ? hasError('route') : '' }}</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group @{{ hasError('route') ? 'has-danger' : '' }}">
                <label class="control-label">Not visited reason</label>
                <div class="ui fluid  search selection dropdown reason-drop-down @{{ hasError('reason') ? 'error' : '' }}">
                    <input type="hidden" name="route">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a reason</div>
                    <div class="menu">
                        @foreach($reasons as $key => $reason)
                            <div class="item" data-value="{{ $key }}">{{ $reason }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">@{{ hasError('route') ? hasError('route') : '' }}</p>
            </div>
        </div>
    </div>
    @include('report.general.date-range')
</div>