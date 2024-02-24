<div class="row m-t-10">
    <div class="col-md-12">
        {!! form()->bsTextarea('cancel_notes_'.$varName, 'Reason to cancel this ' . $varName, null, ['placeholder' => 'please mention, why do you want to cancel this '.$varName , 'rows' => '4'], false) !!}
    </div>
    <input type="hidden" name="{{ $varName }}" value="true">
</div>

