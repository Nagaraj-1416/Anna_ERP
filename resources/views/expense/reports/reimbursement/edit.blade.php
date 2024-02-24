<div id="reimbursementEditForm" class="hidden custom-form-body">
    {!! form()->model($report, ['url' => '', 'method' => 'PATCH']) !!}
    <div class="form-body">
        <h3 class="box-title box-title-with-margin">Edit Reimbursement</h3>
        <hr>
        @include('expense.reports.reimbursement.form')
        <input type="hidden" name="mode" value="edit">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-left"></div>
                <div class="pull-right">
                    <button type="Submit" class="btn btn-success"><i class="fa fa-check"></i> Update</button>
                    <button type="Button" class="btn btn-inverse" id="cancelBtn"><i class="fa fa-remove"></i> Cancel</button>
                </div>
            </div>
        </div>
    </div>
    {{ form()->close() }}
</div>

@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection
