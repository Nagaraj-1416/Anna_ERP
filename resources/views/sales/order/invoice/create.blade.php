<div id="invoiceForm" class="hidden custom-form-body">
    {!! form()->model($model, ['url' => route('sales.invoice.store', [$model]), 'method' => 'POST']) !!}
    <div class="form-body">
        <h3 class="box-title box-title-with-margin">Create Invoice </h3>
        <hr>
        @include('sales.order.invoice.form')
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