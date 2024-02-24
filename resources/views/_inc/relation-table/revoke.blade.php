<div class="ui modal modal-fixed" id="rb_modal">
    <div class="header">Revoke Vehicle</div>
    <div class="content">
        {{ form()->model($rep, ['url' => route('setting.rep.vehicle.status.change', ['method' => 'Revoke', 'rep' => $rep]), 'method' => 'POST']) }}
        <input type="hidden" name="data" id="revokeData">
        <div class="form-body">
            <div class="row">
                <div class="col-md-12">
                    {!! form()->bsText('date', 'Date', null, ['placeholder' => 'pick a vehicle revoked date', 'class' => 'form-control datepicker']) !!}
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