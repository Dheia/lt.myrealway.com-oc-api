<?php namespace Qcsoft\App\Controllers;

use Backend\Classes\Controller;
use Backend\Widgets\Form;
use Backend\Widgets\Lists;
use BackendMenu;
use October\Rain\Database\Builder;
use Qcsoft\App\Classes\CatalogitemCustomergroupsBackend;
use Qcsoft\App\Classes\CatalogitemFilteroptionsBackend;
use Qcsoft\App\Models\Catalogitem;
use Qcsoft\App\Models\Customergroup;
use Qcsoft\App\Models\Product;
use Qcsoft\Ocext\Behaviors\ColumnInputController;
use Qcsoft\Ocext\Behaviors\MakeSlugController;

class Products extends Controller
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
        BackendMenu::setContext('Qcsoft.App', 'main-menu-app', 'side-menu-products');
    }

    public function listGetQuery($definition, $selectConstraints)
    {
        return <<<EOT
{
    product_count
    product {
        id
        path
        catalogitem {
            id
            name
        }
    }
}
EOT;
    }

    public function formBeforeSave($model)
    {
        (new CatalogitemFilteroptionsBackend())->bindModelSave($model);

        (new CatalogitemCustomergroupsBackend())->bindModelSave($model);
    }

    public function listExtendColumns(Lists $lists)
    {
        (new CatalogitemFilteroptionsBackend())->extendListColumns($lists);

//        (new CatalogitemCustomergroupsBackend())->extendListColumns($lists);
    }

    public function formCreateModelObject()
    {
        $result = new Product();

        $result->catalogitem = new Catalogitem();

        return $result;
    }

    public function listExtendQuery(Builder $query, $definition)
    {
        $query->withComposites();

        $query->with(['catalogitem', 'catalogitem.main_image', 'catalogitem.main_category']);

        (new CatalogitemFilteroptionsBackend())->extendListQuery($query);

//        (new CatalogitemCustomergroupsBackend())->extendListQuery($query);
    }

    public function formExtendFieldsBefore(Form $form)
    {
        (new CatalogitemFilteroptionsBackend())->extendFormFields($form);

        (new CatalogitemCustomergroupsBackend())->extendFormFields($form);
    }
}
