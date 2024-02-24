@if($template->links && count($template->links))
    <div class="card card-outline-white">
        <div class="card-header">
            <h4 class="card-title">Links</h4>
        </div>
        <div class="card-body">
            <table class="table">
                @foreach($template->links as $id => $link)
                    <tr>
                        <td width="5%">
                            <i class="fa fa-link"></i>
                        </td>
                        <td class="content">
                            {{ $link['name'] }}
                        </td>
                        <td class="pull-right content">
                            <button
                                    class="btn btn-sm btn-info add-legend"
                                    type="button"
                                    data-type="link"
                                    data-id="{{ $id }}"
                                    data-name="{{ $link['name'] }}">
                                <i class="fa fa-plus"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endif