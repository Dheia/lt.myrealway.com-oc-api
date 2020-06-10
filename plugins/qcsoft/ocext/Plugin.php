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

            $controller->addJs('/plugins/qcsoft/ocext/assets/vendor/vue.js');
            $controller->addJs('/plugins/qcsoft/ocext/assets/vendor/uiv.min.js');
            $controller->addJs('/plugins/qcsoft/ocext/assets/vue-shell.js');
        });
    }
}
