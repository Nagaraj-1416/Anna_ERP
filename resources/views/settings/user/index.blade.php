@extends('layouts.master')
@section('title', 'User')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Is Active</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/vendor/table.js') }}"></script>
    <script>
        $(document).ready(function() {
            var $table = $('#dataTable');

            var table = $table.DataTable({
                "processing": true,
                "serverSide": true,
                "orderCellsTop": true,
                "ajax":{
                    "url": "{{ route('setting.user.table.data') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
                "columns": [
                    { "data": "name" },
                    { "data": "role_name" },
                    { "data": "is_active" },
                    { "data": "action" }
                ]
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
        });
    </script>
@endsection