<div class="form-group">
    <input class="form-control {{ isset($class) ? $class : '' }}" placeholder="{{isset($placeHolder) ? $placeHolder : ''}}" name="{{$name}}" type="text"
           {{ isset($id) ? 'id='. $id .'' : '' }}  autocomplete="off" value="{{isset($value) ? $value : ''}}">
    <span class="help-block" style="width: 100%; color: red" {{ isset($id) ? 'id='. $id .'_help' : '' }}></span>
</div>