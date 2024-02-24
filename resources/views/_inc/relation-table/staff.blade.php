<div class="card">
    <div class="card-body">
        <h3><b>STAFF</b> <span
                    class="pull-right">Total Staff: {{ $model->staff()->count() }}</span>
        </h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Code</th>
                    <th>Full name</th>
                    <th>Designation</th>
                    <th>phone</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Is active?</th>
                </tr>
                </thead>
                <tbody>
                @if(count($model->staff()->get()))
                    @foreach($model->staff()->get() as $staff)
                        <tr>
                            <td>
                                <a target="_blank"
                                   href="{{ route('setting.staff.show', [$staff]) }}">{{ $staff->code ?? 'None'}}</a>
                            </td>
                            <td>{{ $staff->full_name ?? 'None' }}</td>
                            <td>{{ $staff->designation->name ?? 'None'}}</td>
                            <td>{{ $staff->phone ?? 'None' }}</td>
                            <td>{{ $staff->email ?? 'None' }}</td>
                            <td>{{ $staff->gender ?? 'None' }}</td>
                            <td>{{ $staff->is_active ?? 'None' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No Staff Associated...</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>