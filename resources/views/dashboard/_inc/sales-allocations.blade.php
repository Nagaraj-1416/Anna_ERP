<!-- Sales Allocations -->
<div class="card bg-light-purple border-purple">
    <div class="card-body">
        <h3 class="card-title text-purple">Sales Allocations</h3>
        <div class="{{ count(allocations()) > 5 ? 'scrollable-widget' : '' }}">
            <table class="table m-t-10">
                <tbody>
                    @if(count(allocations()))
                        @foreach(allocations() as $allocation)
                        <tr>
                            <td>
                                <span class="">
                                    <img src="{{route('setting.staff.image', [$allocation->rep->staff_id ?? ''])}}" alt="user" width="50" class="img-circle">
                                </span>
                            </td>
                            <td>
                                <h5><a target="_blank" href="{{ route('sales.allocation.show', [$allocation]) }}">
                                        {{ $allocation->code }}
                                    </a></h5><small class="text-muted">
                                    {{ $allocation->from_date }} | {{ $allocation->salesLocation->name }}
                                    <br />
                                    {{ $allocation->rep ? $allocation->rep->name : '' }}
                                    @if(isset($allocation->salesHandover) && $allocation->salesHandover->status == 'Pending')
                                        <br /> <b class="text-danger">Handover confirmation is pending</b>
                                    @endif
                                </small>
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
                        <tr><td colspan="2" class="text-muted"><small>No allocations found...</small></td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

