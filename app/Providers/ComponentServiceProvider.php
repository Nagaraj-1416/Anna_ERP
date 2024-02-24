<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class ComponentServiceProvider
 * @package App\Providers
 */
class ComponentServiceProvider extends ServiceProvider
{

    public function boot()
    {
        form()->component('bsText', '_inc.components.form.text', ['name', 'label' => null, 'value' => null, 'attributes' => [], 'required' => true,  'horizontal' => false, 'helpBlocks' => [],'labelClass' => null ]);
        form()->component('bsTextarea', '_inc.components.form.textarea', ['name', 'label' => null, 'value' => null, 'attributes' => [], 'required' => true, 'horizontal' => false, 'helpBlocks' => [], 'labelClass' => null]);
        form()->component('bsEmail', '_inc.components.form.email', ['name', 'label' => null, 'value' => null, 'attributes' => [], 'required' => true, 'horizontal' => false, 'helpBlocks' => []]);
        form()->component('bsCheckbox', '_inc.components.form.checkbox', ['name', 'label' => null, 'value' => null, 'checked' => false, 'attributes' => [], 'required' => true, 'horizontal' => false, 'helpBlocks' => []]);
        form()->component('bsFile', '_inc.components.form.file', ['name', 'label' => null, 'attributes' => [], 'required' => true, 'horizontal' => false, 'helpBlocks' => []]);
        form()->component('bsSelect', '_inc.components.form.select', ['name', 'label' => null, 'data' => [], 'value' => null, 'attributes' => [], 'required' => true, 'optionAttributes' => [], 'horizontal' => false, 'helpBlocks' => [], 'labelClass' => null]);
        form()->component('bsSubmit', '_inc.components.form.submit', ['text', 'class' => 'btn btn-success waves-effect waves-light m-r-10', 'value' => null, 'name' => null]);
        form()->component('bsCancel', '_inc.components.form.cancel', ['text', 'name' => 'home.index', 'params' => []]);
        form()->component('bsBack', '_inc.components.form.back', ['text', 'name' => 'home.index', 'params' => []]);

        form()->component('suCombo', '_inc.components.form.su.combo', ['name', 'label' => null, 'default' => 'Search...', 'multiple' => '', 'horizontal' => false, 'class' => null]);
        form()->component('suCheckbox', '_inc.components.form.su.checkbox', ['name', 'label' => null, 'value' => null, 'checked' => false, 'attributes' => [], 'required' => true, 'horizontal' => false]);
        form()->component('suSubmit', '_inc.components.form.su.submit', ['text', 'class' => 'green', 'disabled' => false]);
        form()->component('suCancel', '_inc.components.form.su.cancel', ['text', 'name' => 'home.index', 'params' => []]);
    }
    public function register()
    {

    }
}
