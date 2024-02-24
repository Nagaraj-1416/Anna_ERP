@extends('layouts.master')
@section('title', 'Vehicle Makes')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @can('create', new \App\VehicleMake())
                        <a href="{{ route('setting.vehicle.make.create') }}" class="btn btn-info"> <i
                                    class="fa fa-plus"></i> Create</a>
                    @endcan
                    <div class="table-responsive">
                        <table id="dataTable" class="display nowrap table table-hover table-striped table-bordered"
                               cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Is Active?</th>
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

@section('style')
@endsection

@section('script')
    <script src="{{ asset('js/vendor/table.js') }}"></script>
    <script>
        $(document).ready(function () {
            var $table = $('#dataTable');

            var table = $table.DataTable({
                "processing": true,
                "serverSide": true,
                "orderCellsTop": true,
                "ajax": {
                    "url": "{{ route('setting.vehicle.make.table.data') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {"data": "name"},
                    {"data": "is_active"},
                    {"data": "action"}
                ]
            });

            var $searchHeader = $('<tr class="form-material"></tr>');
            $table.find('thead th').each(function () {
                var title = $table.find('thead tr:eq(0) th').eq($(this).index()).text();
                if (title === 'Actions') {
                    $searchHeader.append('<th></th>');
                } else {
                    $searchHeader.append('<th><input type="text" placeholder="search. . ." class="form-control  form-control-line form-control-sm table-search-field"/></th>');
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

        $('.card-body').on('click', '.delete-vehicle-make', function () {
            var id = $(this).data('id');
            var deleteUrl = '{{ route('setting.vehicle.make.delete', [ 'vehicleType'=>'ID']) }}';
            deleteUrl = deleteUrl.replace('ID', id);
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
                        success: function (result) {
                            swal(
                                'Deleted!',
                                'Vehicle Type deleted successfully!',
                                'success'
                            );
                            setTimeout(location.reload(), 300);
                        }
                    });
                }
            });
        });
    </script>
@endsection