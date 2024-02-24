<div class="card">
    <div class="card-body">
        <div class="d-flex no-block">
            <h4 class="card-title">Associated Staff</h4>
            <div class="ml-auto"></div>
        </div>
        <div class="table-responsive">
            <table id="dataTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Code</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Mobile</th>
                    <th>Is Active?</th>
                    @if (isset($removeURL) && $removeURL)
                        <th>Actions</th>
                    @endif
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@section('script')
    @parent()
    <script src="{{ asset('js/vendor/table.js') }}"></script>
    <script>
        $(document).ready(function() {
            var $table = $('#dataTable');
            var model = [{!! json_encode($model->toArray()) !!}][0];
            var route = '{{ route('setting.staff.list', [class_basename($model), $model, isset($relation) ? $relation : 'staff']) }}'
            var columns = [
                { "data": "code" },
                { "data": "full_name" },
                { "data": "email" },
                { "data": "phone" },
                { "data": "mobile" },
                { "data": "is_active" }
            ];
            @if (isset($removeURL) && $removeURL)
                columns.push({ "data": "action" });
            @endif
            var table = $table.DataTable({
                "processing": true,
                "serverSide": true,
                "orderCellsTop": true,
                "ajax":{
                    "url": route,
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
                "columns": columns
            });

            var $searchHeader = $('<tr class="form-material"></tr>');
            $table.find('thead th').each( function () {
                var title = $table.find('thead tr:eq(0) th').eq( $(this).index() ).text();
                if (title === 'Action' || title === 'Actions'){
                    $searchHeader.append('<th></th>');
                }else{
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

            @if (isset($removeURL) && $removeURL)
                $('.card-body').on('click', '.remove-staff', function() {
                    var id = $(this).data('id');
                    var deleteUrl  = '{{ $removeURL }}';
                    deleteUrl = deleteUrl.replace('ID', id);
                    swal({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this action!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DB2828',
                        confirmButtonText: 'Yes, Delete!'
                    }).then(function (isConfirm) {
                        if (isConfirm.value){
                            $.ajax({
                                url: deleteUrl,
                                type: 'DELETE',
                                data : {'_token' : '{{ csrf_token() }}'},
                                success: function(result) {
                                    var type = result.success ? 'success' : 'warning';
                                    var title = result.success ? 'Deleted!' : 'Unable to delete!';
                                    swal(title, result.message, type);
                                    if (result.success){
                                        setTimeout(function () {
                                            location.reload()
                                        }, 800);
                                    }
                                }
                            });
                        }
                    });
                });
            @endif
        });
    </script>
@endsection