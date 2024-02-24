<div class="card">
    <div class="card-body">
        <div class="d-flex no-block">
            <h4 class="card-title">Permissions</h4>
            <div class="ml-auto"></div>
        </div>
        @if(!isset($role) || (isset($role) && $role->access_level != 500))
            <div class="card-body">
                @php
                    $disable = !isset($disable) ? false : $disable;
                @endphp
                @if(!$disable)
                    <div>
                        <input id="checkAll" type="checkbox" class="filled-in chk-col-light-green">
                        <label for="checkAll">Check All (All Modules)</label>
                    </div>
                @endif
                <ul class="nav nav-tabs customtab2" role="tablist">
                    @foreach($polices as $module => $policyGroup)
                        <li class="nav-item">
                            <a class="nav-link {{ ($loop->first) ? 'active show' : ''}}" data-toggle="tab"
                               href="#{{ $module }}_tab" role="tab" aria-selected="true">
                                <span>
                                    {{ $policyGroup['group_name'] or 'Others' }}
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content p-10">
                    @foreach($polices as $module => $policyGroup)
                        <div class="tab-pane  {{ ($loop->first) ? 'active show' : ''}}" id="{{ $module }}_tab" role="tabpanel">
                            @if(!$disable)
                                <div class="module-check-btn">
                                    <input id="{{$module}}_check_btn" type="checkbox" class="filled-in chk-col-light-blue module_check" data-module="{{ $module }}">
                                    <label for="{{$module}}_check_btn">Check All ( {{ $policyGroup['group_name'] or 'Others' }})</label>
                                </div>
                            @endif
                            @php
                                $allMethods = getAllPolicyMethods($policyGroup)
                            @endphp
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Module</th>
                                        @foreach($allMethods as $method)
                                            <th  class="text-center">
                                                {{ ucfirst($method) }}
                                                <span class=" text-megna" data-toggle="tooltip" data-placement="top" title="{{ permissionTips($method) }}">
                                                     <i class="mdi mdi-information"></i>
                                                </span>
                                            </th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($policyGroup['policies'] ?? [] as $policy)
                                        <tr>
                                            <td>
                                                {{ $policy['model_name'] ?? '' }}
                                                {!!
                                                    !permissionSubtitle($policy['model_name']) ?
                                                        '':
                                                        '<br/> <small>('. permissionSubtitle($policy['model_name']) . ')</small>'
                                                 !!}
                                                <br/>
                                            </td>
                                            @foreach($allMethods as $method)
                                                <td  class="text-center">
                                                    @php
                                                        $model = $policy['model'];
                                                        $policyMethods = app($policy['policy'])->policies;
                                                    @endphp
                                                    @if(in_array($method, $policyMethods))
                                                        <div class="checkbox permission">
                                                            <input {{ $disable ? 'disabled' : '' }} name="permission[{{ $model }}][{{ $method }}]" type="checkbox" id="{{ snake_case(class_basename($model)) }}_{{ snake_case($method) }}_check"
                                                                   class="filled-in chk-col-blue-blue permission-check {{ $module }}_check_box"
                                                                    {{ old('_token') ? (old('permission') && isset(old('permission')[$model]) && isset(old('permission')[$model][$method]) ? 'checked' : '') :
                                                                    ( isset($role->permission[$model]) && is_array($role->permission[$model]) && in_array($method, $role->permission[$model]) ? 'checked' : '')}}>
                                                            <label for="{{ snake_case(class_basename($model)) }}_{{ snake_case($method) }}_check"></label>
                                                        </div>
                                                    @else
                                                        <b>-</b>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="card-body">
                <span class="text-muted">This user role has the highest Permissions.</span>
            </div>
        @endif
    </div>
</div>
@section('script')
    @parent
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
        $('#checkAll').change(function (e) {
            e.preventDefault();
            var isChecked = $(this).is(':checked');
            $('input.permission-check').prop('checked', isChecked);
            $('.module_check').prop('checked', isChecked)
        });

        $('.module_check').change(function (e) {
            e.preventDefault();
            var module = $(this).data('module');
            var isChecked = $(this).is(':checked');
            $('.' + module + '_check_box').prop('checked', isChecked)
        });
    </script>
@endsection