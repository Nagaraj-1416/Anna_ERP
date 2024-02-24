@extends('layouts.master')
@section('title', 'Expense')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Expense') !!}
@endsection
@section('content')
    <section ng-controller="ExpenseController">
        <div class="row">
            @include('expense._inc.thinks-you-do')
            <div class="col-lg-6 col-md-6">
                @include('expense._inc.count-widgets')
                @include('expense._inc.comparison')
            </div>
            @include('expense._inc.by-type')
        </div>
    </section>
@endsection

@section('script')
    @parent
    @include('expense._inc.script')
@endsection
