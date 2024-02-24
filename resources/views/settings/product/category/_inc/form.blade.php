<div class="form-body">
    <div class="row">
        <div class="col-md-7">
            {!! form()->bsText('name', 'Name', null, ['placeholder' => 'enter product category name']) !!}
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