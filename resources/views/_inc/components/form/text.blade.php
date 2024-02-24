<div class="form-group {{ ($errors->has($name)) ? 'has-danger' : '' }} {{ $required ? 'required' : '' }}">
    @if($horizontal)
        {{ form()->label($name, $label, isset($labelClass) && $labelClass ? ['class' => $labelClass.' col-sm-2 control-label form-control-label '] : ['class' => 'col-sm-2 control-label form-control-label']) }}
        <div class="col-sm-10">
            {{ form()->text($name, $value, array_merge(['class' => 'form-control'], $attributes)) }}
            <p class="form-control-feedback">{{ ($errors->has($name) ? $errors->first($name) : '') }}</p>
            @foreach($helpBlocks as $block)
                <p class="form-control-feedback">{{ $block }}</p>
            @endforeach
        </div>
    @else
        {{ form()->label($name, $label, ['class' => 'control-label form-control-label']) }}
        {{ form()->text($name, $value, array_merge(['class' => 'form-control'], $attributes)) }}
        <p class="form-control-feedback">{{ ($errors->has($name) ? $errors->first($name) : '') }}</p>
    @endif
</div>