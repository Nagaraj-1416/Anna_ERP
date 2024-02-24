<div class="row m-t-10">
    <div class="col-md-12">
        {!! form()->bsTextarea('refund_notes_'.$varName, 'Reason to refund this ' . $varName, null, ['placeholder' => 'please mention, why do you want to refund this '.$varName , 'rows' => '4'], false) !!}
    </div>
    <input type="hidden" name="{{ 'refund_' . $varName }}" value="true">
</div>

