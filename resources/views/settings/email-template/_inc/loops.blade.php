@if($template->loops && count($template->loops))
    <div class="card card-outline-white">
        <div class="card-header">
            <h4 class="card-title">Loops</h4>
        </div>
        <div class="card-body">
            <table class="table">
                @foreach($template->loops as $id => $templateLoop)
                    <tr>
                        <td width="5%">
                            <i class="fa fa-undo"></i>
                        </td>
                        <td class="content">
                            {{ $templateLoop['name'] }}
                        </td>
                        <td class="pull-right content">
                            <button
                                    class="btn btn-sm btn-info add-legend"
                                    type="button"
                                    data-type="loop"
                                    data-id="{{ $id }}"
                                    data-name="{{ $templateLoop['name'] }}">
                                <i class="fa fa-plus"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            @foreach($templateLoop['fields'] as $fieldName => $field)
                                <div class="field">
                                    <div class="ui slider {{ isset($field['enabled']) && $field['enabled']
                                                             ? 'checked' : null }} checkbox loop-checkbox"
                                         data-loop-id="{{ $id }}"
                                         data-field-name="{{ $fieldName }}">
                                        <input title=""
                                               type="checkbox"
                                               tabindex="0" class="hidden"
                                               {{ (isset($field['enabled']) && $field['enabled'] == 'true')
                                               ? 'checked="true"' : null }}
                                               value="true">
                                        <label>
                                            {{ $field['heading'] }}
                                        </label>
                                    </div>
                                    <label></label>
                                </div>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endif