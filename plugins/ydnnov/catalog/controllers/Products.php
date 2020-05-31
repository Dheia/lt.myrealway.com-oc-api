<?php namespace Ydnnov\Catalog\Controllers;

use Backend\Classes\Controller;
use Backend\Widgets\Form;
use BackendMenu;
use Ydnnov\Catalog\Models\Filter;

class Products extends Controller
{
    public $implement = ['Backend\Behaviors\ListController', 'Backend\Behaviors\FormController'];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Ydnnov.Catalog', 'main-menu-catalog', 'side-menu-products');
    }

    public function formExtendFieldsBefore(Form $form)
    {
        $filters = Filter::with('filteroptions')->get();

        foreach ($filters as $filter)
        {
            $form->tabs['fields']['filter_options[' . $filter->id . ']'] = [
                'label'       => $filter->name.' ('.$filter->id.')',
                'span'        => 'auto',
                'type'        => 'checkboxlist',
                'quickselect' => true,
                'options'     => $filter->filteroptions
                    ->sortBy('sort_order')
                    ->map(function($item){
                        $item->name=$item->name.' ('.$item->id.')';
                        return $item;
                    })
                    ->lists('name', 'id'),
                'tab'         => 'Filters'
            ];
        }
    }

}
