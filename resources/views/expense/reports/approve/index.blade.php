@extends('layouts.master')
@section('title', 'Report Approvals')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Expense') !!}
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
                                <th>Title</th>
                                <th>Report No</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Status</th>
                                <th style="width:15%;">Action</th>
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
                    "url": "{{ route('expense.reports.approvals.data.table') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
                "columns": [
                    { "data": "title" },
                    { "data": "report_no" },
                    { "data": "report_from" },
                    { "data": "report_to" },
                    { "data": "status" },
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
        var cardBody = $('.card-body');
        cardBody.on('click', '.approve-report', function() {
            var id = $(this).data('id');
            approveProcess(id, 'Approved');
        });

        // Delete role
        cardBody.on('click', '.reject-report', function() {
            var id = $(this).data('id');
            approveProcess(id, 'Rejected');
        });

        function approveProcess(id, status) {
            var approveUrl  = '{{ route('expense.reports.approvals.approve', [ 'report'=>'ID']) }}';
            approveUrl = approveUrl.replace('ID', id);
            var text = (status === 'Approved') ? 'Approve' : 'Reject';
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: status === 'Approved' ? '#26c6da' : '#DB2828',
                confirmButtonText: 'Yes, ' + text +'!'
            }).then(function (isConfirm) {
                if (isConfirm.value){
                    $.ajax({
                        url: approveUrl,
                        type: 'POST',
                        data : {'_token' : '{{ csrf_token() }}', 'status' : status},
                        success: function(result) {
                            var type = result.success ? 'success' : 'warning';
                            var title = result.success ? status + '!' : 'Unable to ' + status +'!';
                            swal(title, result.message, type);
                            setTimeout(function () {
                                location.reload()
                            }, 800);
                        }
                    });
                }
            });
        }
    </script>
@endsection