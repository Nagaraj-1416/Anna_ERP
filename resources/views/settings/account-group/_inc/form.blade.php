<div class="form-body">
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('name', 'Name', null, ['placeholder' => 'enter name', 'class' => 'form-control']) !!}
        </div>
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('category_id') ? 'has-danger' : '' }}">
                <label class="control-label">Account category</label>
                <div class="ui fluid search normal selection dropdown category-drop-down {{ $errors->has('category_id') ? 'error' : '' }}">
                    @if(isset($accountGroup))
                        <input name="category_id" type="hidden" value="{{ old('_token') ? old('category_id'): $accountGroup->category_id }}">
                    @else
                        <input name="category_id" type="hidden" value="{{ old('_token') ? old('category_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a parent</div>
                    <div class="menu">
                        @foreach(accountCategoryDropDown() as $key => $name)
                            <div class="item" data-value="{{ $key }}">{{ $name }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('parent_id') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group {{ $errors->has('parent_id') ? 'has-danger' : '' }}">
                <label class="control-label">Parent group</label>
                <div class="ui fluid search normal selection dropdown group-drop-down {{ $errors->has('parent_id') ? 'error' : '' }}">
                    @if(isset($accountGroup))
                        <input name="parent_id" type="hidden" value="{{ old('_token') ? old('parent_id'): $accountGroup->parent_id }}">
                    @else
                        <input name="parent_id" type="hidden" value="{{ old('_token') ? old('parent_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a parent</div>
                    <div class="menu">
                        @foreach(accountGroupDropDown(isset($accountGroup) ? $accountGroup->id :null) as $key => $name)
                            <div class="item" data-value="{{ $key }}">{{ $name }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('parent_id') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group required {{ ($errors->has('is_active')) ? 'has-danger' : '' }}">
                <label class="control-label">Is active?</label>
                <div class="demo-radio-button">
                    <input name="is_active" value="Yes" type="radio" class="with-gap" id="Yes"
                           checked="" {{ (old('is_active') == 'Yes' || (isset($accountGroup) && $accountGroup->is_active  == 'Yes')) ? 'checked' : ''}}>
                    <label for="Yes">Yes</label>
                    <input name="is_active" value="No" type="radio" class="with-gap"
                           id="No" {{ (old('is_active') == 'No' || (isset($accountGroup) && $accountGroup->is_active == 'No'))  ? 'checked' : ''}}>
                    <label for="No">No</label>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {!! form()->bsTextarea('description', 'Description', null, ['placeholder' => 'enter description', 'class' => 'form-control'], false) !!}
        </div>
    </div>
</div>
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection

@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script>

        var groupDropDown = $('.group-drop-down');
        var groupSearchRoute = '{{ route('setting.account.group.search.by.category', ['category' => 'CATEGORY']) }}';
        var categoryDropdown = $('.category-drop-down');

        categoryDropdown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            onChange: function (value) {
                var url = groupSearchRoute.replace('CATEGORY', value);
                groupDropDown.dropdown('clear');
                groupDropDown.dropdown('setting', {
                    forceSelection: false,
                    saveRemoteData: false,
                    apiSettings: {
                        url: url + '/{query}',
                        cash: false,
                    },
                });
            }
        });

        @if(isset($accountGroup))
            groupDropDown.dropdown('set text', '{{ $accountGroup->parent->name ?? '' }}');
            groupDropDown.dropdown('set value', '{{ $accountGroup->parent_id  }}');
        @endif

        var old = @json(old());
        if (old.hasOwnProperty('_token')){
            groupDropDown.dropdown('set text', old.parent_name);
            groupDropDown.dropdown('set value', old.parent_id);
        }
    </script>
@endsection