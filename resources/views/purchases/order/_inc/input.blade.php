<div class="form-group {{ $errors->has($name) ? 'has-danger' : '' }}">
    <input
            class="form-control {{ isset($class) ? $class : '' }}"
            placeholder="{{ isset($placeHolder) ? $placeHolder : ''}}"
            name="{{$name}}"
            type="{{ isset($type) ? $type : 'text' }}"
            {{ isset($id) ? 'id="'. $id .'"' : '' }}
            autocomplete="off"
            value="{{ old($name) ? old($name) : (isset($value) ? $value : '')}}"
            {{ isset($readonly) ? 'readonly' : ''}}
            {{ isset($type) && $type == 'number' ? 'step=0.01' : 'text' }}
            {{ isset($ngModel) ? 'ng-model=' . $ngModel : '' }}
            {{ isset($ngClick) ? 'ng-click=' . $ngClick  : '' }}
            {{ isset($ngChange) ? 'ng-change=' . $ngChange  : '' }}
            @php
                if (isset($attr) && is_array($attr)){
                foreach ($attr as $key => $value)
                    echo $key . '=' . $value;
                }
            @endphp
    >
    {{--<p class="form-control-feedback">{{ $errors->first($name) }}</p>--}}
</div>