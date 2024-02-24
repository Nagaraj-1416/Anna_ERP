<!-- Sales Allocations -->
<div class="card bg-light-warning border-warning">
    <div class="card-body">
        <h3 class="card-title text-warning">Today's Allocations</h3>
        <div class="{{ count(todayAllocations()) > 5 ? 'scrollable-widget' : '' }}">
            <table class="table m-t-10">
                <tbody>
                    @if(count(todayAllocations()))
                        @foreach(todayAllocations() as $allocation)
                        <tr>
                            <td>
                                <span class="">
                                    <img src="{{route('setting.staff.image', [$allocation->rep->staff_id ?? ''])}}" alt="user" width="50" class="img-circle">
                                </span>
                            </td>
                            <td>
                                <h5>
                                    <a target="_blank" href="{{ route('sales.allocation.show', [$allocation]) }}">
                                        {{ $allocation->rep ? $allocation->rep->name : '' }}
                                    </a>
                                </h5>
                                <small class="text-muted">
                                    {{ $allocation->from_date }}
                                    <br />
                                    {{ $allocation->salesLocation->name }}
                                    <br />
                                    {{ $allocation->route ? $allocation->route->name : '' }}
                                </small>
                                {{--<button class="btn waves-effect waves-light btn-info btn-sm products-sidebar-btn"
                                        data-value="{{ $allocation }}">
                                    <i class="fa fa-plus"></i> Add New Products
                                </button>--}}
                            </td>
                            <td>
                                <small></small>
                            </td>
                            <td>
                                <span class="{{ statusLabelColor($allocation->status) }}">
                                    {{ $allocation->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="2" class="text-muted">
                                <span>No allocations found for today...</span>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

