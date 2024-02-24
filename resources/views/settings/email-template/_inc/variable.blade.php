@if($template->variables && count($template->variables))
    <div class="card card-outline-white">
        <div class="card-header">
            <h4 class="card-title">Variables</h4>
        </div>
        <div class="card-body">
            <table class="table">
                @foreach($template->variables as $id => $variable)
                    <tr>
                        <td width="5%">
                            <i class="fa fa-code"></i>
                        </td>
                        <td class="content">
                            {{ $variable['name'] }}
                        </td>
                        <td class="pull-right content">
                            <button
                                    class="btn btn-sm btn-info add-legend"
                                    type="button"
                                    data-type="variable"
                                    data-id="{{ $id }}"
                                    data-name="{{ $variable['name'] }}">
                                <i class="fa fa-plus"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endif