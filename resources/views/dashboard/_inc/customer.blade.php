<div class="row">
    <div class="col-md-6">
        <!-- net income -->
        <div class="card border-default">
            <div class="card-body">
                <h3 class="card-title">YOUR TOP CUSTOMERS</h3>
                <hr>
                <div class="row">
                    <div class="col-md-4 m-t-40">
                        <img class="" style="display: block; margin-left: auto; margin-right: auto;"
                             src="{{ asset('images/customers/Silver_Customer.png') }}" alt="">
                        <p class="text-center">
                            <a target="_blank"
                               href="{{ route('sales.customer.show', [array_get(array_get($topThreeCustomer, 1, []), 'id', '')]) }}">{{ array_get(array_get($topThreeCustomer, 1, []), 'display_name', '') }}</a>
                        </p>
                        <p class="text-center">{{ number_format(array_get(array_get($topThreeCustomer, 1, []), 'total_amount', 0), 2) }}</p>
                    </div>
                    <div class="col-md-4 ">
                        <img class="" style="display: block; margin-left: auto; margin-right: auto;"
                             src="{{ asset('images/customers/Gold_Customer.png') }}" alt="">
                        <p class="text-center">
                            <a target="_blank"
                               href="{{ route('sales.customer.show', [array_get(array_get($topThreeCustomer, 0, []), 'id', '')]) }}">
                                {{ array_get(array_get($topThreeCustomer, 0, []), 'display_name', '') }}
                            </a>
                        </p>
                        <p class="text-center">{{ number_format(array_get(array_get($topThreeCustomer, 0, []), 'total_amount', 0), 2) }}</p>
                    </div>
                    <div class="col-md-4" style="margin-top: 60px;">
                        <img class="" style="display: block; margin-left: auto; margin-right: auto;"
                             src="{{ asset('images/customers/Bronze_Customer.png') }}" alt="">
                        <p class="text-center">
                            <a target="_blank"
                               href="{{ route('sales.customer.show', [array_get(array_get($topThreeCustomer, 2, []), 'id', '')]) }}">
                                {{ array_get(array_get($topThreeCustomer, 2, []), 'display_name', '') }}
                            </a>
                        </p>
                        <p class="text-center">{{ number_format(array_get(array_get($topThreeCustomer, 2, []), 'total_amount', 0), 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <!-- net income -->
        <div class="card border-warning">
            <div class="card-body">
                <h3 class="card-title">TOP RUNNER UP'S</h3>
                {{--<hr>--}}
                <div class="m-t-10">
                    {{--<table class="ui table celled stripped">
                        --}}{{--<thead>
                        <tr>
                            <th style="width: 60%">Customer name</th>
                            <th class="text-right">Amount</th>
                        </tr>
                        </thead>--}}{{--
                        <tbody>
                        @if($customers->count())
                            @foreach($customers as $customer)
                                <tr>
                                    <td style="width: 60%">
                                        <a target="_blank"
                                           href="{{ route('sales.customer.show', [array_get($customer, 'id')]) }}">
                                            {{ array_get($customer, 'display_name', 'None') }}
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        {{ number_format(array_get($customer, 'total_amount', 0), 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2" class="text-muted">
                                    <small>No customers found...</small>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>--}}

                    <table class="table custom-table m-t-10">
                        <tbody>
                        @if($customers->count())
                            @foreach($customers as $customer)
                            <tr>
                                <td>
                                    <span class="">
                                        <img src="{{route('sales.customer.logo', $customer)}}" alt="user" width="50">
                                    </span>
                                </td>
                                <td style="vertical-align: center">
                                    <a target="_blank"
                                       href="{{ route('sales.customer.show', [array_get($customer, 'id')]) }}">
                                        {{ array_get($customer, 'display_name', 'None') }}
                                    </a>
                                </td>
                                <td class="text-right">{{ number_format(array_get($customer, 'total_amount', 0), 2) }}</td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2" class="text-muted">
                                    <small>No customers found...</small>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>