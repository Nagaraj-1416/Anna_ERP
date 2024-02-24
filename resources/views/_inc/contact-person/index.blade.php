{!! form()->model($model, ['url' => route('contact.person.store', [class_basename($model), $model]), 'method' => 'POST']) !!}
<div class="row">
    <div class="col-md-12">
        <hr>
        <div class="list template clearfix" id="ContactPersonTemplate">
            <table id="personTable" class="display nowrap table " cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Salutation</th>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Full name</th>
                        <th>Phone</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Designation</th>
                        <th>Department</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div class="col-md-12">
        <div class="pull-left">
            <button type="Button" id="add_more_data_row" class="btn btn-info"><i class="fa fa-plus"></i> Add a new record</button>
        </div>
        <div class="pull-right">
            <button type="Submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
            <button type="Button" class="btn btn-inverse" id="cancel-btn"><i class="fa fa-remove"></i> Cancel</button>
        </div>
    </div>
</div>
<hr>
{{ form()->close() }}

<div class="table-data-temp hidden">
    <table>
        <tr>
            <td>
                <div class="form-group">
                    <select class="form-control" id="salutationCPD" name="salutation[CPD]">
                        @foreach(salutationDropDown() as $index => $item)
                            <option value="{{ $index }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
            </td>
            <td>@include('_inc.contact-person.input', ['name' => 'first_name[CPD]', 'placeHolder' => 'enter first name', 'id' => 'first_nameCPD'])</td>
            <td>@include('_inc.contact-person.input', ['name' => 'last_name[CPD]', 'placeHolder' => 'enter last name', 'id' => 'last_nameCPD'])</td>
            <td>@include('_inc.contact-person.input', ['name' => 'full_name[CPD]', 'placeHolder' => 'enter full name', 'id' => 'full_nameCPD'])</td>
            <td>@include('_inc.contact-person.input', ['name' => 'phone[CPD]', 'placeHolder' => 'enter phone', 'id' => 'phoneCPD'])</td>
            <td>@include('_inc.contact-person.input', ['name' => 'mobile[CPD]', 'placeHolder' => 'enter mobile', 'id' => 'mobileCPD'])</td>
            <td>@include('_inc.contact-person.input', ['name' => 'email[CPD]', 'placeHolder' => 'enter email', 'id' => 'emailCPD'])</td>
            <td>@include('_inc.contact-person.input', ['name' => 'designation[CPD]', 'placeHolder' => 'enter designation', 'id' => 'designationCPD'])</td>
            <td>@include('_inc.contact-person.input', ['name' => 'department[CPD]', 'placeHolder' => 'enter department', 'id' => 'departmentCPD'])</td>
            {{--            <td>@include('_inc.contact-person.input', ['name' => 'is_active[CPD]', 'placeHolder' => 'enter department', 'id' => 'is_activeCPD'])</td>--}}
            <td>
                <button type="Button" class="btn btn-danger remove_row_btn hidden" onclick="removeRow(this)">
                    <i class="fa fa-remove"></i>
                </button>
            </td>
        </tr>
    </table>
</div>

@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection

@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
@endsection