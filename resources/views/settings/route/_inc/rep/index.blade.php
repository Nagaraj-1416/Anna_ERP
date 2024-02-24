<div class="card">
    <div class="card-body">
        <h3><b>REPS</b> <span class="pull-right">Total Reps: {{ count($reps) }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Mobile</th>
                    <th>Vehicle</th>
                </tr>
                </thead>
                <tbody>
                @if($reps)
                    @foreach($reps as $rep)
                        <tr>
                            <td>
                                <a target="_blank" href="{{ route('setting.staff.show', [$rep->staff]) }}">
                                    {{ $rep->code }}
                                </a>
                            </td>
                            <td>{{ $rep->name }}</td>
                            <td>{{ $rep->staff->phone or 'None' }}</td>
                            <td>{{ $rep->staff->mobile or 'None' }}</td>
                            <td>{{ $rep->vehicle->vehicle_no or 'None' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2">No Reps Found...</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>