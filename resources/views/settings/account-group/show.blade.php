@extends('layouts.master')
@section('title', 'Account Group Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Account Group') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">{{ $accountGroup->name }}</h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                <a href="{{ route('setting.account.group.edit', $accountGroup) }}"
                                   class="btn waves-effect waves-light btn-primary btn-sm" target="_blank">
                                    <i class="fa fa-pencil"></i> Edit
                                </a>
                                <button class="btn waves-effect waves-light btn-danger btn-sm delete-btn">
                                    <i class="fa fa-remove"></i> Delete
                                </button>
                            </div>
                            <div class="pull-right"></div>
                        </div>
                    </div>

                    <div class="row custom-top-margin">
                        <div class="col-md-9">
                            <div class="card card-body">
                                <h3>
                                    <b>{{ $accountGroup->name }}</b>
                                    <span class="pull-right text-muted">
                                        @if($accountGroup->is_active == 'Yes')
                                            {{ 'Active' }}
                                        @else
                                            {{ 'Inactive' }}
                                        @endif
                                    </span>
                                </h3>
                                <hr>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Name</strong>
                                                <br>
                                                <p class="text-muted">{{ $accountGroup->name ?? 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Is active?</strong>
                                                <br>
                                                <p class="text-muted">{{ $accountGroup->is_active ?? 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6"><strong>Parent</strong>
                                                <br>
                                                <p class="text-muted">{{ $accountGroup->parent->name ?? 'None' }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-xs-12"><strong>Description</strong>
                                                <br>
                                                <p class="text-muted">{{ $accountGroup->description ?? 'None' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">

                            <!-- recent comments -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.comment.index', ['model' => $accountGroup])
                                </div>
                            </div>

                            <!-- recent audit logs -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.log.index', ['model' => $accountGroup, 'modelName' => 'AccountGroup'])
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
    @include('general.comment.script', ['modelId' => $accountGroup->id])
    <script>
        $(document).ready(function () {
            $('.delete-btn').click(function () {
                var deleteUrl = '{{ route('setting.account.group.delete', $accountGroup->id) }}';
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
                                var type = result.success ? 'success' : 'warning';
                                var title = result.success ? 'Deleted!' : 'Unable to delete!';
                                swal(title, result.message, type);
                                if (type === 'success') {
                                    setTimeout(function () {
                                        window.location.href = '{{ route('setting.account.group.index') }}';
                                    }, 500);
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
