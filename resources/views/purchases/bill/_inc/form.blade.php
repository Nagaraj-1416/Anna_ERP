<div class="form-body">
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('amount', 'Bill amount', null, ['placeholder' => 'enter bill amount', 'class' => 'form-control']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('bill_date', 'Bill date', null, ['placeholder' => 'pick a bill date', 'class' => 'form-control datepicker']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('due_date', 'Due date', null, ['placeholder' => 'pick bill due date', 'class' => 'form-control datepicker']) !!}
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter bill related notes here...', 'rows' => '4'], false) !!}
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