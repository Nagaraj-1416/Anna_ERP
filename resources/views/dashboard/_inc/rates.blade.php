<div class="row">
    <div class="col-md-6">
        <div class="card bg-light-success border-default">
            <div class="card-body">
                <h3 class="card-title text-megna">YOUR TOP CUSTOMERS</h3>
                <hr>
                <div class="row">
                    @if(array_get(array_get($topThreeCustomer, 1, []), 'id', ''))
                        <div class="col-md-4 m-t-40">
                            <img class="" style="display: block; margin-left: auto; margin-right: auto;"
                                 src="{{ asset('images/customers/Silver_Customer.png') }}" alt="">
                            <p class="text-center">
                                <a target="_blank"
                                   href="{{ route('sales.customer.show', [array_get(array_get($topThreeCustomer, 1, []), 'id', '')]) }}">
                                    {{ array_get(array_get($topThreeCustomer, 1, []), 'display_name', '') }}
                                </a>
                            </p>
                            <p class="text-center text-success sidebar-btn"
                               data-route="{{ route('dashboard.customer.stats', [array_get(array_get($topThreeCustomer, 1, []), 'id', '')]) }}">{{ number_format(array_get(array_get($topThreeCustomer, 1, []), 'total_amount', 0), 2) }}</p>
                        </div>
                    @endif
                    @if(array_get(array_get($topThreeCustomer, 0, []), 'id', ''))
                        <div class="col-md-4 ">
                            <img class="" style="display: block; margin-left: auto; margin-right: auto;"
                                 src="{{ asset('images/customers/Gold_Customer.png') }}" alt="">
                            <p class="text-center">
                                <a target="_blank"
                                   href="{{ route('sales.customer.show', [array_get(array_get($topThreeCustomer, 0, []), 'id', '')]) }}">
                                    {{ array_get(array_get($topThreeCustomer, 0, []), 'display_name', '') }}
                                </a>
                            </p>
                            <p class="text-center text-success sidebar-btn"
                               data-route="{{ route('dashboard.customer.stats', [array_get(array_get($topThreeCustomer, 0, []), 'id', '')]) }}">{{ number_format(array_get(array_get($topThreeCustomer, 0, []), 'total_amount', 0), 2) }}</p>
                        </div>
                    @endif
                    @if(array_get(array_get($topThreeCustomer, 2, []), 'id', ''))
                        <div class="col-md-4" style="margin-top: 60px;">
                            <img class="" style="display: block; margin-left: auto; margin-right: auto;"
                                 src="{{ asset('images/customers/Bronze_Customer.png') }}" alt="">
                            <p class="text-center">
                                <a target="_blank"
                                   href="{{ route('sales.customer.show', [array_get(array_get($topThreeCustomer, 2, []), 'id', '')]) }}">
                                    {{ array_get(array_get($topThreeCustomer, 2, []), 'display_name', '') }}
                                </a>
                            </p>
                            <p class="text-center text-success sidebar-btn"
                               data-route="{{ route('dashboard.customer.stats', [array_get(array_get($topThreeCustomer, 2, []), 'id', '')]) }}">{{ number_format(array_get(array_get($topThreeCustomer, 2, []), 'total_amount', 0), 2) }}</p>
                        </div>
                    @endif
                </div>
                <hr>
                <h3 class="card-title text-warning">TOP RUNNER UP'S</h3>
                <div class="m-t-10">
                    <table class="table custom-table m-t-10">
                        <tbody>
                        @if($customers->count())
                            @foreach($customers as $customer)
                                <tr>
                                    <td>
                                        <img src="{{ route('sales.customer.logo', [array_get($customer, 'id')]) }}"
                                             alt="user" width="50" class="img-circle">
                                    </td>
                                    <td style="vertical-align: center;">
                                        <a target="_blank"
                                           href="{{ route('sales.customer.show', [array_get($customer, 'id')]) }}">
                                            {{ array_get($customer, 'display_name', 'None') }}
                                        </a>
                                        <br/>
                                        <small class="text-muted">No of
                                            Orders: {{ array_get($customer, 'total_orders', 0) }}</small>
                                    </td>
                                    <td class="text-right text-success sidebar-btn"
                                        data-route="{{ route('dashboard.customer.stats', [array_get($customer, 'id')]) }}">{{ number_format(array_get($customer, 'total_amount', 0), 2) }}</td>
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


    <div class="col-md-6">
        <div class="card bg-light-success border-default">
            <div class="card-body">
                <h3 class="card-title text-megna">YOUR TOP REPS</h3>
                <hr>
                <div class="row">
                    @if(array_get(array_get($topThreeReps, 1, []), 'id', ''))
                        <div class="col-md-4 m-t-40">
                            <img class="" style="display: block; margin-left: auto; margin-right: auto;"
                                 src="{{ asset('images/reps/Silver_Rep.png') }}" alt="">
                            <p class="text-center">
                                <a target="_blank"
                                   href="{{ route('setting.rep.show', [array_get(array_get($topThreeReps, 1, []), 'id', '')]) }}">{{ array_get(array_get($topThreeReps, 1, []), 'name', '') }}</a>
                            </p>
                            <p class="text-center text-success sidebar-btn"
                               data-route="{{ route('dashboard.rep.stats', [array_get(array_get($topThreeReps, 1, []), 'id', '')]) }}">{{ number_format(array_get(array_get($topThreeReps, 1, []), 'total_amount', 0), 2) }}</p>
                        </div>
                    @endif
                    @if(array_get(array_get($topThreeReps, 0, []), 'id', ''))
                        <div class="col-md-4 ">
                            <img class="" style="display: block; margin-left: auto; margin-right: auto;"
                                 src="{{ asset('images/reps/Gold_Rep.png') }}" alt="">
                            <p class="text-center">
                                <a target="_blank"
                                   href="{{ route('setting.rep.show', [array_get(array_get($topThreeReps, 0, []), 'id', '')]) }}">
                                    {{ array_get(array_get($topThreeReps, 0, []), 'name', '') }}
                                </a>
                            </p>
                            <p class="text-center text-success sidebar-btn"
                               data-route="{{ route('dashboard.rep.stats', [array_get(array_get($topThreeReps, 0, []), 'id', '')]) }}">{{ number_format(array_get(array_get($topThreeReps, 0, []), 'total_amount', 0), 2) }}</p>
                        </div>
                    @endif
                    @if(array_get(array_get($topThreeReps, 2, []), 'id', ''))
                        <div class="col-md-4" style="margin-top: 60px;">
                            <img class="" style="display: block; margin-left: auto; margin-right: auto;"
                                 src="{{ asset('images/reps/Bronze_Rep.png') }}" alt="">
                            <p class="text-center">
                                <a target="_blank"
                                   href="{{ route('setting.rep.show', [array_get(array_get($topThreeReps, 2, []), 'id', '')]) }}">
                                    {{ array_get(array_get($topThreeReps, 2, []), 'name', '') }}
                                </a>
                            </p>
                            <p class="text-center text-success sidebar-btn"
                               data-route="{{ route('dashboard.rep.stats', [array_get(array_get($topThreeReps, 2, []), 'id', '')]) }}">{{ number_format(array_get(array_get($topThreeReps, 2, []), 'total_amount', 0), 2) }}</p>
                        </div>
                    @endif
                </div>
                <hr>
                <h3 class="card-title text-warning">TOP RUNNER UP'S</h3>
                <div class="m-t-10">
                    <table class="table custom-table m-t-10">
                        <tbody>
                        @if($reps->count())
                            @foreach($reps as $rep)
                                <tr>
                                    <td>
                                        <span class="">
                                            <img src="{{route('setting.staff.image', [array_get($rep, 'staff_id')])}}"
                                                 alt="user" width="50" class="img-circle">
                                        </span>
                                    </td>
                                    <td style="vertical-align: center">
                                        <a target="_blank"
                                           href="{{ route('setting.rep.show', [array_get($rep, 'id')]) }}">
                                            {{ array_get($rep, 'name', 'None') }}
                                        </a>
                                        <br/>
                                        <small class="text-muted">No of
                                            Orders: {{ array_get($rep, 'total_orders', 0) }}</small>
                                    </td>
                                    <td class="text-right text-success sidebar-btn"
                                        data-route="{{ route('dashboard.rep.stats', [array_get($rep, 'id')]) }}">{{ number_format(array_get($rep, 'total_amount', 0), 2) }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2" class="text-muted">
                                    <small>No reps found...</small>
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