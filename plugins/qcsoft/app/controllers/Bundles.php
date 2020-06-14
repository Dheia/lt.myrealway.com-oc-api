<?php namespace Qcsoft\App\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
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
}
