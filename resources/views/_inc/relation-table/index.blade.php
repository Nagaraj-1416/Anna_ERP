<div class="card">
    <div class="card-body">
        <div class="d-flex no-block">
            <h4 class="card-title">{{ $title }}</h4>
            <div class="ml-auto"></div>
        </div>
        <h6 class="card-subtitle">No of {{ $name }}: {{ count($modal->$relation) }}</h6>
        <div class="table-responsive">
            <table class="table stylish-table">
                <thead>
                <tr>
                    @foreach($columns as $key => $column)
                        @if(!is_array($column))
                            <th>{{ ucfirst($column) }}</th>
                        @else
                            @foreach($column as $index => $item)
                                <th>{{ ucfirst($item) }}</th>
                            @endforeach
                        @endif
                    @endforeach

                </tr>
                </thead>
                <tbody>
                @if(count($modal->$relation))
                    @foreach($modal->$relation as $data)
                        <tr>
                            @foreach($columns as $key => $column)
                                @if(!is_array($column))
                                    @if($key == 'is_active')
                                        <td>
                                            <span class="label {{ $data->is_active == 'Yes' ? 'label-success' : 'label-danger' }}">{{ $data->is_active }}</span>
                                        </td>
                                    @elseif(isset($relationMethod) && array_get($relationMethod, $key))
                                        @php(
                                            $relationColumn = array_get($relationMethod, $key)
                                        )
                                        <td>
                                            <h6>
                                                <a target="{{isset($showUrl) ? '_blank' : ''}}"
                                                   href="{{ isset($showUrl) ? route($showUrl, [$data->$key]) : '#'}}"
                                                   class="link">{{ $data->$key->$relationColumn ?? ''}}</a>
                                            </h6>
                                        </td>
                                    @elseif($key == 'action')
                                        <td>
                                            @if(isset($actions))
                                                @foreach($actions as $key => $action)
                                                    @if($key == 'edit')
                                                        <span class="btn waves-effect waves-light btn-sm btn-success edit-{{$name}}"
                                                              data-object="{{ json_encode($data->toArray()) }}"> {{$action}}</span>
                                                    @elseif($key == 'delete')
                                                        <span class="btn waves-effect waves-light btn-sm btn-danger delete-{{$name}}"
                                                              data-id="{{ $data->id }}"> {{$action}}</span>
                                                    @elseif($key == 'revoke')
                                                        <span class="btn waves-effect waves-light btn-sm btn-danger revoke-{{$name}} {{ $data->pivot->status != 'Assigned' ? 'hidden' : '' }}"
                                                              data-id="{{ $data->id }}"> {{$action}}</span>
                                                    @elseif($key == 'block')
                                                        <span class="btn waves-effect waves-light btn-sm btn-danger block-{{$name}} {{ $data->pivot->status != 'Assigned' ? 'hidden' : '' }}"
                                                                data-id="{{ $data->id }}"> {{$action}}</span>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>
                                    @elseif($key)
                                        <td>{{ $data->$key ?? 'N/A'}}</td>
                                    @else
                                        <td>
                                            <h6>
                                                <a target="{{isset($showUrl) ?'_blank' : '#'}}"
                                                   href="{{ isset($showUrl) ? route($showUrl, [$data]) : '#'}}"
                                                   class="link">{{ $data->name ?? 'N/A'}}</a>
                                            </h6>
                                            <small class="text-muted">Code : {{ $data->code ?? 'N/A'}} </small>
                                        </td>
                                    @endif
                                @else
                                    @foreach($column as $index => $item)
                                        <td>{{ $data->$key->$index ?? 'N/A'}}</td>
                                    @endforeach
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">
                            <span class="text-muted">No {{ $name }} Found.</span>
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@if(isset($formNames))
    @include('_inc.relation-table.edit', ['formNames' => $formNames, 'title' => 'Edit '. $name])
@endif
@section('script')
    @parent()
    @if(isset($formNames))
        <script>
            var name = 'edit-' + '{{ $name }}';
            var formData = [{!! json_encode($formNames) !!}][0];
            var updateRoute = '{{ $editRoute }}';
            $('.card-body').on('click', '.' + name, function () {
                $('#edit_data_modal').modal('show');
                var data = $(this).data('object');
                setData(formData, data);
                $('#edit_data_modal').find('#edited_location').val(data.id);
                $('#edit_data_modal').find('form').removeAttr('action');
                $('#edit_data_modal').find('form').attr('action', updateRoute.replace('CP', data.id));
            });
            $('#edit_data_modal').find('#cancelBtn').click(function () {
                $('#edit_data_modal').modal('hide');
            });

            function setData(formData, data) {
                $.each(formData, function (index, value) {
                    if (value === 'is_active') {
                        $.each($('#is_active').find('option'), function (key, val) {
                            if ($(val).val() === data[value]) {
                                $(val).attr('selected', true);
                            }
                        })
                    } else if (value === 'notes') {
                        $('#' + value).text(data[value]);
                    } else {
                        $('#' + value).attr('value', data[value]);
                    }
                });
            }

            @if ($errors->has('name') || $errors->has('is_active'))
            $('#edit_data_modal').modal('show');
            $('#edit_data_modal').find('form').removeAttr('action');
            $('#edit_data_modal').find('form').attr('action', updateRoute.replace('CP', [{!! json_encode(old()) !!}][0].id));
            @endif

        </script>
    @endif
    @if(isset($deleteRoute))
        <script>
            $('.card-body').on('click', '.delete-' + '{{$name}}', function () {
                var message = '{{ isset($deleteMessage) ? $deleteMessage : null }}';
                var id = $(this).data('id');
                var deleteUrl = '{{ $deleteRoute }}';
                deleteUrl = deleteUrl.replace('ID', id);
                var deleteMessage = message ? 'Detach' : 'Delete';
                swal({
                    title: 'Are you sure?',
                    text: message ? message : "You won't be able to revert this action!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DB2828',
                    confirmButtonText: 'Yes, ' + deleteMessage
                }).then(function (isConfirm) {
                    if (isConfirm.value) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            data: {'_token': '{{ csrf_token() }}'},
                            success: function (result) {
                                swal(
                                    'Deleted!',
                                    '{{$name}} deleted successfully!',
                                    'success'
                                );
                                setTimeout(location.reload(), 300);
                            }
                        });
                    }
                });
            });
        </script>
    @endif
    @if(isset($revokeRoute))
        <script>
            var Revokename = 'revoke-' + '{{ $name }}';
            var updateRouteRevoke = '{{ $revokeRoute }}';
            $('.card-body').on('click', '.' + Revokename, function () {
                $('#rb_modal').modal('show');
                var data = $(this).data('id');
                $('#revokeData').val(data);
                $('#rb_modal').find('form').removeAttr('action');
                $('#rb_modal').find('form').attr('action', updateRouteRevoke.replace('ID', data));
            });
            @if ($errors->has('date'))
            $('#rb_modal').modal('show');
            var data = '{{ old('data') }}';
            $('#revokeData').val(data);
            $('#rb_modal').find('form').removeAttr('action');
            $('#rb_modal').find('form').attr('action', updateRouteRevoke.replace('ID', data));
            @endif
        </script>
    @endif
    @if(isset($blockRoute))
        <script>
            var name = 'block-' + '{{ $name }}';
            var updateRoute = '{{ $blockRoute }}';
            $('.card-body').on('click', '.' + name, function () {
                $('#block_modal').modal('show');
                var data = $(this).data('id');
                $('#dataInput').val(data);
                $('#block_modal').find('.content').find('form').removeAttr('action');
                $('#block_modal').find('.content').find('form').attr('action', updateRoute.replace('ID', data));
            });
            @if ($errors->has('block_date'))
            $('#block_modal').modal('show');
            $('#block_modal').modal('refresh');
            var data = '{{ old('block_date') }}';
            var id = '{{ old('block_date_id') }}';
            $('#dataInput').val(id);
            $('#block_date').val(data);
            $('#block_modal').find('form').removeAttr('action');
            $('#block_modal').find('form').attr('action', updateRoute.replace('ID', id));
            @endif
        </script>
    @endif
    {{--for Cancel btn--}}
    <script>
        $('.cancelBtn').click(function () {
            $(this).parent().parent().parent().parent().modal('hide');
        })
    </script>
@endsection
