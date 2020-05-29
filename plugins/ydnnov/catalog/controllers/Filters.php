<?php namespace Ydnnov\Catalog\Controllers;

use Backend\Behaviors\FormController;
use Backend\Classes\Controller;
use BackendMenu;
use Ydnnov\Catalog\Models\Filter;

class Filters extends Controller
{
    public $implement = ['Backend\Behaviors\ListController', 'Backend\Behaviors\FormController'];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Ydnnov.Catalog', 'main-menu-catalog', 'side-menu-filters');
    }

    public function update_onSave($recordId = null, $context = null)
    {
        /** @var FormController $formController */
        $formController = $this->asExtension('FormController');

        $formController->update_onSave($recordId, $context);

        \Request::offsetUnset('Filter');
        \Request::offsetUnset('formOptionsRepeater_loaded');

        $newBehaviorInstance = new FormController($this);

        $newBehaviorInstance->initForm(Filter::find($recordId));

        return $newBehaviorInstance->formGetWidget()->onRefresh();
    }
}
