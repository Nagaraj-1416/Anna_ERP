@extends('layouts.master')
@section('title', 'Data Administration')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Migrations') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-3">
            <!-- Things You Could Do -->
            <div class="card border-default">
                <div class="card-body">
                    <h3 class="card-title">Migrations</h3>
                    <hr>
                    <ul class="feeds">
                        <li>
                            <div class="bg-light-success">
                                <i class="ti-blackboard"></i>
                            </div> <a href="{{ route('setting.migrate.products') }}">Migrate Products</a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection