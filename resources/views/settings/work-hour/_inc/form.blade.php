<div class="form-body">
    <div class="row">
        {{--<div class="col-md-3">
            <div class="form-group required {{ $errors->has('company_id') ? 'has-danger' : '' }}">
                <label class="control-label">Company</label>
                <div class="ui fluid search normal selection dropdown drop-down {{ $errors->has('company_id') ? 'error' : '' }}">
                    <input name="company_id" type="hidden" value="{{ old('_token') ? old('company_id'): '' }}">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a company</div>
                    <div class="menu">
                        @foreach(companyDropDown() as $key => $company)
                            <div class="item" data-value="{{ $key }}">{{ $company }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('company_id') }}</p>
            </div>
        </div>--}}
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('user_id') ? 'has-danger' : '' }}">
                <label class="control-label">User</label>
                <div class="ui fluid search normal selection dropdown drop-down {{ $errors->has('user_id') ? 'error' : '' }}">
                    <input name="user_id" type="hidden" value="{{ old('_token') ? old('user_id'): '' }}">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a user</div>
                    <div class="menu">
                        @foreach(userDropDown() as $key => $user)
                            <div class="item" data-value="{{ $key }}">{{ $user }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('user_id') }}</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('date', 'Current date', null, ['placeholder' => 'current date', 'ng-model' => 'today', 'readonly']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('start', 'Start time', null, ['placeholder' => 'start time', 'class' => 'form-control clockpicker', 'autocomplete' => 'off']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('end', 'End time', null, ['placeholder' => 'end time', 'class' => 'form-control clockpicker', 'autocomplete' => 'off']) !!}
        </div>
    </div>
</div>

@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection