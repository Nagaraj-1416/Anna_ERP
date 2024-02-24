<div class="ui modal modal-fixed" id="block_modal">
    <div class="header">Block Vehicle</div>
    <div class="content">
        {{ form()->model($rep, ['url' => route('setting.rep.vehicle.status.change', ['method' => 'Block', 'rep' => $rep]), 'method' => 'POST'])  }}
        <input type="hidden" name="block_date_id" id="dataInput">
        <div class="form-body">
            <div class="row">
                <div class="col-md-12">
                    {!! form()->bsText('block_date', 'Date', null, ['placeholder' => 'pick a vehicle blocked date', 'class' => 'form-control datepicker']) !!}
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-inverse cancelBtn" id="cancelBtn" data-dismiss="modal"><i class="fa fa-remove"></i>
            Cancel
        </button>
        <button type="Submit" class="btn btn-success pull-right"><i class="fa fa-check"></i>Submit</button>
    </div>
    {{ form()->close() }}
</div>
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection

@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
@endsection