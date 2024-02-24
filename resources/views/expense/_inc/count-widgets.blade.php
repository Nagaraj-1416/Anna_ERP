<div class="row">
    <!-- Column -->
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="d-flex flex-row">
                <div class="p-10 bg-primary">
                    <h3 class="text-white box m-b-0"><i class="ti-receipt"></i></h3></div>
                <div class="align-self-center m-l-20">
                    <h3 class="m-b-0 text-primary ng-binding">@{{ totalExpenses }}</h3>
                    <h6 class="text-muted m-b-0">Total Expenses</h6></div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="d-flex flex-row">
                <div class="p-10 bg-info">
                    <h3 class="text-white box m-b-0"><i class="ti-files"></i></h3></div>
                <div class="align-self-center m-l-20">
                    <h3 class="m-b-0 text-info ng-binding">@{{ totalReports }}</h3>
                    <h6 class="text-muted m-b-0">Total Reports</h6></div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="d-flex flex-row">
                <div class="p-10 bg-warning">
                    <h3 class="text-white box m-b-0"><i class="ti-shopping-cart"></i></h3></div>
                <div class="align-self-center m-l-20">
                    <h3 class="m-b-0 text-warning ng-binding">@{{ totalNonReportExpense }}</h3>
                    <h6 class="text-muted m-b-0">Not Reported Expenses</h6></div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="d-flex flex-row">
                <div class="p-10 bg-success">
                    <h3 class="text-white box m-b-0"><i class="ti-shopping-cart"></i></h3></div>
                <div class="align-self-center m-l-20">
                    <h3 class="m-b-0 text-success ng-binding">@{{ unSubmitted }}</h3>
                    <h6 class="text-muted m-b-0">Not Submitted Expenses</h6></div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="d-flex flex-row">
                <div class="p-10 bg-danger">
                    <h3 class="text-white box m-b-0"><i class="ti-shopping-cart"></i></h3></div>
                <div class="align-self-center m-l-20">
                    <h3 class="m-b-0 text-danger ng-binding">@{{ submitted }}</h3>
                    <h6 class="text-muted m-b-0">Submitted Expenses</h6></div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="d-flex flex-row">
                <div class="p-10 bg-primary">
                    <h3 class="text-white box m-b-0"><i class="ti-receipt"></i></h3></div>
                <div class="align-self-center m-l-20">
                    <h3 class="m-b-0 text-primary ng-binding">@{{ approved }}</h3>
                    <h6 class="text-muted m-b-0">Approved Expenses</h6></div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="d-flex flex-row">
                <div class="p-10 bg-info">
                    <h3 class="text-white box m-b-0"><i class="ti-receipt"></i></h3></div>
                <div class="align-self-center m-l-20">
                    <h3 class="m-b-0 text-info ng-binding">@{{ reimbursed }}</h3>
                    <h6 class="text-muted m-b-0">Reimbursed Expenses</h6></div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="d-flex flex-row">
                <div class="p-10 bg-success">
                    <h3 class="text-white box m-b-0"><i class="ti-write"></i></h3></div>
                <div class="align-self-center m-l-20">
                    <h3 class="m-b-0 text-success">@{{ submittedReport }}</h3>
                    <h6 class="text-muted m-b-0">Submitted Reports</h6></div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="d-flex flex-row">
                <div class="p-10 bg-inverse">
                    <h3 class="text-white box m-b-0"><i class="ti-money"></i></h3></div>
                <div class="align-self-center m-l-20">
                    <h3 class="m-b-0 text-inverse">@{{ reimbursedReport }}</h3>
                    <h6 class="text-muted m-b-0">Reimbursed Reports</h6></div>
            </div>
        </div>
    </div>
    <!-- Column -->
</div>