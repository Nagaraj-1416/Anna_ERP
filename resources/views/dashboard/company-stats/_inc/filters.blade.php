<div class="form-filter">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group required @{{ hasError('company') ? 'has-danger' : '' }}">
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
    </div>
    @include('report.general.date-range')
</div>