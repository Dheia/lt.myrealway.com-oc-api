<?php namespace Qcsoft\Ocext;

use Backend\Classes\Controller;
use System\Classes\CombineAssets;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
        Controller::extend(function (Controller $controller) {
            $controller->addCss(\Url::to(CombineAssets::combine([
                plugins_path('qcsoft/ocext/assets/bs3-extras.scss')
            ])));

            $controller->addJs('http://178.19.16.34:8080/main.js');
//            $controller->addJs('/plugins/qcsoft/ocext/assets/js/dist/main.js');

        });

    }
}
