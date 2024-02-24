<script>
    @if(!old('_token') && !$model);
    $('.datepicker').val('{{ carbon()->toDateString() }}');
    @endif;
</script>