@extends('layouts.master')
@section('title', 'Price Books Comparison')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row" ng-controller="PriceBookController">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-b-0">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="card-title"><i class="ti-layout-column2"></i> Price Books Comparison</h3>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    <!-- from to filter -->
                    <form method="get" id="filter-form">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('company_id') ? 'has-danger' : '' }}">
                                    <label class="control-label">Company</label>
                                    <div class="ui fluid  search selection dropdown company-drop-down {{ $errors->has('company_id') ? 'error' : '' }}">
                                        <input type="hidden" name="company_id">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose a company</div>
                                        <div class="menu">
                                            <div class="item" data-value="All">All</div>
                                            @foreach(companyDropDown() as $key => $company)
                                                <div class="item" data-value="{{ $key }}">{{ $company }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="form-control-feedback">{{ $errors->first('company_id') }}</p>
                                </div>
                            </div>
                            {{--<div class="col-md-4">
                                <div class="form-group {{ $errors->has('rep_id') ? 'has-danger' : '' }}">
                                    <label class="control-label">Rep</label>
                                    <div class="ui fluid  search selection dropdown rep-drop-down {{ $errors->has('rep_id') ? 'error' : '' }}">
                                        <input type="hidden" name="rep_id">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose a rep</div>
                                        <div class="menu">
                                            @foreach(repDropDown() as $key => $rep)
                                                <div class="item" data-value="{{ $key }}">{{ $rep }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="form-control-feedback">{{ $errors->first('rep_id') }}</p>
                                </div>
                            </div>--}}
                        </div>

                        <div class="clearfix m-t-10">
                            <div class="pull-left">
                                <button class="btn btn-info btn-submit" type="submit"><span class="mdi mdi-filter"></span> Filter</button>
                                <a href="{{ route('setting.price.book.comparison') }}?company_id=All"
                                   class="btn btn-primary">
                                    <i class="fa fa-columns"></i> Reset
                                </a>
                            </div>
                            <div class="pull-right"></div>
                        </div>

                    </form>

                    <div style="overflow-x: auto; width: 100%;" class="m-t-10">
                        <table class="table table-bordered">
                            <tr>
                                <td rowspan="2" style="vertical-align: middle; font-size: 18px; background: #fbf0dd;" class="text-center"><b>Products</b></td>
                                <td style="font-size: 18px; padding: 20px !important; background-color: #e7e4f9;" colspan="{{ count(getPriceBooksLabel($companyId)) }}" class="text-center"><b>Van Price Details</b></td>
                            </tr>
                            <tr>
                                @foreach(getPriceBooksLabel($companyId) as $labelKey => $label)
                                <td style="font-size: 14px; padding: 20px !important; background-color: rgba(207, 236, 254, 0.58)" class="text-center text-info">
                                    {{ $label }}<br />
                                    <a target="_blank" href="/setting/price-book/{{ $labelKey }}/edit" class="btn btn-info btn-sm m-t-10">
                                        <small><i class="ti-pencil"></i> Edit</small>
                                    </a>
                                </td>
                                @endforeach
                            </tr>
                            @foreach(getFinishedGoods() as $proKey => $product)
                            <tr>
                                <td scope="row" style="font-size: 14px; background: #e8fdeb; vertical-align: middle;">
                                    <span class="text-megna">{{ $product->name }}</span><br />
                                    <small class="">{{ $product->tamil_name }}</small><br />
                                    <a class="text-info" target="_blank" href="/setting/product/{{ $product->id }}"><small>View Details</small></a>
                                </td>
                                @foreach(getPriceBooksLabel($companyId) as $key => $label)
                                <td>
                                    @foreach(getProductPrices($key, $product->id) as $price)
                                        <ul style="list-style: none;">
                                            <li>
                                                <span style="font-size: 18px;">Rs {{ number_format($price->price, 2) }}</span><br />
                                                <span class="text-warning"> {{ $price->range_start_from.' -> '.$price->range_end_to }}</span>
                                            </li>
                                        </ul>
                                    @endforeach
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        app.controller('PriceBookController', ['$scope', '$http', function ($scope, $http) {
            $scope.query = {
                company: '',
                rep: ''
            };

            $scope.dropdowns = {
                company: $('.company-drop-down'),
                rep: $('.rep-drop-down')
            };

            $scope.dropdowns.company.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val, name) {
                    $scope.query.company = val;
                    $scope.dropdowns.rep.dropdown('clear');
                    repDropDown(val);
                }
            });

            function repDropDown(company) {
                var url = '{{ route('setting.rep.by.company.search', ['repId']) }}';
                url = url.replace('repId', company);
                $scope.dropdowns.rep.dropdown('setting', {
                    forceSelection: false,
                    apiSettings: {
                        url: url + '/{query}',
                        cache:false,
                    },
                    saveRemoteData:false,
                    onChange: function(val, name){
                        $scope.query.rep = val;
                    }
                });
            }

            $('.btn-submit').click(function () {
                $('#filter-form').submit();
            });



        }]);
    </script>
@endsection