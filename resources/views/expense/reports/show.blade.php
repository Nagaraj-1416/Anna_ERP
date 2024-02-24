@extends('layouts.master')
@section('title', 'Report Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Expense') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">{{ $report->report_no }}</h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                @can('edit', $report)
                                    <a href="{{ route('expense.reports.edit', [$report->id]) }}"
                                       class="btn waves-effect waves-light btn-primary btn-sm"
                                       target="_blank">
                                        <i class="fa fa-pencil"></i> Edit
                                    </a>
                                @endcan
                                @php
                                    $reimbursementAmount = reportReimbursementAmount($report);
                                @endphp
                                @if($reimbursementAmount > $report->reimburses->sum('amount') && ($report->status == 'Approved' || $report->status == 'Partially Reimbursed'))
                                    @can('create', new \App\ExpenseReportReimburse())
                                        <button class="btn waves-effect waves-light btn-info btn-sm" id="createRei">
                                            <i class="fa fa-plus"></i> Record Reimbursement
                                        </button>
                                    @endcan
                                @endif
                            </div>
                            <div class="pull-right">
                                @can('export', $report)
                                    <a href="{{ route('expense.reports.export', [$report]) }}"
                                       class="btn waves-effect waves-light btn-inverse btn-sm">
                                        <i class="fa fa-file-pdf-o"></i> Export to PDF
                                    </a>
                                @endcan
                                @can('print', $report)
                                    <a href="{{ route('expense.reports.print', [$report]) }}"
                                       class="btn waves-effect waves-light btn-inverse btn-sm">
                                        <i class="fa fa-print"></i> Print
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                @include('expense.reports.reimbursement.create')
                @include('expense.reports.reimbursement.edit')
                <!-- estimate summary and history -->
                    <div class="row custom-top-margin">
                        <div class="col-md-9">
                            <div class="card card-body">
                                <h3>
                                    <b>EXPENSE REPORT</b> |
                                    <small class="{{ statusLabelColor($report->status) }}">
                                        {{ $report->status }}
                                    </small>
                                    <span class="pull-right">#{{ $report->report_no }}</span></h3>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive po-line-items">
                                            <table class="table color-table inverse-table so-table">
                                                <thead>
                                                <tr>
                                                    <th>Expense no</th>
                                                    <th>Expense date</th>
                                                    <th>Expense category</th>
                                                    <th style="width: 10%;" class="text-right">Amount</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($report->expenses as $expense)
                                                    <tr>
                                                        <td>{{ $expense->expense_no }}</td>
                                                        <td>{{ $expense->expense_date }}</td>
                                                        <td>{{ $expense->category->name or 'None' }}</td>
                                                        <td class="text-right">{{ number_format($expense->amount, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                                @if($report->expenses->count())
                                                    <tr>
                                                        <td colspan="3" class="text-right"><b>Total Expense Amount:</b>
                                                        </td>
                                                        <td class="text-right">{{ number_format($report->expenses->sum('amount'), 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" class="text-right"><b>Reimbursable Amount:</b>
                                                        </td>
                                                        <td class="text-right">{{ number_format($reimbursementAmount, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" class="text-right"><b>Reimbursed Amount:</b>
                                                        </td>
                                                        <td class="text-right">{{ number_format($report->reimburses->sum('amount'), 2) }}</td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4 class="box-title box-title-with-margin">Other Details</h4>
                                        <hr>
                                    </div>
                                    <div class="col-md-3">
                                        <p><b>Report title :</b> {{ $report->title or 'None' }}</p>
                                        <p><b>Report from :</b> {{ $report->report_from or 'None' }}</p>
                                        <p><b>Report to :</b> {{ $report->report_to or 'None' }}</p>
                                        <p><b>Business type :</b> {{ $report->businessType->name or 'None' }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p>
                                            <b>Approved by :</b>
                                            {{ $report->approvedBy->name or 'None' }}
                                        </p>
                                        <p><b>Prepared by :</b> {{ $report->preparedBy->name or 'None' }}</p>
                                        <p><b>Company :</b> {{ $report->company->name or 'None' }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><b>Submitted On :</b> {{ $report->submitted_on or 'None' }}</p>
                                        <p><b>Submitted By :</b> {{ $report->submittedBy->name or 'None' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-body">
                                <h3><b>Reimbursements</b></h3>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive po-line-items">
                                            <table class="table color-table inverse-table so-table">
                                                <thead>
                                                <tr>
                                                    <th>Reimbursed on</th>
                                                    <th style="width: 25%;">Paid through</th>
                                                    <th style="width: 15%;" class="text-right">Amount</th>
                                                    <th style="width: 10%;" class="text-right">Actions</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if($report->reimburses->count() == 0)
                                                    <tr>
                                                        <td colspan="4">No Reimbursements Found...</td>
                                                    </tr>
                                                @endif
                                                @foreach($report->reimburses as $reimburse)
                                                    <tr>
                                                        <td>{{ $reimburse->reimbursed_on }}</td>
                                                        <td>{{ $reimburse->paidThroughAccount ? $reimburse->paidThroughAccount->name : null }}</td>
                                                        <td class="text-right">{{ number_format($reimburse->amount, 2) }}</td>
                                                        <td class="text-right">
                                                            @can('edit', $reimburse)
                                                                <button type="Button"
                                                                        class="btn btn-primary btn-sm reimburse-edit"
                                                                        data-id="{{ $reimburse->id }}">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>
                                                            @endcan
                                                            @can('delete', $reimburse)
                                                                <button type="Button"
                                                                        class="btn btn-danger btn-sm reimburse-delete"
                                                                        data-id="{{ $reimburse->id }}">
                                                                    <i class="fa fa-remove"></i>
                                                                </button>
                                                            @endcan
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h3><b>ATTACHMENTS</b> <span
                                                class="pull-right">Total: {{ count($report->documents) }}</span></h3>
                                    <hr>
                                    @include('_inc.document.view', ['model' => $report])
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            @if($report->status == 'Submitted' && $report->approved_by == auth()->id())
                                <div class="card border-success text-center estimate-approval-panel">
                                    <div class="card-body">
                                        <h3 class="card-title text-success"><i class="fa fa-clock-o"></i> Waiting For
                                            Approval</h3>
                                        <p class="card-subtitle"> This report is waiting for your approval. They can
                                            take further actions once report is <code>Approved</code>.</p>
                                        <button class="btn btn-success approve-report">
                                            <i class="fa fa-check"></i> Approve
                                        </button>
                                        <button class="btn btn-danger reject-report">
                                            <i class="fa fa-times"></i> Reject
                                        </button>
                                    </div>
                                </div>
                            @endif
                            @if($report->status == 'Draft' && $report->approved_by == null)
                                <div class="card border-success text-center estimate-approval-panel">
                                    <div class="card-body">
                                        <h3 class="card-title text-success"><i class="fa fa-clock-o"></i> Submit To
                                            Approval</h3>
                                        <p class="card-subtitle"> This report is waiting for approval process. You can
                                            take further actions once report is <code>Approved</code>.</p>
                                        {{ form()->open([ 'route' => ['expense.reports.submit.to.approvals', $report], 'method' => 'POST']) }}
                                        <div class="form-group {{ $errors->has('approved_by') ? 'has-danger' : '' }}">
                                            <div class="ui fluid  search selection dropdown approved-by-drop-down {{ $errors->has('approved_by') ? 'error' : '' }}">
                                                <input type="hidden" name="approved_by">
                                                <i class="dropdown icon"></i>
                                                <div class="default text">choose an approver</div>
                                                <div class="menu"></div>
                                            </div>
                                            <p class="form-control-feedback">{{ $errors->first('approved_by') }}</p>
                                        </div>
                                        <button class="btn btn-success">
                                            <i class="fa fa-check"></i> Submit
                                        </button>
                                        {{ form()->close() }}
                                    </div>
                                </div>
                        @endif
                        <!-- recent comments -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.comment.index', ['model' => $report])
                                </div>
                            </div>
                            <!-- recent audit logs -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.log.index', ['model' => $report, 'modelName' => 'ExpenseReport'])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('general.comment.script', ['modelId' => $report->id])
    @include('_inc.document.script', ['model' => $report])
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script>
        $reiFormEl = {
            createRei: $('#createRei'),
            reiForm: $('#reimbursementForm'),
            reiEditForm: $('#reimbursementEditForm'),
            cancelBtn: $('#cancelBtn'),
            ptDropdown: $('.paid-through-drop-down'),
            reimburseDeleteBtn: $('.reimburse-delete'),
            reimburseEditBtn: $('.reimburse-edit'),
            approvedByDropDown: $('.approved-by-drop-down')
        };


        /** company dropdown init */
        var approverSearchUrl = '{{ route('setting.user.search') }}';
        $reiFormEl.approvedByDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            apiSettings: {
                url: approverSearchUrl + '/{query}',
                cache: false
            }
        });


        /** set values to semantic UI drop-down */
        function setDropDownValue(dd, value, name) {
            if (!dd) return false;
            dd.dropdown("refresh");
            dd.dropdown('set value', value);
            dd.dropdown('set text', name);
        };

        $reiFormEl.createRei.click(function (e) {
            e.preventDefault();
            $reiFormEl.reiForm.removeClass('hidden');
        });

        $reiFormEl.cancelBtn.click(function (e) {
            e.preventDefault();
            $reiFormEl.reiForm.addClass('hidden');
        });

        @if ($errors->has('reimbursed_amount') && old('mode') == 'create')
        $reiFormEl.reiForm.removeClass('hidden');
        @endif
        @if ($errors->has('reimbursed_paid_through') && old('mode') == 'create')
        $reiFormEl.reiForm.removeClass('hidden');
        @endif
        @if ($errors->has('reimbursed_on') && old('mode') == 'create')
        $reiFormEl.reiForm.removeClass('hidden');

        @endif

        function showEditForm() {
            var oldValue = [{!! json_encode(old()) !!}][0];
            $reiFormEl.reiEditForm.removeClass('hidden');
            if (oldValue.reimburse_id) {
                var reimburseUpdateUrl = '{{ route('expense.reports.reimbursement.show', ['report' => $report->id, 'reimburse'=>'ID']) }}';
                reimburseUpdateUrl = reimburseUpdateUrl.replace('ID', oldValue.reimburse_id);
                $reiFormEl.reiEditForm.find('form').attr('action', reimburseUpdateUrl);
            }
        }

        @if ($errors->has('reimbursed_amount') && old('mode') == 'edit')
        showEditForm();
        @endif
        @if ($errors->has('reimbursed_paid_through') && old('mode') == 'edit')
        showEditForm();
        @endif
        @if ($errors->has('reimbursed_on') && old('mode') == 'edit')
        showEditForm();
        @endif

        /** paid through account dropdown init */
        var ptSearchUrl = '{{ route('finance.paid.through.account.search') }}';
        $reiFormEl.ptDropdown.dropdown({
            forceSelection: false,
            saveRemoteData: false,
            apiSettings: {
                url: ptSearchUrl + '/{query}',
                cache: false
            }
        });

        @if (old('_token'))
        setDropDownValue($reiFormEl.ptDropdown, '{{ old('reimbursed_paid_through') }}', '{{ old('reimbursed_paid_through_name') }}');
        @endif

        $reiFormEl.reimburseEditBtn.click(function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            if (!id) return;
            $reiFormEl.reiEditForm.removeClass('hidden');
            var reimburseShowUrl = '{{ route('expense.reports.reimbursement.show', ['report' => $report->id, 'reimburse'=>'ID']) }}';
            var reimburseUpdateUrl = '{{ route('expense.reports.reimbursement.update', ['report' => $report->id, 'reimburse'=>'ID']) }}';
            reimburseShowUrl = reimburseShowUrl.replace('ID', id);
            reimburseUpdateUrl = reimburseUpdateUrl.replace('ID', id);
            $reiFormEl.reiEditForm.find('form').attr('action', reimburseUpdateUrl);
            $.ajax({
                url: reimburseShowUrl,
                type: 'GET',
                success: function (response) {
                    $('html, body').animate({
                        scrollTop: ($reiFormEl.reiEditForm.offset().top - 250)
                    }, 1000);
                    $reiFormEl.reiEditForm.find('input[name="reimbursed_on"]').val(response.reimbursed_on);
                    $reiFormEl.reiEditForm.find('input[name="reimbursed_amount"]').val(response.amount);
                    $reiFormEl.reiEditForm.find('textarea[name="reimbursed_notes"]').val(response.notes);
                    if (response.paid_through_account) {
                        setDropDownValue($reiFormEl.ptDropdown, response.paid_through_account.id, response.paid_through_account.name);
                    }

                }
            })
        });

        $reiFormEl.reimburseDeleteBtn.click(function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var approvalUrl = '{{ route('expense.reports.reimbursement.delete', ['report' => $report->id, 'reimburse'=>'ID']) }}';
            approvalUrl = approvalUrl.replace('ID', id);
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action! Are you sure want to delete this reimburse?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4aba45',
                confirmButtonText: 'Yes, Delete!'
            }).then(function (isConfirm) {
                if (isConfirm.value) {
                    $.ajax({
                        url: approvalUrl,
                        type: 'DELETE',
                        data: {'_token': '{{ csrf_token() }}'},
                        success: function (result) {
                            swal(
                                'Deleted!',
                                'Reimburse deleted successfully!',
                                'success'
                            );
                            setTimeout(location.reload(), 300);
                        }
                    });
                }
            });
        });


        $('.approve-report').click(function (e) {
            e.preventDefault();
            approveProcess('Approved');
        });

        $('.reject-report').click(function (e) {
            e.preventDefault();
            approveProcess('Rejected');
        });

        function approveProcess(status) {
            var approveUrl = '{{ route('expense.reports.approvals.approve', [$report]) }}';
            var text = (status === 'Approved') ? 'Approve' : 'Reject';
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: status === 'Approved' ? '#26c6da' : '#DB2828',
                confirmButtonText: 'Yes, ' + text + '!'
            }).then(function (isConfirm) {
                if (isConfirm.value) {
                    $.ajax({
                        url: approveUrl,
                        type: 'POST',
                        data: {'_token': '{{ csrf_token() }}', 'status': status},
                        success: function (result) {
                            var type = result.success ? 'success' : 'warning';
                            var title = result.success ? status + '!' : 'Unable to ' + status + '!';
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