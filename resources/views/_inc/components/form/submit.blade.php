<button type="submit" class="btn btn-{{ $class }} submission-disable" {{ $value ? 'value=' . $value .'' : '' }} {{ $name ? 'name=' . $name .'' : '' }}>
    @if($text == 'Update')
        <i class="ti-pencil"></i>
    @else
        <i class="ti-check"></i>
    @endif
    {{ $text }}
</button>
