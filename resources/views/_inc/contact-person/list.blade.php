<div class="card">
    <div class="card-body">
        <div class="d-flex no-block">
            <h4 class="card-title">Contact Persons</h4>
            <div class="ml-auto"></div>
        </div>
        <div class="table-responsive">
            <table id="dataTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0"
                   width="100%">
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
                    <th>Is active?</th>
                    <th>Actions</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    @include('_inc.contact-person.edit')
</div>
@section('script')
    @parent()
    <script src="{{ asset('js/vendor/table.js') }}"></script>
    <script>
        $(document).ready(function () {
            var $table = $('#dataTable');
            var route = '{{ route('contact.person.list', [class_basename($model), $model]) }}';
            var table = $table.DataTable({
                "processing": true,
                "serverSide": true,
                "orderCellsTop": true,
                "ajax": {
                    "url": route,
                    "dataType": "json",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {"data": "salutation"},
                    {"data": "first_name"},
                    {"data": "last_name"},
                    {"data": "full_name"},
                    {"data": "phone"},
                    {"data": "mobile"},
                    {"data": "email"},
                    {"data": "designation"},
                    {"data": "department"},
                    {"data": "is_active"},
                    {"data": "action"}
                ]
            });
            var $searchHeader = $('<tr class="form-material"></tr>');
            $table.find('thead th').each(function () {
                var title = $table.find('thead tr:eq(0) th').eq($(this).index()).text();
                if (title === 'Action' || title === 'Actions') {
                    $searchHeader.append('<th></th>');
                } else {
                    $searchHeader.append('<th><input type="text" placeholder="search. . ." class="form-control form-control-line form-control-sm"/></th>');
                }
            });
            $table.find('thead').append($searchHeader);
            table.columns().every(function (index) {
                $table.find('thead tr:eq(1) th:eq(' + index + ') input').on('keyup change', function () {
                    table.column($(this).parent().index() + ':visible')
                        .search(this.value)
                        .draw();
                });
            });


        });

        function deletePerson(data) {
            var deleteUrl = '{{ route('contact.person.delete', [ 'contactPerson'=>'ID', class_basename($model), $model]) }}';
            deleteUrl = deleteUrl.replace('ID', data);
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DB2828',
                confirmButtonText: 'Yes, Delete!'
            }).then(function (isConfirm) {
                if (isConfirm.value) {
                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        data: {'_token': '{{ csrf_token() }}'},
                        success: function(result) {
                            var type = result.success ? 'success' : 'warning';
                            var title = result.success ? 'Deleted!' : 'Unable to delete!';
                            swal(title, result.message, type);
                            setTimeout(function () {
                                location.reload()
                            }, 800);
                        }
                    });
                }
            });
        }

        function editData(data) {
            var route = '{{ route('contact.person.edit', ['contactPerson' => 'CP']) }}'
            $.get(route.replace('CP', data), function (value) {
                $('#first_name').attr('value', value.first_name);
                $('#last_name').attr('value', value.last_name);
                $('#full_name').attr('value', value.full_name);
                $('#phone').attr('value', value.phone);
                $('#mobile').attr('value', value.mobile);
                $('#email').attr('value', value.email);
                $('#designation').attr('value', value.designation);
                $('#department').attr('value', value.department);
                $.each($('#salutation').find('option'), function (key, val) {
                    if ($(val).val() === value.salutation) {
                        $(val).attr('selected', true);
                    }
                });
                $.each($('#is_active').find('option'), function (key, val) {
                    if ($(val).val() === value.is_active) {
                        $(val).attr('selected', true);
                    }
                })
                $('#modalTitle').text('Edit contact person -' + value.first_name)
            });
            var updateRoute = '{{ route('contact.person.update', ['contactPerson' => 'CP']) }}';
            $('#edit_modal').find('form').removeAttr('action');
            $('#edit_modal').find('form').attr('action', updateRoute.replace('CP', data));
        }
    </script>
@endsection