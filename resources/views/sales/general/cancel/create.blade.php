<div id="{{ isset($formName) ? $formName : 'cancelForm' }}" class="hidden custom-form-body cancel-form">
    {!! form()->model($model, ['url' => $route, 'method' => 'POST']) !!}
    <div class="form-body">
        <h3 class="box-title box-title-with-margin">Cancel {{ isset($header) ? $header: 'Order' }}</h3>
        <hr>
        @include('purchases.general.cancel.form')
        <div class="row">
            <div class="col-md-12">
                <div class="pull-left"></div>
                <div class="pull-right">
                    <button type="Submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
                    <button type="Button" class="btn btn-inverse cancelBtn" id="cancelBtn"><i class="fa fa-remove"></i>
                        Cancel
                    </button>
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
