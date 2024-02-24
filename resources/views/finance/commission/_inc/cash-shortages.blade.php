@extends('layouts.master')
@section('title', 'Sales Commission - Cash Shortages')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4>Cash Shortages of {{ $rep->name }}</h4>
                    <br />
                    <div class="form-body">
                        <table class="ui celled structured table collapse-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Allocation Details</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cashShortages as $cashShortage)
                                <tr>
                                    <td>{{ $cashShortage->date->toDateString() }}</td>
                                    <td>
                                        <a target="_blank" href="{{ route('sales.allocation.show', $cashShortage->dailySale) }}">
                                            {{ $cashShortage->dailySale->code }}
                                        </a>
                                        <br />
                                        {{ $cashShortage->dailySale->route->name }}
                                    </td>
                                    <td class="text-right">{{ number_format($cashShortage->amount, 2) }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td class="text-right" colspan="2">
                                        <b>TOTAL</b>
                                    </td>
                                    <td class="text-right"><b>{{ number_format($cashShortagesTotal, 2) }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
