<div class="form-body">
    <div class="row">
        <div class="col-md-4">
            {!! form()->bsText('name', 'Name', null, ['placeholder' => 'enter role name']) !!}
        </div>
        <div class="col-md-4">
            {!! form()->bsText('access_level', 'Access level', null, ['placeholder' => 'enter role access level']) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {!! form()->bsTextarea('description', 'Description', null, ['placeholder' => 'enter role related description here...', 'rows' => '5']) !!}
        </div>
    </div>
</div>