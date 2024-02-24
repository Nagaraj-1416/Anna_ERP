@extends('layouts.master')
@section('title', 'Log Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Log Details</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                        $old = array_get($data, 'old');
                        $attributes = array_get($data, 'attributes');
                        ?>
                        @if($old)
                            <div class="col-md-4">
                                <h6><b>Old Values</b></h6>
                                <table class="ui celled structured table">
                                    <tbody>
                                    @foreach(array_get($data, 'fields') as $field)

                                        <tr>
                                            <td><b>{{ ucfirst($field) }}</b></td>
                                            <td class="text-right {{ ($old && array_get($attributes, $field) != array_get($old, $field)) ? 'td-bg-warning' : ''}}">
                                                {{ array_get($old, $field) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        <div class="col-md-4">
                            <h6><b>New Values</b></h6>
                            <table class="ui celled structured table">
                                <tbody>
                                @foreach(array_get($data, 'fields') as $field)
                                    <tr>
                                        <td><b>{{ ucfirst($field) }}</b></td>
                                        <td class="text-right {{ ($old && array_get($attributes, $field) != array_get($old, $field)) ? 'td-bg-success' : ''}}">
                                            {{ ( !is_array(array_get($attributes, $field)) ? array_get($attributes, $field) : null) ?? 'None' }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <h6><b>Other Details</b></h6>
                            <table class="ui celled structured table">
                                <tbody>
                                <tr>
                                    <td><b>Causer</b></td>
                                    <td class="text-right">
                                        <a target="_blank"
                                           href="{{ route('setting.user.show', [$log->causer]) }}">{{ $log->causer->name ?? 'None' }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>IP</b></td>
                                    <td class="text-right">
                                        {{ $log->ip }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Created at</b></td>
                                    <td class="text-right">
                                        {{ carbon($log->created_at)->toDateString() }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Updated at</b></td>
                                    <td class="text-right">
                                        {{ carbon($log->updated_at)->toDateString() }}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection

@section('script')

@endsection