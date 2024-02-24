<div class="form-group {{ ($errors->has($name)) ? 'has-danger' : '' }} {{ $required ? 'required' : '' }}">
    @if($horizontal)
        {{ form()->label($name, $label, ['class' => 'col-sm-2 control-label form-control-label']) }}
        <div class="col-sm-10">
            {{--{{ form()->checkbox($name, $value, $checked, array_merge([], $attributes)) }}--}}
            <div class="switch">
                <label>
                    <input type="checkbox" class="{{ implode(" ", array_merge([], $attributes)) }}" name="{{ $name }}" value="{{$value}}"  {{ $checked ? 'checked' : '' }}>
                    <span class="lever switch-col-grey"></span>
                </label>
            </div>
            <p class="form-control-feedback">{{ ($errors->has($name) ? $errors->first($name) : '') }}</p>
        </div>
    @else
        {{ form()->label($name, $label, ['class' => 'control-label form-control-label']) }}
        <div class="switch">
            <label>
                <input type="checkbox" class="{{ implode(" ", array_merge([], $attributes)) }}" name="{{ $name }}" value="{{$value}}"  {{ $checked ? 'checked' : '' }}>
                <span class="lever switch-col-grey"></span>
            </label>
        </div>
        <p class="form-control-feedback">{{ ($errors->has($name) ? $errors->first($name) : '') }}</p>
    @endif
</div>