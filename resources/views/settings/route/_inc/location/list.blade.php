<div class="card">
    <div class="card-body">
        <h3><b>LOCATIONS</b> <span class="pull-right">Total Locations: {{ count($locations) }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Notes</th>
                    <th style="width: 20%;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @if($locations)
                    @foreach($locations as $location)
                        <tr>
                            <td>
                                {{ $location->name }}
                            </td>
                            <td>
                                {{ $location->notes }}
                            </td>
                            <td>
                                <div class="button-group">
                                    <button data-id="{{$location->id}}"
                                            class="btn waves-effect waves-light btn-sm btn-primary location-edit-btn">
                                        Edit
                                    </button>
                                    <button data-id="{{$location->id}}"
                                            class="btn waves-effect waves-light btn-sm btn-danger location-delete-btn">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2">No Locations Found</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>