<div id="renewalForm" class="custom-form-body">
    {!! form()->model($model, ['url' => route('setting.vehicle.add.renewal', [$model]), 'method' => 'POST']) !!}
    <div class="form-body">
        <h3 class="box-title box-title-with-margin">Renewal</h3>
        <hr>
        @include('settings.vehicle._inc.renewal._inc.form')
        <div class="row">
            <div class="col-md-12">
                <div class="pull-left"></div>
                <div class="pull-right">
                    <button id="paymentSubmitBtn" type="Submit" class="btn btn-success"><i class="fa fa-check"></i>
                        Submit
                    </button>
                    <button type="Button" class="btn btn-inverse" id="cancelBtn" onclick="hideForm()"><i
                                class="fa fa-remove"></i> Cancel
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

@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script>
        var renewalBtn = $('#renewalBtn');
        var renewalForm = $('#renewalForm');
        
        renewalBtn.click(function () {
            showForm();
        });

        function showForm() {
            renewalForm.fadeIn();
        }

        hideForm();

        function hideForm() {
            renewalForm.fadeOut();
        }

        @if(old('_token') && ($errors->has('type') || $errors->has('date')))
        showForm();
        @endif
    </script>
@endsection
