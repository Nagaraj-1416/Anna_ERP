@extends('layouts.master')
@section('title', 'Role Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">{{ $role->name or 'N/A' }}</h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                @can('edit', $role)
                                <a href="{{ route('setting.role.edit', $role->id) }}" class="btn waves-effect waves-light btn-primary btn-sm" target="_blank">
                                    <i class="fa fa-pencil"></i> Edit
                                </a>
                                @endcan
                                @can('delete', $role)
                                    <button type="button" data-id="{{ $role->id }}" class="btn waves-effect waves-light btn-danger btn-sm" id="delete-btn">
                                        <i class="fa fa-remove"></i> Delete
                                    </button>
                                @endcan
                            </div>
                            <div class="pull-right"></div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-3 col-xs-6 b-r"> <strong>Name</strong>
                            <br>
                            <p class="text-muted">{{ $role->name or 'None' }}</p>
                        </div>
                        <div class="col-md-3 col-xs-6 b-r"> <strong>Access level</strong>
                            <br>
                            <p class="text-muted">{{ $role->access_level or 'None' }}</p>
                        </div>
                        <div class="col-md-3 col-xs-6"> <strong>Is role default?</strong>
                            <br>
                            <p class="text-muted">{{ $role->is_deletable == 'Yes' ? 'No' : 'Yes' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-6"> <strong>Description</strong>
                            <br>
                            <p class="text-muted">{{ $role->description or 'None' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-9">
            @include('settings.role._inc.permissions', ['disable' => true])
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex no-block">
                        <h4 class="card-title">Associated Users</h4>
                        <div class="ml-auto"></div>
                    </div>
                    @if(!count($users))
                        <div class="row box-title-with-margin">
                            <div class="col-md-12">
                                <span class="text-muted">No Users Associated.</span>
                            </div>
                        </div>
                    @else
                        <div class="message-box contact-box">
                            <div class="message-widget contact-widget">
                                @foreach($users as $user)
                                    <a target="_blank" href="#">
                                        <div class="user-img">
                                            <img src="{{route('setting.staff.image', [$user->staffs()->first()])}}" alt="user" class="img-circle">
                                            <span class="profile-status {{ $user->is_active == 'Yes' ? 'online' : 'busy' }} pull-right"></span>
                                        </div>
                                        <div class="mail-contnet">
                                            <h5>{{ $user->name }}</h5> <span class="mail-desc">{{ $user->email }}</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('#delete-btn').click(function() {
            var id = $(this).data('id');
            var deleteUrl  = '{{ route('setting.role.delete', [ 'staff'=>'ID']) }}';
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
                            if (type === 'success'){
                                setTimeout(function () {
                                    window.location.href = '{{ route('setting.role.index') }}';
                                }, 800);
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection