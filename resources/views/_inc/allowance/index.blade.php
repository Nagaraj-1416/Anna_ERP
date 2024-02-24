{!! form()->model($modal, ['url' => route('allowance.create', [class_basename($modal), $modal]), 'method' => 'POST']) !!}
<div class="row hidden" id="allowanceForm">
    <div class="col-md-12">
        <hr class="hrSector">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('assigned_date') ? 'has-danger' : '' }}">
                            <label for="assigned_date" class="control-label form-control-label">Assigned date</label>
                            <input class="form-control datepicker" placeholder="choose assigned date"
                                   name="date" type="text" id="assigned_date" autocomplete="off">
                            <p class="form-control-feedback"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group  required {{ $errors->has('amount') ? 'has-danger' : '' }}">
                            <label for="amount" class="control-label form-control-label">Amount</label>
                            <input class="form-control" placeholder="enter allowance amount"
                                   name="amount" type="text" id="amount"
                                   autocomplete="off">
                            <p class="form-control-feedback"></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group {{ $errors->has('notes') ? 'has-danger' : '' }}">
                            <label for="notes" class="control-label form-control-label">Allowance
                                notes</label>
                            <textarea class="form-control" placeholder="enter allowance related notes here..."
                                      rows="3"
                                      name="notes" cols="50" id="notes"
                                      autocomplete="off"></textarea>
                            <p class="form-control-feedback"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="clearfix">
            <div class="pull-left">

            </div>
            <div class="pull-right">
                <button type="Submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
                <button type="Button" class="btn btn-inverse" onclick="cancelClick()" id="cancel-btn"><i class="fa fa-remove"></i> Cancel
                </button>
            </div>
        </div>
        <hr>
    </div>
</div>
{{ form()->close() }}