{{ form()->model($staff, [ 'route' => ['profile.update', $staff->id], 'method' => 'PATCH', 'enctype' => 'multipart/form-data']) }}
<div class="form-body m-t-15">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">Staff profile image</label>
                <input type="file" class="form-control" name="staff_image">
            </div>
        </div>
        <div class="col-md-9">
            <div class="form-group required {{ ($errors->has('is_active')) ? 'has-danger' : '' }}">
                <label class="control-label">Is staff active?</label>
                <div class="demo-radio-button">
                    <input name="is_active" value="Yes" type="radio" class="with-gap" id="Yes"
                           checked="" {{ (old('is_active') == 'Yes' || (isset($staff) && $staff->is_active  == 'Yes')) ? 'checked' : ''}}>
                    <label for="Yes">Yes</label>
                    <input name="is_active" value="No" type="radio" class="with-gap"
                           id="No" {{ (old('is_active') == 'No' || (isset($staff) && $staff->is_active == 'No'))  ? 'checked' : ''}}>
                    <label for="No">No</label>
                </div>
            </div>
        </div>
    </div>

    <hr>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group required">
                <label class="control-label">Salutation</label>
                <div class="ui fluid normal selection dropdown drop-down">
                    @if(isset($staff))
                        <input name="salutation" type="hidden"
                               value="{{ old('_token') ? old('salutation'): $staff->salutation }}">
                    @else
                        <input name="salutation" type="hidden" value="{{ old('_token') ? old('salutation'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a salutation</div>
                    <div class="menu">
                        @foreach(salutationDropDown() as $key => $salutation)
                            <div class="item" data-value="{{ $key }}">{{ $salutation }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            {!! form()->bsText('first_name', 'First name', null, ['placeholder' => 'enter staff first name']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('last_name', 'Last name', null, ['placeholder' => 'enter staff last name']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('short_name', 'Short name', null, ['placeholder' => 'enter staff short name']) !!}
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('phone', 'Phone no', null, ['placeholder' => 'eg: 0215555551'], false) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('mobile', 'Mobile no', null, ['placeholder' => 'eg: 0215555552']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('dob', 'Date of birth', null, ['placeholder' => 'eg: 1988-11-20', 'class' => 'form-control datepicker']) !!}
        </div>
        <div class="col-md-3">
            <div class="form-group required {{ ($errors->has('gender')) ? 'has-danger' : '' }}">
                <label class="control-label">Gender</label>
                <div class="demo-radio-button">
                    <input name="gender" value="Male" type="radio" class="with-gap"
                           id="gender_male" {{ (old('gender') == 'Male' || (isset($staff) && $staff->gender == 'Male')) ? 'checked' : ''}}>
                    <label for="gender_male">Male</label>
                    <input name="gender" value="Female" type="radio" class="with-gap"
                           id="gender_female" {{ (old('gender') == 'Female' || (isset($staff) && $staff->gender == 'Female')) ? 'checked' : ''}}>
                    <label for="gender_female">Female</label>
                </div>
                <p class="form-control-feedback">{{ ($errors->has('gender') ? $errors->first('gender') : '') }}</p>
            </div>
        </div>
    </div>
    <h4 class="box-title">Staff Address</h4>
    <hr>
    @include('_inc.address.form')


    <div class="row">
        <div class="col-md-12">
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter staff related notes here...', 'rows' => '5'], false) !!}
        </div>
    </div>
</div>
<div class="clearfix">
    <div class="pull-right">
        {!! form()->bsSubmit('Update', 'btn btn-success waves-effect waves-light m-r-10', 'Save', 'submit') !!}
    </div>
</div>
{{ form()->close() }}

@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script>
        var dropDown = $('.drop-down');
        dropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });
    </script>
@endsection