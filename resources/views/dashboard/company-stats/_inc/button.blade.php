<div class="clearfix m-t-10">
    <div class="pull-left">
        <button ng-click="generate()" class="btn btn-info"><i class="ti-filter"></i>
            Generate
        </button>
        <button ng-click="resetFilters()" class="btn btn-inverse"><i class="ti-eraser"></i> Reset</button>
    </div>
    <div class="pull-right">
        <a href="@{{ getExportRoute() }}" class="btn btn-danger"><i
                    class="fa fa-file-pdf-o"></i> Export to PDF</a>
        <a href="@{{ getPrintRoute() }}" class="btn btn-inverse"><i
                    class="fa fa-print"></i> Print View</a>
    </div>
</div>
<hr class="hr-dark">