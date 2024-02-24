@extends('layouts.master')
@section('title', 'Due Invoices')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Due Invoices') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="display nowrap table table-hover table-striped table-bordered"
                               cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Invoice Code</th>
                                <th>Invoice Date</th>
                                <th>Due Date</th>
                                <th>Amount</th>
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
                'columnDefs': [
                    {
                        targets: -1,
                        className: 'text-right'
                    }
                ],
                "processing": true,
                "serverSide": true,
                "orderCellsTop": true,
                "ajax": {
                    "url": "{{ route('dashboard.list.due', ['modal' => 'invoice', 'dateRange' => $dateRange]) }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {"data": "customer"},
                    {"data": "invoice_no"},
                    {"data": "invoice_date"},
                    {"data": "due_date"},
                    {"data": "amount"},
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
    </script>
@endsection