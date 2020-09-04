<?php namespace Qcsoft\App\Controllers;

use Backend\Classes\Controller;
use Backend\Widgets\Form;
use Backend\Widgets\Lists;
use BackendMenu;
use October\Rain\Database\Builder;
use Qcsoft\App\Classes\CatalogitemFilteroptionsBackend;
use Qcsoft\App\Models\Bundle;
use Qcsoft\App\Models\Catalogitem;
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

    public function listGetQuery($definition, $selectConstraints)
    {
//        dump($selectConstraints);
//        return <<<EOT
//{
//    bundle {
//        id
//        catalogitem {
//            id
//            name
//        }
//    }
//}
//EOT;
        return <<<EOT
{
    page_count (selectWhereIn: ["owner_type", "genericpage", "bundle"])
    page (selectWhereIn: ["owner_type", "genericpage", "bundle"]) {
        id
        path
        owner {
            __typename
            ... on Bundle {
                id
                catalogitem {
                    id
                    name
                }
            }
            ... on Product {
                id
                catalogitem {
                    id
                    name
                }
            }
            ... on Genericpage {
                id
                name
                content
            }
        }
    }
}
EOT;
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
//        (new CatalogitemFilteroptionsBackend())->extendListColumns($lists);
    }

    public function listExtendQuery(Builder $query, $definition)
    {
        $query->withComposites();

        $query->with(['catalogitem', 'catalogitem.main_image'/*, 'catalogitem.main_category'*/]);

//        (new CatalogitemFilteroptionsBackend())->extendListQuery($query);
    }

    public function formCreateModelObject()
    {
        $result = new Bundle();

        $result->catalogitem = new Catalogitem();

        return $result;
    }

}
