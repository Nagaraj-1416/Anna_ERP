@extends('layouts.master')
@section('title', 'Price Book Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Price Book Details') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">{{ $priceBook->code }}</h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                <a href="{{ route('setting.price.book.edit', [$priceBook]) }}"
                                   class="btn waves-effect waves-light btn-primary btn-sm" target="_blank">
                                    <i class="fa fa-pencil"></i> Edit
                                </a>
                                <a href="{{ route('setting.price.book.clone', [$priceBook]) }}"
                                   class="btn waves-effect waves-light btn-inverse btn-sm" target="_blank">
                                    <i class="fa fa-copy"></i> Clone
                                </a>
                                <a href="{{ route('setting.price.book.export', [$priceBook]) }}"
                                   class="btn waves-effect waves-light btn-pdf btn-sm">
                                    <i class="fa fa-file-pdf-o"></i> PDF
                                </a>
                                <a href="{{ route('setting.price.book.export', ['priceBook' => $priceBook, 'type' => 'excel']) }}"
                                   class="btn waves-effect waves-light btn-excel btn-sm">
                                    <i class="fa fa-file-pdf-o"></i> Excel
                                </a>
                            </div>
                            <div class="pull-right"></div>
                        </div>
                    </div>
                    <div class="row custom-top-margin">
                        <div class="col-md-9">
                            <div class="card card-body">
                                <h3>
                                    <b>{{ $priceBook->name }}</b>
                                    <span class="pull-right text-muted">
                                        @if($priceBook->is_active == 'Yes')
                                            {{ 'Active' }}
                                        @else
                                            {{ 'Inactive' }}
                                        @endif
                                    </span>
                                </h3>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Company</strong>
                                                <br>
                                                <p class="text-muted">
                                                    <a target="_blank"
                                                       href="{{ route('setting.company.show', ['company' => $priceBook->company]) }}">
                                                        {{ $priceBook->company->name }}
                                                    </a>
                                                </p>
                                            </div>
                                            {{--<div class="col-md-3 col-xs-6 b-r"><strong>Type</strong>
                                                <br>
                                                <p class="text-muted">{{ $priceBook->type or 'None' }}</p>
                                            </div>--}}
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Category</strong>
                                                <br>
                                                <p class="text-muted">{{ $priceBook->category or 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 b-r"><strong>Related To</strong>
                                                <br>
                                                <p class="text-muted">{{ $priceBook->relatedTo->name or 'None' }}</p>
                                            </div>
                                        </div>
                                        <h5 class="box-title box-title-with-margin">Notes</h5>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12 col-xs-6">
                                                <p class="text-muted">{{ $priceBook->notes or 'None' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h3><b>PRICES</b> <span
                                                class="pull-right">Total Prices: {{ $priceBook->prices()->count() }}</span>
                                    </h3>
                                    <hr>
                                    <div class="table-responsive">
                                        <table class="table color-table muted-table">
                                            <thead>
                                            <tr>
                                                <th class="text-left">Products</th>
                                                <th style="width:20%">Quantity range</th>
                                                <th class="text-right" style="width:10%">Prices</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($prices as $price)
                                                <tr>
                                                    <td>{{ $price->product->name ?? 'None' }}</td>
                                                    <td>{{ $price->range_start_from ?? 'None'}} - {{ $price->range_end_to ?? 'None'}}</td>
                                                    <td class="text-right">{{ $price->price ? number_format($price->price, 2) : 'None'}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">

                            <!-- recent comments -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.comment.index', ['model' => $priceBook])
                                </div>
                            </div>

                            <!-- histories -->
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Histories</h4>
                                    <hr>
                                    <div class="message-box" id="logScroll">
                                        <div class="message-widget">
                                            @if(count($histories))
                                                @foreach($histories as $history)
                                                    <a target="_blank" href="{{ route('setting.price.history.show', $history) }}">
                                                        <div class="user-img">
                                                            <img src="{{route('setting.staff.image', [$history->causer ? $history->causer->staffs()->first() : ''])}}" alt="user" class="img-circle">
                                                        </div>
                                                        <div class="mail-contnet">
                                                            <h5>Price book updated</h5>
                                                            <span class="mail-desc">by {{ $history->updatedBy->name or 'System' }}</span>
                                                            <span class="time text-muted">on {{ carbon($history->date)->format('d F Y, h:i:s A') }}</span>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            @else
                                                <p>No histories found.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- recent audit logs -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.log.index', ['model' => $priceBook, 'modelName' => 'Price Book'])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('general.comment.script', ['modelId' => $priceBook->id])
@endsection