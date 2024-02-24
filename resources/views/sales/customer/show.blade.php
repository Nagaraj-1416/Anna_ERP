@extends('layouts.master')
@section('title', 'Customer Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row" ng-controller="CustomerController">
        <div class="col-md-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Customer Details</h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                @can('edit', $customer)
                                    <a href="{{ route('sales.customer.edit', [$customer]) }}"
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
                                @if(!($customer->gps_lat && $customer->gps_long))
                                    @can('edit', $customer)
                                        <button onclick="removeHidden()"
                                                class="btn waves-effect waves-light btn-info btn-sm"
                                                id="add_location_btn">
                                            <i class="fa fa-map"></i> Add Customer Location
                                        </button>
                                    @endcan
                                @endif
                                @if(!($customer->opening_balance && $customer->opening_balance_at))
                                    <a target="_blank" href="{{ route('sales.customer.opening.create', $customer->id) }}"
                                       class="btn waves-effect waves-light btn-primary btn-sm">
                                        <i class="ti-money"></i> Add Opening Balance
                                    </a>
                                @else
                                    <a target="_blank" href="{{ route('sales.customer.opening.edit', $customer->id) }}"
                                       class="btn waves-effect waves-light btn-warning btn-sm">
                                        <i class="ti-money"></i> Edit Opening Balance
                                    </a>
                                @endif
                            </div>
                            <div class="pull-right">
                                <a target="_blank" href="{{ route('sales.customer.ledger', [$customer]) }}"
                                   class="btn waves-effect waves-light btn-inverse btn-sm">
                                    <i class="ti-receipt"></i> Customer Ldeger
                                </a>
                                @can('statement', $customer)
                                    <a target="_blank" href="{{ route('sales.customer.statement', [$customer]) }}"
                                       class="btn waves-effect waves-light btn-inverse btn-sm">
                                        <i class="ti-receipt"></i> Customer Statement
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>

                    <!-- contact person menu -->
                    <div class="row custom-top-margin">
                        <div class="col-md-12">
                            <div class="contact-person hidden" id="contact_person">
                                @include('_inc.contact-person.index', ['model' => $customer])
                            </div>
                        </div>
                        <div class="col-md-12 hidden" id="map_form">
                            @include('sales.customer._inc.map', ['model' => $customer, 'id' => 'map1'])
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            <div class="card card-body">
                                <h3>
                                    <b>{{ $customer->display_name }} {{ $customer->tamil_name ? '('. $customer->tamil_name .')' : '' }}</b>
                                    <span class="pull-right text-muted">
                                    @if($customer->is_active == 'Yes')
                                            {{ 'Active' }}
                                        @else
                                            {{ 'Inactive' }}
                                        @endif
                                </span>
                                </h3>
                                <br />
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="card blog-widget">
                                            <div class="card-body">
                                                <div class="blog-image">
                                                    <img src="{{route('sales.customer.logo', [$customer])}}" alt="img"
                                                         class="img-responsive">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table custom-table m-t-10">
                                                    <tbody>
                                                        <tr>
                                                            <td><b>First name</b></td>
                                                            <td>{{ $customer->first_name or 'None' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Last name</b></td>
                                                            <td>{{ $customer->last_name or 'None' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Phone</b></td>
                                                            <td>{{ $customer->phone or 'None' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Mobile</b></td>
                                                            <td>{{ $customer->mobile or 'None' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Fax</b></td>
                                                            <td>{{ $customer->fax or 'None' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Email</b></td>
                                                            <td>{{ $customer->email or 'None' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Website</b></td>
                                                            <td>{{ $customer->website or 'None' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table custom-table m-t-10">
                                                    <tbody>
                                                        <tr>
                                                            <td><b>Sales route</b></td>
                                                            <td>{{ $customer->route->name or 'None' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Location</b></td>
                                                            <td>{{ $customer->location->name or 'None'}}</td>
                                                        </tr>
                                                        @if($customer->notes)
                                                        <tr>
                                                            <td colspan="2">
                                                                <b>Notes</b><br />
                                                                {{ $customer->notes or 'None' }}
                                                            </td>
                                                        </tr>
                                                        @endif
                                                        @if($address)
                                                        <tr>
                                                            <td colspan="2">
                                                                <b>Address</b><br />
                                                                @if($address)
                                                                    {{ $address->street_one }}
                                                                    @if($address->street_two)
                                                                        {{ $address->street_two }},
                                                                    @endif
                                                                    @if($address->city)
                                                                        {{ $address->city }},
                                                                    @endif
                                                                    @if($address->province)
                                                                        {{ $address->province }},
                                                                    @endif
                                                                    @if($address->postal_code)
                                                                        {{ $address->postal_code }},
                                                                    @endif
                                                                    @if($address->country)
                                                                        {{ $address->country->name }}.
                                                                    @endif
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($customer->gps_lat && $customer->gps_long)
                                    <h5 class="box-title box-title-with-margin">Location</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12 col-xs-6">
                                            @include('sales.customer._inc.map', ['model' => $customer])
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- sales orders -->
                            @include('sales.general.anquler.orders', ['orders' => $customer->orders])

                            <!-- sales invoice -->
                            @include('sales.general.anquler.invoices', ['invoices' =>  $customer->invoices])

                            <!-- sales invoice payments -->
                            @include('sales.general.anquler.payment', ['payments' => $customer->payments])

                            <!-- sales returns -->
                            @include('sales.general.anquler.returns', ['returns' => $returns])

                            <!-- sales visits -->
                            @include('sales.general.anquler.visits', ['visits' => $visits])

                            <!-- estimates -->
                            @include('sales.general.anquler.estimates', ['estimates' => $customer->estimates])

                            <!-- credits -->
                            @include('sales.general.anquler.credits', ['credits' => $customer->credits])

                            <!-- expenses -->
                            @include('sales.general.anquler.expenses', ['expenses' => $customer->expenses])

                            <!-- journals -->
                            @include('general.journal.anquler.journals', ['journals' => $customer->journals])

                            <!-- openings -->
                            @include('sales.general.anquler.opening')

                            <div class="card">
                                <div class="card-body">
                                    <h3><b>ATTACHMENTS</b> <span
                                                class="pull-right">Total: {{ count($customer->documents) }}</span></h3>
                                    <hr>
                                    @include('_inc.document.view', ['model' => $customer])
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card border-info">
                                <div class="card-body">
                                    <h4 class="card-title text-info">Customer Credit Limit</h4>
                                    <hr>
                                    <div>
                                        <h3 class="card-title"><b>{{ number_format($customer->cl_amount, 2) }}</b></h3>
                                        <h6 class="card-subtitle">CL used notification at
                                            <b>{{ $customer->cl_notify_rate }}</b>
                                        </h6>
                                        <h6 class="card-subtitle">Allowed credit balance limit days:
                                            <b>{{ $customer->cl_days }}</b>
                                        </h6>
                                    </div>
                                </div>
                            </div>

                            @if($customer->opening_balance && $customer->opening_balance_at)
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex flex-row">
                                            <div class="round align-self-center round-primary"><i class="ti-money"></i></div>
                                            <div class="m-l-10 align-self-center">
                                                <h3 class="m-b-0">{{ number_format($customer->opening_balance, 2) }}</h3>
                                                <h6 class="text-muted m-b-0">
                                                    Opening balance as at {{ carbon($customer->opening_balance_at)->format('F j, Y') }}
                                                </h6>
                                                <a class="go-ref text-success" style="cursor: pointer;">Click here to view references</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="card border-danger">
                                <div class="card-body">
                                    <h4 class="card-title text-danger">Outstanding Receivables</h4>
                                    <hr>
                                    <div class="d-flex flex-row">
                                        <div class="round align-self-center round-primary"><i class="ti-money"></i></div>
                                        <div class="m-l-10 align-self-center">
                                            <h3 class="m-b-0">{{ number_format((cusOutstanding($customer)['balance']), 2) }}</h3>
                                            <h6 class="text-muted m-b-0">
                                                Outstanding receivables as at {{ carbon()->now()->format('F j, Y') }}
                                            </h6>
                                            <small class="text-muted">(Opening Balance + Current Sales Order Balance)</small>
                                        </div>
                                    </div>
                                    <table class="table custom-table m-t-10">
                                        <tbody>
                                            <tr>
                                                <td>Total sales</td>
                                                <td class="text-right">{{ number_format(cusOutstanding($customer)['ordered'], 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Total received</td>
                                                <td class="text-right">{{ number_format(cusOutstanding($customer)['paid'], 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Balance</td>
                                                <td class="text-right" style="border-top: 2px #000 solid !important; width: 20%;">
                                                    {{ number_format(cusOutstanding($customer)['balance'], 2) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-right"><b>Total outstanding</b></td>
                                                <td class="text-right" style="border-bottom: 3px #000 solid !important;">
                                                    <b>{{ number_format((cusOutstanding($customer)['balance']), 2) }}</b>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card border-warning">
                                <div class="card-body">
                                    <h4 class="card-title text-warning">Cheques that are subject to realise</h4>
                                    <table class="table custom-table m-t-10">
                                        <tbody>
                                        @if(count(chequesSubjectToRealise($customer)))
                                            @foreach(chequesSubjectToRealise($customer) as $cheque)
                                                <tr>
                                                    <td>
                                                        <a target="_blank" href="#">
                                                            {{ $cheque->cheque_no }} ({{ $cheque->cheque_date }})
                                                        </a><br />
                                                        <small class="text-muted">{{ $cheque->cheque_type }} Cheque</small>
                                                        <small class="text-muted"> | {{ $cheque->bank->name }}</small>
                                                    </td>
                                                    <td class="text-right text-muted">{{ number_format($cheque->amount, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr><td colspan="2" class="text-muted"><small>No cheques to realise...</small></td></tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card border-info">
                                <div class="card-body">
                                    <h4 class="card-title text-info">Orders Outstanding Summary</h4>
                                    <hr>
                                    <div>
                                        <h3 class="card-title">
                                            <b> {{ number_format(cusOutstanding($customer)['ordered'], 2) }} </b></h3>
                                        <h6 class="card-subtitle">Total Sales
                                            <span class="pull-right">{{ number_format(getProgressValue(cusOutstanding($customer)['ordered'], cusOutstanding($customer)['paid']), 2) }}
                                                %</span>
                                        </h6>
                                    </div>
                                    <div class="m-t-5">
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                 style="width: {{ getProgressValue(cusOutstanding($customer)['ordered'], cusOutstanding($customer)['paid']) }}%; height:10px;"
                                                 aria-valuenow=""
                                                 aria-valuemin="0"
                                                 aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="card-divider">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <h4>{{ number_format(cusOutstanding($customer)['paid'], 2) }}</h4>
                                            <h6 class="text-muted text-info">Total Received</h6>
                                        </div>
                                        <div class="col-4">
                                            <h4>{{ number_format(cusOutstanding($customer)['balance'], 2) }}</h4>
                                            <h6 class="text-muted text-warning">Balance</h6>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-3">
                                            <h4>{{ number_format(cusOutstanding($customer)['paidAsCash'], 2) }}</h4>
                                            <h6 class="text-muted text-info">By Cash</h6>
                                        </div>
                                        <div class="col-3">
                                            <h4>{{ number_format(cusOutstanding($customer)['paidAsCheque'], 2) }}</h4>
                                            <h6 class="text-muted text-info">By Cheque</h6>
                                        </div>
                                        <div class="col-3">
                                            <h4>{{ number_format(cusOutstanding($customer)['paidAsDD'], 2) }}</h4>
                                            <h6 class="text-muted text-info">By Deposit</h6>
                                        </div>
                                        <div class="col-3">
                                            <h4>{{ number_format(cusOutstanding($customer)['paidAsCD'], 2) }}</h4>
                                            <h6 class="text-muted text-info">By Card</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-default">
                                <div class="card-body">
                                    <h4 class="card-title text-megna">Customer Sales Summary</h4>
                                    <hr>

                                    <h5><b>Van Sales</b></h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-4">
                                            <h4>{{ number_format(array_get(customerSalesSummary($customer, 'van'), 'sales', 0), 2) }}</h4>
                                            <h6 class="text-muted text-info">Sales</h6>
                                        </div>
                                        <div class="col-4">
                                            <h4>{{ number_format(array_get(customerSalesSummary($customer, 'van'), 'paid', 0), 2) }}</h4>
                                            <h6 class="text-muted text-success">Received</h6>
                                        </div>
                                        <div class="col-4">
                                            <h4>{{ number_format(array_get(customerSalesSummary($customer, 'van'), 'balance', 0), 2) }}</h4>
                                            <h6 class="text-muted text-warning">Balance</h6>
                                        </div>
                                    </div>

                                    <hr>
                                    <h5><b>Shop Sales</b></h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-4">
                                            <h4>{{ number_format(array_get(customerSalesSummary($customer, 'shop'), 'sales', 0), 2) }}</h4>
                                            <h6 class="text-muted text-info">Sales</h6>
                                        </div>
                                        <div class="col-4">
                                            <h4>{{ number_format(array_get(customerSalesSummary($customer, 'shop'), 'paid', 0), 2) }}</h4>
                                            <h6 class="text-muted text-success">Received</h6>
                                        </div>
                                        <div class="col-4">
                                            <h4>{{ number_format(array_get(customerSalesSummary($customer, 'shop'), 'balance', 0), 2) }}</h4>
                                            <h6 class="text-muted text-warning">Balance</h6>
                                        </div>
                                    </div>


                                    <hr>
                                    <h5><b>Office Sales</b></h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-4">
                                            <h4>{{ number_format(array_get(customerSalesSummary($customer, 'office'), 'sales', 0), 2) }}</h4>
                                            <h6 class="text-muted text-info">Sales</h6>
                                        </div>
                                        <div class="col-4">
                                            <h4>{{ number_format(array_get(customerSalesSummary($customer, 'office'), 'paid', 0), 2) }}</h4>
                                            <h6 class="text-muted text-success">Received</h6>
                                        </div>
                                        <div class="col-4">
                                            <h4>{{ number_format(array_get(customerSalesSummary($customer, 'office'), 'balance', 0), 2) }}</h4>
                                            <h6 class="text-muted text-warning">Balance</h6>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="card border-info">
                                <div class="card-body">
                                    <h4 class="card-title text-info">Customer Credit Summary</h4>
                                    <hr>
                                    <div>
                                        <h3 class="card-title">
                                            <b> {{ number_format(cusCreditOutstanding($customer)['credits'], 2) }} </b>
                                        </h3>
                                        <h6 class="card-subtitle">Total Credit
                                            <span class="pull-right">{{ number_format(getProgressValue(cusCreditOutstanding($customer)['credits'],
                                             (cusCreditOutstanding($customer)['refunded'] + cusCreditOutstanding($customer)['used']), true), 2) }}
                                                %</span>
                                        </h6>
                                    </div>
                                    <div class="m-t-5">
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                 style="width: {{ getProgressValue(cusCreditOutstanding($customer)['credits'], (cusCreditOutstanding($customer)['refunded'] + cusCreditOutstanding($customer)['used']), true) }}%; height:10px;"
                                                 aria-valuenow="25" aria-valuemin="100"
                                                 aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="card-divider">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <h4>{{ number_format(cusCreditOutstanding($customer)['refunded'], 2) }}</h4>
                                            <h6 class="text-muted text-info">Refunded</h6>
                                        </div>
                                        <div class="col-4">
                                            <h4>{{ number_format(cusCreditOutstanding($customer)['used'], 2) }}</h4>
                                            <h6 class="text-muted text-success">Used</h6>
                                        </div>
                                        <div class="col-4">
                                            <h4>{{ number_format(cusCreditOutstanding($customer)['balance'], 2) }}</h4>
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
                                    @include('general.comment.index', ['model' => $customer])
                                </div>
                            </div>

                            <!-- recent audit logs -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.log.index', ['model' => $customer, 'modelName' => 'Customer'])
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
    @include('layouts._inc.map-script')
    @include('_inc.contact-person.script')
    @include('general.comment.script', ['modelId' => $customer->id])
    @include('_inc.document.script', ['model' => $customer])
    @include('general.distance-calculator.index')
    @include('general.helpers')
    <script src="{{ asset('js/vendor/object-helper.js') }}"></script>
    <script>
        app.controller('CustomerController', ['$scope', '$http', function ($scope, $http) {
            $scope.orders = @json($customer->orders()->with(['invoices', 'payments' => function ($q){
             $q->where('status', 'Paid');
            }])->get());

            $scope.invoices = @json($customer->invoices()->with(['payments' => function ($q){
             $q->where('status', 'Paid');
            }])->get());

            $scope.payments = @json($customer->payments()->with(['preparedBy', 'depositedTo'])->get());

            $scope.estimates = @json($customer->estimates);

            $scope.credits = @json($customer->credits()->with(['refunds', 'payments'])->get());

            $scope.expenses = @json($customer->expenses()->with(['category'])->get());

            $scope.references = @json($customer->openingReferences);

            $scope.journals = @json($customer->journals);

            $scope.returns = @json($returns);

            $scope.visits = @json($visits);

            $scope.statusLabelColor = function ($status) {
                return statusLabelColor($status)
            };

            $scope.soOutstanding = function (order) {
                return soOutstanding(order);
            };

            $scope.invOutstanding = function (order) {
                return invOutstanding(order);
            };

            var route = '{{ route('sales.payment.print', ['invoice' => 'INVOICE', 'payment' => 'PAYMENT'])  }}';
            $scope.getRoute = function (payment) {
                return route.replace('INVOICE', payment.invoice.id).replace('PAYMENT', payment.id)
            };

            $scope.getCustomerCreditLimit = function (credit) {
                return getCustomerCreditLimit(credit);
            };

            $scope.getTotal = function (array, get) {
                return sum(_.pluck(array, get));
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
