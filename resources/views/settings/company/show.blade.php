@extends('layouts.master')
@section('title', 'Company Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">{{ $company->code }}</h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                <button class="btn waves-effect waves-light btn-info btn-sm" id="assign_staff_btn">
                                    <i class="fa fa-user-circle-o"></i> Assign Staff
                                </button>
                                <a href="{{ route('setting.company.edit', [$company]) }}"
                                   class="btn waves-effect waves-light btn-primary btn-sm" target="_blank">
                                    <i class="fa fa-pencil"></i> Edit
                                </a>
                            </div>
                            <div class="pull-right"></div>
                        </div>
                    </div>
                    @include('_inc.assign-staff.index', [
                        'actionURL' => route('setting.company.assign.staff', $company->id),
                        'searchURL' => route('setting.company.staff.search', $company->id)
                    ])

                    <div class="row custom-top-margin">
                        <div class="col-md-9">
                            <div class="card card-body">
                                <h3>
                                    <b>{{ $company->name }}</b>
                                    <span class="pull-right text-muted">
                                        @if($company->is_active == 'Yes')
                                            {{ 'Active' }}
                                        @else
                                            {{ 'Inactive' }}
                                        @endif
                                    </span>
                                </h3>
                                <hr>

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="card blog-widget">
                                            <div class="card-body">
                                                <div class="blog-image">
                                                    <img src="{{route('setting.company.logo', [$company])}}" alt="img"
                                                         class="img-responsive">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Business location</strong>
                                                <br>
                                                <p class="text-muted">{{ $company->business_location or 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Email</strong>
                                                <br>
                                                <p class="text-muted">{{ $company->email or 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6"><strong>Is company active?</strong>
                                                <br>
                                                <p class="text-muted">{{ $company->is_active }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Phone</strong>
                                                <br>
                                                <p class="text-muted">{{ $company->phone or 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Fax</strong>
                                                <br>
                                                <p class="text-muted">{{ $company->fax or 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Mobile</strong>
                                                <br>
                                                <p class="text-muted">{{ $company->mobile or 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6"><strong>Website</strong>
                                                <br>
                                                <p class="text-muted">{{ $company->website or 'None' }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Fiscal year starts month</strong>
                                                <br>
                                                <p class="text-muted">{{ monthIdentifier($company->fy_starts_month) ?? '' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Fiscal year starts from</strong>
                                                <br>
                                                <p class="text-muted">{{ $company->fy_starts_from or 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Business starts at</strong>
                                                <br>
                                                <p class="text-muted">{{ $company->business_starts_at or 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6"><strong>Business ends at</strong>
                                                <br>
                                                <p class="text-muted">{{ $company->business_end_at or 'None' }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Base currency</strong>
                                                <br>
                                                <p class="text-muted">{{ $company->base_currency or 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Timezone</strong>
                                                <br>
                                                <p class="text-muted">{{ $company->timezone or 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Date time format</strong>
                                                <br>
                                                <p class="text-muted">{{ $company->date_time_format or 'None' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h5 class="box-title box-title-with-margin">Address</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3 col-xs-12 b-r"><strong>Street one</strong>
                                        <br>
                                        <p class="text-muted">{{ $address->street_one or 'None' }}</p>
                                    </div>
                                    <div class="col-md-3 col-xs-12 b-r"><strong>Street two</strong>
                                        <br>
                                        <p class="text-muted">{{ $address->street_two or 'None' }}</p>
                                    </div>
                                    <div class="col-md-3 col-xs-12 b-r"><strong>City</strong>
                                        <br>
                                        <p class="text-muted">{{ $address->city or 'None' }}</p>
                                    </div>
                                    <div class="col-md-3 col-xs-12"><strong>Province</strong>
                                        <br>
                                        <p class="text-muted">{{ $address->province or 'None' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h3><b>DEPARTMENTS</b> <span
                                                class="pull-right">Total Departments: {{ $company->departments()->count() }}</span>
                                    </h3>
                                    <hr>
                                    <div class="table-responsive">
                                        <table class="table color-table muted-table">
                                            <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Mobile</th>
                                                <th>Fax</th>
                                                <th>Email</th>
                                                <th>Is Active?</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($company->departments()->get() as $department)
                                                <tr>
                                                    <td>
                                                        <a target="_blank"
                                                           href="{{ route('setting.department.show', [$department]) }}">{{ $department->code ?? 'None' }}</a>
                                                    </td>
                                                    <td>{{ $department->name ?? 'None' }}</td>
                                                    <td>{{ $department->phone ?? 'None' }}</td>
                                                    <td>{{ $department->mobile ?? 'None'}}</td>
                                                    <td>{{ $department->fax ?? 'None' }}</td>
                                                    <td>{{ $department->email ?? 'None'}}</td>
                                                    <td>{{ $department->is_active ?? 'None'}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h3><b>STORES</b> <span
                                                class="pull-right">Total Stores: {{ $company->stores()->count() }}</span>
                                    </h3>
                                    <hr>
                                    <div class="table-responsive">
                                        <table class="table color-table muted-table">
                                            <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Mobile</th>
                                                <th>Fax</th>
                                                <th>Email</th>
                                                <th>Is active?</th>

                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($company->stores()->get() as $store)
                                                <tr>
                                                    <td>
                                                        <a target="_blank"
                                                           href="{{ route('setting.store.show', [$store]) }}">{{ $store->code ?? 'None'}}</a>
                                                    </td>
                                                    <td>{{ $store->name ?? 'None'}}</td>
                                                    <td>{{ $store->phone ?? 'None'}}</td>
                                                    <td>{{ $store->mobile ?? 'None'}}</td>
                                                    <td>{{ $store->fax ?? 'None'}}</td>
                                                    <td>{{ $store->email ?? 'None'}}</td>
                                                    <td>{{ $store->is_active ?? 'None'}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            @include('_inc.relation-table.staff', ['model' => $company])

                            <div class="card">
                                <div class="card-body">
                                    <h3><b>SALES LOCATIONS</b> <span
                                                class="pull-right">Total Sales locations: {{ $company->salesLocations()->count() }}</span>
                                    </h3>
                                    <hr>
                                    <div class="table-responsive">
                                        <table class="table color-table muted-table">
                                            <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>Phone</th>
                                                <th>Mobile</th>
                                                <th>Email</th>
                                                <th>Is active?</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($company->salesLocations()->get() as $location)
                                                <tr>
                                                    <td>
                                                        <a target="_blank"
                                                           href="{{ route('setting.sales.location.show', [$location]) }}">{{ $location->code ?? 'None' }}</a>
                                                    </td>
                                                    <td>{{ $location->name ?? 'None' }}</td>
                                                    <td>{{ $location->type ?? 'None' }}</td>
                                                    <td>{{ $location->phone ?? 'None' }}</td>
                                                    <td>{{ $location->mobile ?? 'None' }}</td>
                                                    <td>{{ $location->email ?? 'None' }}</td>
                                                    <td>{{ $location->is_active ?? 'None' }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-3">

                            <!-- recent comments -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.comment.index', ['model' => $company])
                                </div>
                            </div>

                            <!-- recent audit logs -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.log.index', ['model' => $company, 'modelName' => 'Company'])
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
    @include('general.comment.script', ['modelId' => $company->id])
@endsection
