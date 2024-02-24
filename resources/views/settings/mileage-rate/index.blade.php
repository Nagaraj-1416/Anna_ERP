@extends('layouts.master')
@section('title', 'Mileage Rate')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Mileage Rate') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('setting.mileage.rate.create') }}" class="btn btn-info"> <i class="fa fa-plus"></i> Create</a>
                    <div class="table-responsive">
                        <table id="dataTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Rate</th>
                                <th>Action</th>
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
                    "url": "{{ route('setting.mileage.rate.table.data') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
                "columns": [
                    { "data": "date" },
                    { "data": "rate" },
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

        // Delete role
        $('.card-body').on('click', '.delete-role', function() {
            var id = $(this).data('id');
            var deleteUrl  = '{{ route('setting.mileage.rate.delete', [ 'mileageRate'=>'ID']) }}';
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
                            setTimeout(function () {
                                location.reload()
                            }, 800);
                        }
                    });
                }
            });
        });
    </script>
@endsection