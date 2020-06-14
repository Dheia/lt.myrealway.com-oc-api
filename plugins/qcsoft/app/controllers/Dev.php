<?php namespace Qcsoft\App\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Qcsoft\App\Classes\ImportOldSite;
use Qcsoft\Ocext\Behaviors\MaintenanceController;

class Dev extends Controller
{
    public $implement = [MaintenanceController::class];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Qcsoft.App', 'main-menu-app', 'side-menu-dev');
    }

    public function importOldSite()
    {
        (new ImportOldSite())->import();

        return $this->asExtension(MaintenanceController::class)->index();
    }
}
