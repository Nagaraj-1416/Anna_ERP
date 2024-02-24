<div id="reimbursementForm" class="hidden custom-form-body">
    {!! form()->model($report, ['url' => route('expense.reports.reimbursement.store', [$report]), 'method' => 'POST']) !!}
    <div class="form-body">
        <h3 class="box-title box-title-with-margin">Record Reimbursement</h3>
        <hr>
        @include('expense.reports.reimbursement.form')
        <input type="hidden" name="mode" value="create">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-left"></div>
                <div class="pull-right">
                    <button type="Submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
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
