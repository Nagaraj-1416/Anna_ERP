@extends('layouts.master')
@section('title', 'Sales Commission')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
    <div class="row" ng-controller="CommissionController">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-b-0">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="card-title"><i class="ti-money"></i> Sales Commission Grid</h3>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="btn-group">
                            <button type="button" ng-click="fnPreYear()" class="btn btn-warning">
                                <span class="mdi mdi-chevron-left"></span>
                            </button>
                            <span class="btn btn-info">{{ $year - 1 }}</span>
                        </div>
                        <div class="btn-group">
                            <span class="btn btn-success"> {{ $year }} </span>
                        </div>
                        <div class="btn-group">
                            <span class="btn btn-primary">{{ $year + 1 }}</span>
                            <button type="button" ng-click="fnNextYear()" class="btn btn-warning">
                                <span class="mdi mdi-chevron-right"></span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <td rowspan="2" style="vertical-align: middle; font-size: 18px; background: #fbf0dd; width: 20%;" class="text-center"><b>REPS</b></td>
                            <td style="font-size: 18px; padding: 20px !important; background-color: #e7e4f9;" colspan="{{ count(shortMonthsDropDown()) }}" class="text-center"><b>Months and Commission Details</b></td>
                        </tr>
                        <tr>
                            @foreach(shortMonthsDropDown() as $labelKey => $label)
                                <td style="font-size: 14px; padding: 20px !important; background-color: rgba(207, 236, 254, 0.58)" class="text-center text-danger">
                                    <b>{{ $label }}</b>
                                </td>
                            @endforeach
                        </tr>
                        @foreach(repDropDown() as $repKey => $repValue)
                            <tr>
                                <td scope="row" style="font-size: 14px; background: rgba(207, 236, 254, 0.58); vertical-align: middle;">
                                    <span class="text-primary">{{ $repValue }}</span>
                                </td>
                                @foreach(shortMonthsDropDown() as $monthKey => $monthValue)
                                    <td class="text-center">
                                        @if(!checkAvailableCommission($repKey, $year, $monthKey))
                                            <a target="_blank" href="{{ route('finance.commission.create', [$repKey, $year, $monthKey]) }}" class="btn-sm btn-info btn">
                                                Draft
                                            </a>
                                        @else
                                            <a target="_blank" href="{{ route('finance.commission.show', commissionData($repKey, $year, $monthKey)) }}" class="btn-sm btn-success btn">
                                                View
                                            </a>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection
@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script>
        app.controller('CommissionController', ['$scope', '$http', function ($scope, $http) {

            $scope.year = '{{ $year }}';
            $scope.nextYear = '{{ $nextYear }}';
            $scope.preYear = '{{ $preYear }}';

            $scope.fnPreYear = function () {
                var preYear = $scope.preYear;
                window.location.replace('{{ url('/') }}/finance/commission/year/' + preYear);
            };

            $scope.fnNextYear = function () {
                var nextYear = $scope.nextYear;
                window.location.replace('{{ url('/') }}/finance/commission/year/' + nextYear);
            };
        }]);
    </script>
@endsection