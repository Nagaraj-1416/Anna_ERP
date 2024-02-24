@extends('layouts.master')
@section('title', 'Data Administration')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Migrations') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-4">
            <!-- Things You Could Do -->
            <div class="card border-default">
                <div class="card-body">
                    <h5 class="card-title">Choose products sheet (Excel) to migrate</h5>
                    <hr>
                    {{ form()->open([ 'route' => 'setting.do.migrate.products', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <div class="form-group required {{ ($errors->has('source_file')) ? 'has-danger' : '' }}">
                            <input type="file" class="form-control" id="source_file" name="source_file">
                            <p class="form-control-feedback">{{ ($errors->has('source_file') ? $errors->first('source_file') : '') }}</p>
                        </div>
                        <hr>
                        {!! form()->bsSubmit('Submit') !!}
                        {!! form()->bsCancel('Cancel', 'setting.migrate.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection