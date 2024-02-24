<div class="card">
    <div class="card-body">
        <div class="pull-left">
            <h3 style="display:inline"><b>CUSTOMERS</b>
            </h3>
            @if(isset($exportRoute))
                <a href="{{ $exportRoute }}"
                   class="btn waves-effect waves-light btn-pdf btn-sm m-b-5">
                    <i class="fa fa-file-pdf-o"></i> PDF
                </a>
            @endif
            @if(isset($excelExport))
                <a href="{{ $excelExport }}"
                   class="btn waves-effect waves-light btn-excel btn-sm m-b-5">
                    <i class="fa fa-file-pdf-o"></i> Excel
                </a>
            @endif
        </div>
        <div class="pull-right">
            <h3><span class="pull-right">Total Customers: @{{ customers.length }}</span></h3>
        </div>
        <div class="clearfix">
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Mobile</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-show="customers.length" dir-paginate="customer in customers | itemsPerPage:10"
                    pagination-id="customer_pagination">
                    <td>
                        <a target="_blank" href="/sales/customer/@{{ customer.id }}">
                            @{{ customer.code }}
                        </a>
                    </td>
                    <td>@{{ customer.display_name }}</td>
                    <td>@{{ customer.phone }}</td>
                    <td>@{{ customer.mobile }}</td>
                </tr>
                <tr ng-show="!customers.length">
                    <td colspan="2">No Customers Found...</td>
                </tr>
                </tbody>
            </table>

            <hr ng-if="customers.length > 5">
            <div class="pull-right">
                <dir-pagination-controls pagination-id="customer_pagination"></dir-pagination-controls>
            </div>
        </div>
    </div>
</div>