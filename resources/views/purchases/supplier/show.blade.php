@extends('layouts.master')
@section('title', 'Supplier Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Purchase') !!}
@endsection
@section('content')
    <div class="row" ng-controller="SupplierController">
        <div class="col-md-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">{{ $supplier->code.' - '.$supplier->full_name }}</h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                @can('edit', $supplier)
                                    <a href="{{ route('purchase.supplier.edit', [$supplier]) }}"
                                       class="btn waves-effect waves-light btn-primary btn-sm"
                                       target="_blank">
                                        <i class="fa fa-pencil"></i> Edit
                                    </a>
                                @endcan
                                @can('create', new \App\ContactPerson())
                                    <button class="btn waves-effect waves-light btn-info btn-sm"
                                            id="assign_contact_person">
                                        <i class="fa fa-phone-square"></i> Add Contact Person
                                    </button>
                                @endcan
                                @if(!($supplier->opening_balance && $supplier->opening_balance_at))
                                    <a target="_blank" href="" data-id="{{ $supplier->id }}"
                                       class="btn waves-effect waves-light btn-primary btn-sm opening-button">
                                        <i class="ti-money"></i> Add Opening Balance
                                    </a>
                                @endif
                            </div>
                            <div class="pull-right">
                                @can('statement', $supplier)
                                    <a target="_blank" href="{{ route('purchase.supplier.statement', [$supplier]) }}"
                                       class="btn waves-effect waves-light btn-inverse btn-sm">
                                        <i class="ti-receipt"></i> Supplier Statement
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>

                    <!-- contact person menu -->
                    <div class="row custom-top-margin">
                        <div class="col-md-12">
                            <div class="contact-person hidden" id="contact_person">
                                @include('_inc.contact-person.index', ['model' => $supplier])
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-9">
                            <div class="card card-body">
                                <h3>
                                    <b>{{ $supplier->display_name }}</b>
                                    <span class="pull-right text-muted">
                                    @if($supplier->is_active == 'Yes')
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
                                                    <img src="{{route('purchase.supplier.logo', [$supplier])}}"
                                                         alt="img" class="img-responsive">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md-3 col-xs-6 b-r"><strong>First name</strong>
                                                <br>
                                                <p class="text-muted">{{ $supplier->first_name or 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Last name</strong>
                                                <br>
                                                <p class="text-muted">{{ $supplier->last_name or 'None'}}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Phone</strong>
                                                <br>
                                                <p class="text-muted">{{ $supplier->phone or 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6"><strong>Fax</strong>
                                                <br>
                                                <p class="text-muted">{{ $supplier->fax or 'None'}}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Mobile</strong>
                                                <br>
                                                <p class="text-muted">{{ $supplier->mobile or 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Email</strong>
                                                <br>
                                                <p class="text-muted">{{ $supplier->email or 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6"><strong>Website</strong>
                                                <br>
                                                <p class="text-muted">{{ $supplier->website or 'None' }}</p>
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
                                </div>

                                @if($supplier->notes)
                                    <h5 class="box-title box-title-with-margin">Notes</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12 col-xs-6">
                                            <p class="text-muted">{{ $supplier->notes or 'None' }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- purchase orders -->
                        @include('purchases.general.anquler.orders', ['orders' => $supplier->orders])

                        <!-- purchase bills -->
                        @include('purchases.general.anquler.bills', ['bills' =>  $supplier->bills])

                        <!-- purchase bills payments -->
                        @include('purchases.general.anquler.payment', ['payments' => $supplier->payments])

                        <!-- credits -->
                        @include('purchases.general.anquler.credits', ['credits' => $supplier->credits])

                        <!-- expenses -->
                        @include('purchases.general.anquler.expenses', ['expenses' => $supplier->expenses])

                        <!-- journals -->
                            @include('general.journal.anquler.journals', ['journals' => $supplier->journals])

                        <!-- openings -->
                            @include('purchases.general.anquler.opening')
                            <div class="card">
                                <div class="card-body">
                                    <h3><b>ATTACHMENTS</b> <span
                                                class="pull-right">Total: {{ count($supplier->documents) }}</span></h3>
                                    <hr>
                                    @include('_inc.document.view', ['model' => $supplier])
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            @if($supplier->opening_balance && $supplier->opening_balance_at)
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex flex-row">
                                            <div class="round align-self-center round-primary"><i class="ti-money"></i></div>
                                            <div class="m-l-10 align-self-center">
                                                <h3 class="m-b-0">{{ number_format($supplier->opening_balance, 2) }}</h3>
                                                <h6 class="text-muted m-b-0">
                                                    Opening balance as at {{ carbon($supplier->opening_balance_at)->format('F j, Y') }}
                                                </h6>
                                                <a class="go-ref text-success" style="cursor: pointer;"><small>Click here to view references</small></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Supplier Outstanding Summary</h4>
                                    <hr>
                                    <div>
                                        <h3 class="card-title">
                                            <b>{{ number_format(supOutstanding($supplier)['ordered'], 2) }}</b></h3>
                                        <h6 class="card-subtitle">Purchase Order Amount
                                            <span class="pull-right">{{ number_format(getProgressValue(supOutstanding($supplier)['ordered'], supOutstanding($supplier)['paid']), 2) }}%</span>
                                        </h6>
                                    </div>
                                    <div class="custom-top-margin">
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                 style="width: {{ getProgressValue(supOutstanding($supplier)['ordered'], supOutstanding($supplier)['paid']) }}%; height:10px;"
                                                 aria-valuenow="25" aria-valuemin="0"
                                                 aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <h4>{{ number_format(supOutstanding($supplier)['billed'], 2) }}</h4>
                                            <h6 class="text-muted text-info">Total Billed</h6>
                                        </div>
                                        <div class="col-4">
                                            <h4>{{ number_format(supOutstanding($supplier)['paid'], 2) }}</h4>
                                            <h6 class="text-muted text-success">Total Paid</h6>
                                        </div>
                                        <div class="col-4">
                                            <h4>{{ number_format(supOutstanding($supplier)['balance'], 2) }}</h4>
                                            <h6 class="text-muted text-warning">Total Balance</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Customer Credit Summary</h4>
                                    <hr>
                                    <div>
                                        <h3 class="card-title">
                                            <b> {{ number_format(supCreditOutstanding($supplier)['credits'], 2) }} </b>
                                        </h3>
                                        <h6 class="card-subtitle">Total Credit
                                            <span class="pull-right">{{ number_format(getProgressValue(supCreditOutstanding($supplier)['credits'],
                                             (supCreditOutstanding($supplier)['refunded'] + supCreditOutstanding($supplier)['used']), true), 2) }}%</span>
                                        </h6>
                                    </div>
                                    <div class="m-t-5">
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                 style="width: {{ getProgressValue(supCreditOutstanding($supplier)['credits'], (supCreditOutstanding($supplier)['refunded'] + supCreditOutstanding($supplier)['used']), true) }}%; height:10px;"
                                                 aria-valuenow="25" aria-valuemin="100"
                                                 aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="card-divider">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <h4>{{ number_format(supCreditOutstanding($supplier)['refunded'], 2) }}</h4>
                                            <h6 class="text-muted text-info">Refunded</h6>
                                        </div>
                                        <div class="col-4">
                                            <h4>{{ number_format(supCreditOutstanding($supplier)['used'], 2) }}</h4>
                                            <h6 class="text-muted text-success">Used</h6>
                                        </div>
                                        <div class="col-4">
                                            <h4>{{ number_format(supCreditOutstanding($supplier)['balance'], 2) }}</h4>
                                            <h6 class="text-muted text-warning">Balance</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    @include('general.contact.index', ['contacts' => $contacts])
                                </div>
                            </div>

                            <!-- recent comments -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.comment.index', ['model' => $supplier])
                                </div>
                            </div>

                            <!-- recent audit logs -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.log.index', ['model' => $supplier, 'modelName' => 'Supplier'])
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('purchases.supplier._inc.opening.index')
@endsection
@section('script')
    @include('_inc.contact-person.script')
    @include('general.comment.script', ['modelId' => $supplier->id])
    @include('_inc.document.script', ['model' => $supplier])
    @include('general.helpers')
    @include('purchases.supplier._inc.opening.script')
    <script src="{{ asset('js/vendor/object-helper.js') }}"></script>
    <script>
        app.controller('SupplierController', ['$scope', '$http', function ($scope, $http) {
            $scope.journals = @json($supplier->journals);

            $scope.orders = @json($supplier->orders()->with(['bills', 'payments' => function ($q){
             $q->where('status', 'Paid');
            }])->get());

            $scope.bills = @json($supplier->bills()->with(['payments' => function ($q){
             $q->where('status', 'Paid');
            }])->get());

            $scope.payments = @json($supplier->payments()->with(['preparedBy', 'paidThrough'])->get());

            $scope.credits = @json($supplier->credits()->with(['refunds', 'payments'])->get());

            $scope.expenses = @json($supplier->expenses()->with(['category'])->get());

            $scope.references = @json($supplier->openingReferences);

            $scope.statusLabelColor = function ($status) {
                return statusLabelColor($status)
            };

            $scope.billOutstanding = function (bill) {
                return billOutstanding(bill);
            };

            $scope.poOutstanding = function (order) {
                return poOutstanding(order);
            };

            $scope.getTotal = function (array, get) {
                return sum(_.pluck(array, get));
            };

            $scope.getSupplierCreditLimit = function (credit) {
                return getSupplierCreditLimit(credit);
            };

        }]);

        $('document').ready(function () {
            $('.go-ref').click(function () {
                $('html, body').animate({
                    scrollTop: $(".opening-card").offset().top - 150
                }, 2000);
            });
        });
    </script>
@endsection