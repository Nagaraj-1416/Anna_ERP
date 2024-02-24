<div class="card">
    <div class="card-body">
        <h3><b>ALLOWANCE</b></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Assigned date</th>
                    <th>Company</th>
                    <th>Assigned by</th>
                    <th style="text-align: right;">Amount</th>
                    <th style="width: 10%;">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    @php($allowance = $modal->allowance()->first())
                    @if($allowance)
                        <td>{{ $allowance->assigned_date ?? ''}}</td>
                        <td>{{ $allowance->company->name ?? 'None' }}</td>
                        <td>{{ $allowance->assignedBy->name ?? 'None' }}</td>
                        <td style="text-align: right;">{{ number_format($allowance->amount, 2) }}</td>
                        <td>
                            <button onclick="editAllowance({{ $allowance }})" class="btn waves-effect waves-light btn-primary btn-sm">
                                <i class="fa fa-pencil"></i> Edit
                            </button>
                        </td>
                    @else
                        <td colspan="6">
                            No Allowances Found...
                        </td>
                    @endif
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="ui modal modal-fixed" id="allowanceModal">
    <div class="header">Edit Allowance</div>
    <div class="content">
        {!! form()->model($modal, ['url' => route('allowance.edit', [class_basename($modal), $modal]), 'method' => 'POST']) !!}
        <div class="row " id="allowanceForm">
            <div class="col-md-12">
                <input type='hidden' value="edit" name="edit">
                <input type='hidden' name="id" id="allowance_id">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('is_active') ? 'has-danger' : '' }}">
                                    <label for="is_active" class="control-label form-control-label">Is Active?</label>
                                    <select class="form-control" id="is_active" name="is_active">
                                        @foreach(isActiveDropDown() as $index => $item)
                                            <option value="{{ $index }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('assigned_date') ? 'has-danger' : '' }}">
                                    <label for="assigned_date" class="control-label form-control-label">Assigned date</label>
                                    <input class="form-control datepicker" placeholder="choose assigned date"
                                           name="assigned_date" type="text" id="assigned_date" autocomplete="off">
                                    <p class="form-control-feedback"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
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
                <hr class="hrSector">
                <div class="clearfix">
                    <div class="pull-left">
                    </div>
                    <div class="pull-right">
                        <button type="Submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
                        <button type="Button" class="btn btn-inverse" onclick="hideModal()" id="cancel-btn"><i class="fa fa-remove"></i> Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{ form()->close() }}
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
