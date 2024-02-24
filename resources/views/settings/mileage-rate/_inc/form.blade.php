<div class="form-body">
    <div class="row">
        <div class="col-md-6">
            {!! form()->bsText('date', 'Date', null, ['placeholder' => 'Pick the date', 'class' => 'datepicker form-control']) !!}
        </div>
        <div class="col-md-6">
            {!! form()->bsText('rate', 'Rate', null, ['placeholder' => 'enter rate']) !!}
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
@endsection