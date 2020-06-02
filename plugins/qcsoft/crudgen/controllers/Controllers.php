<?php namespace Qcsoft\Crudgen\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Qcsoft\Crudgen\Behaviors\ReadsActivePluginDbml;
use Qcsoft\Crudgen\Classes\Generator;

/**
 * Class Controllers
 * @package Qcsoft\Crudgen\Controllers
 * @mixin ReadsActivePluginDbml
 */
class Controllers extends Controller
{
    public $implement = [ReadsActivePluginDbml::class];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Qcsoft.Crudgen', 'main-menu-crudgen', 'side-menu-controllers');
    }

    public function index()
    {
        if ($this->activePluginDbmlIsError)
        {
            return;
        }

        $this->vars['activePluginCode'] = $this->activePluginCode;

        $this->generateOrUpdateControllers();
    }

    public function generateOrUpdateControllers()
    {
        $generator = new Generator($this->activePluginCode, $this->activePluginDbmlFilePath);

        $generator->generateControllers();

        $generator->generateMenus();
    }

}
