<?php namespace Qcsoft\App\Controllers;

use Backend\Classes\Controller;
use Backend\Widgets\Form;
use Backend\Widgets\Lists;
use BackendMenu;
use October\Rain\Database\Builder;
use Qcsoft\App\Classes\CatalogitemFilteroptionsBackend;
use Qcsoft\Ocext\Behaviors\ColumnInputController;
use Qcsoft\Ocext\Behaviors\MakeSlugController;

class Bundles extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        MakeSlugController::class,
        ColumnInputController::class,
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Qcsoft.App', 'main-menu-app', 'side-menu-bundles');
    }

    public function formBeforeSave($model)
    {
        (new CatalogitemFilteroptionsBackend())->bindModelSave($model);
    }

    public function formExtendFieldsBefore(Form $form)
    {
        (new CatalogitemFilteroptionsBackend())->extendFormFields($form);
    }

    public function listExtendColumns(Lists $lists)
    {
        (new CatalogitemFilteroptionsBackend())->extendListColumns($lists);
    }

    public function listExtendQuery(Builder $query, $definition)
    {
        $query->withComposites();

        $query->with(['catalogitem', 'catalogitem.main_image'/*, 'catalogitem.main_category'*/]);

        (new CatalogitemFilteroptionsBackend())->extendListQuery($query);
    }

}
