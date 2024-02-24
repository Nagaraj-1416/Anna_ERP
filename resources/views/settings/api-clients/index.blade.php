@extends('layouts.master')
@section('title', 'API Clients')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'API Clients') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Client ID</th>
                                <th>Client Name</th>
                                <th>Client Secret</th>
                                <th>Redirect</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($oAuthClients as $oAuthClient)
                            <tr>
                                <td>{{ $oAuthClient->id }}</td>
                                <td>{{ $oAuthClient->name }}</td>
                                <td>{{ $oAuthClient->secret }}</td>
                                <td>{{ $oAuthClient->redirect }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
@endsection

@section('script')

@endsection