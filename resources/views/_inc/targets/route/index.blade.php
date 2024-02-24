{!! form()->model($route, ['url' => route('setting.route.target.store', [$route]), 'method' => 'POST']) !!}
<div class="row hidden">
    <div class="col-md-12">
        <hr>
        <div class="list template clearfix" id="targetForm">
            <table id="personTable" class="display nowrap table " cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th width="25%">Type</th>
                        <th width="25%">Start date</th>
                        <th width="25%">End date</th>
                        <th width="25%">Target</th>
                        <th width="25%"></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div class="col-md-12">
        <div class="clearfix">
            <div class="pull-left">
                <button type="Button" onclick="addNewRecord()" id="add_more_data_row" class="btn btn-info"><i class="fa fa-plus"></i>
                    Add a new record
                </button>
            </div>
            <div class="pull-right">
                <button type="Submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
                <button type="Button" class="btn btn-inverse" id="cancel-btn" onclick="hiddenForm()"><i class="fa fa-remove"></i> Cancel</button>
            </div>
        </div>
        <hr>
    </div>
</div>
{{ form()->close() }}

<div class="table-data-temp hidden">
    <table>
        <tr id="trRT">
            <td>
                <div class="form-group">
                    <select class="form-control" id="typeRT" name="type[RT]">
                        @foreach(typeDD() as $index => $item)
                            <option value="{{ $index }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
            </td>
            <td>@include('_inc.contact-person.input', ['placeHolder' => 'select start date', 'name' => 'start_date[RT]', 'id' => 'start_dateRT', 'class' => 'target-start-datepicker'])</td>
            <td>@include('_inc.contact-person.input', ['placeHolder' => 'select end date', 'name' => 'end_date[RT]', 'id' => 'end_dateRT', 'class' => 'target-end-datepicker'])</td>
            <td>@include('_inc.contact-person.input', ['placeHolder' => 'enter target value', 'name' => 'target[RT]', 'id' => 'targetRT'])</td>
            <td>
                <button type="Button" class="btn btn-danger remove_row_btn hidden" onclick="removeRow(this)">
                    <i class="fa fa-remove"></i>
                </button>
            </td>
        </tr>
    </table>
</div>
