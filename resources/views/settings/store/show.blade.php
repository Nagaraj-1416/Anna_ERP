@extends('layouts.master')
@section('title', 'Store Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline-inverse">
            <div class="card-header">
                <h4 class="m-b-0 text-white">{{ $store->code }}</h4>
            </div>
            <div class="card-body">
                <!-- action buttons -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-left">
                            <button id="assign_staff_btn" class="btn waves-effect waves-light btn-info btn-sm">
                                <i class="fa fa-user-circle-o"></i> Assign Staff
                            </button>
                            <a href="{{ route('setting.store.edit', [$store]) }}" class="btn waves-effect waves-light btn-primary btn-sm" target="_blank">
                                <i class="fa fa-pencil"></i> Edit
                            </a>
                        </div>
                        <div class="pull-right"></div>
                    </div>
                </div>
                @include('_inc.assign-staff.index', [
                  'actionURL' => route('setting.store.assign.staff', $store->id),
                  'searchURL' => route('setting.store.staff.search', $store->id)
                ])

                <div class="row custom-top-margin">
                    <div class="col-md-9">
                        <div class="card card-body">
                            <h3>
                                <b>{{ $store->name }}</b> | {{ $store->type }}
                                <span class="pull-right text-muted">
                                    @if($store->is_active == 'Yes')
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
                                        <div class="col-md-3 col-xs-6 b-r"><strong>Company</strong>
                                            <br>
                                            <p class="text-muted">
                                                <a target="_blank" href="{{ route('setting.company.show', ['company' => $store->company]) }}">
                                                    {{ $store->company->name }}
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 col-xs-6 b-r"><strong>Phone</strong>
                                            <br>
                                            <p class="text-muted">{{ $store->phone or 'None' }}</p>
                                        </div>
                                        <div class="col-md-3 col-xs-6 b-r"><strong>Fax</strong>
                                            <br>
                                            <p class="text-muted">{{ $store->fax or 'None' }}</p>
                                        </div>
                                        <div class="col-md-3 col-xs-6 b-r"><strong>Mobile</strong>
                                            <br>
                                            <p class="text-muted">{{ $store->mobile or 'None' }}</p>
                                        </div>
                                        <div class="col-md-3 col-xs-6"><strong>Email</strong>
                                            <br>
                                            <p class="text-muted">{{ $store->email or 'None' }}</p>
                                        </div>
                                    </div>
                                    <h5 class="box-title box-title-with-margin">Notes</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12 col-xs-6">
                                            <p class="text-muted">{{ $store->notes or 'None' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @include('_inc.relation-table.staff', ['model' => $store])
                    </div>
                    <div class="col-md-3">

                        <!-- recent comments -->
                        <div class="card">
                            <div class="card-body">
                                @include('general.comment.index', ['model' => $store])
                            </div>
                        </div>

                        <!-- recent audit logs -->
                        <div class="card">
                            <div class="card-body">
                                @include('general.log.index', ['model' => $store, 'modelName' => 'Store'])
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
    @include('general.comment.script', ['modelId' => $store->id])
@endsection
