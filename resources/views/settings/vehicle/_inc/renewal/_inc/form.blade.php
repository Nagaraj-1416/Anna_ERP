<div class="row">
    <div class="col-md-3">
        <div class="form-group required">
            <label class="control-label">Renewal type</label>
            <div class="demo-radio-button">
                <input name="type" value="Insurance" type="radio" class="with-gap Insurance type"
                       id="Insurance"
                       checked="" {{ (old('type') == 'Insurance') ? 'checked' : ''}}>
                <label for="Insurance">Insurance</label>
                <input name="type" value="Emission" type="radio" class="with-gap Emission type"
                       id="Emission" {{ (old('type') == 'Emission') ? 'checked' : ''}}>
                <label for="Emission">Emission</label>
                <input name="type" value="Fitness" type="radio"
                       class="with-gap fitness type"
                       id="Fitness" {{ (old('type') == 'Fitness') ? 'checked' : ''}}>
                <label for="Fitness">Fitness</label>
                <input name="type" value="Tax" type="radio" class="with-gap Tax type"
                       id="Tax" {{ (old('type') == 'Tax') ? 'checked' : ''}}>
                <label for="Tax">Tax</label>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        {!! form()->bsText('date', 'Date',  null, ['placeholder' => 'pick renewal date', 'class' => 'form-control datepicker']) !!}
    </div>
</div>