<?php namespace Qcsoft\Cms\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Qcsoft\Ocext\Behaviors\MakeSlugController;

class Genericpages extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        MakeSlugController::class,
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Qcsoft.Cms', 'main-menu-cms', 'side-menu-genericpages');
    }
}
