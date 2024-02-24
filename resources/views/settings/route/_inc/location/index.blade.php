{!! form()->model($modal, ['url' => route('setting.route.location.store', [$modal]), 'method' => 'POST']) !!}
<div class="row hidden">
    <div class="col-md-12">
        <div id="location_form"></div>
    </div>
    <div class="col-md-12">
        <div class="clearfix">
            <div class="pull-left">
                <button type="Button" id="add_new"  class="btn btn-info"><i class="fa fa-plus"></i> Add a new record</button>
            </div>
            <div class="pull-right">
                <button type="Submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
                <button type="Button" class="btn btn-inverse" id="cancel-btn"><i class="fa fa-remove"></i> Cancel</button>
            </div>
        </div>
        <hr>
    </div>
</div>
{{ form()->close() }}