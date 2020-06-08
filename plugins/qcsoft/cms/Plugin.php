<?php namespace Qcsoft\Cms;

use Cms\Classes\Controller;
use Cms\Classes\Page;
use Cms\Classes\Router;
use Cms\Classes\Theme;
use Qcsoft\Cms\Models\Genericblock;
use Qcsoft\Cms\Models\Genericpage;
use Qcsoft\Shop\Models\Category;
use Qcsoft\Shop\Models\Product;
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
        \Event::listen('qcsoft.cms.registerPageTypes', function () {
            return [
                Genericpage::class,
            ];
        });

        \Event::listen('cms.router.beforeRoute', function ($url, Router $router) use (&$pageObj) {

            if ($url !== '/')
            {
                $url = trim($url, '/');
            }

            $pageTypes = array_flatten(event('qcsoft.cms.registerPageTypes'));

            foreach ($pageTypes as $pageClass)
            {
                if ($pageObj = $pageClass::where('path', $url)->first())
                {
                    iF ($url === '/')
                    {
                        $filename = 'home';
                    }
                    elseif (file_exists(Theme::getActiveTheme()->getPath() . "/pages/$url.htm"))
                    {
                        $filename = $url;
                    }
                    else
                    {
                        $filename = \Str::snake(class_basename($pageClass), '-');
                    }

                    break;
                }
            }

            if (!$pageObj || $url == '404')
            {
                $pageObj = Genericpage::where('path', '404')->first();

                Controller::getController()->setStatusCode(404);

                $filename = '404';
            }

            return Page::loadCached(Theme::getActiveTheme(), $filename);
        });

        \Event::listen('cms.page.init', function (Controller $controller, Page $page) use (&$pageObj) {

            $pageObjVarName = lcfirst(class_basename($pageObj));

            $controller->vars[$pageObjVarName] = $controller->vars['pageObj'] = $pageObj;

            $pageType = \Str::snake(class_basename($pageObj), '-');

            $genericBlocks = Genericblock
                ::where(function ($query) use ($pageType, $pageObj) {
                    $query
                        ->where('owner_type', $pageType)
                        ->where(function ($query) use ($pageType, $pageObj) {
                            $query
                                ->where('owner_id', $pageObj->id)
                                ->orWhere('owner_id', null);
                        });
                })
                ->orWhere('owner_type', null)
                ->get();

            $layoutBlocks = new GenericBlock();
            $layoutBlocks->children = $genericBlocks->where('owner_type', null)->toNested();
            $controller->vars['layoutBlocks'] = $layoutBlocks;

            $pageBlocks = new GenericBlock();
            $pageBlocks->children = $genericBlocks->where('owner_type', $pageType)->toNested();
            $controller->vars['pageBlocks'] = $pageBlocks;

            if (\Request::ajax() && \Request::input('noLayout'))
            {
                $controller->vars['noLayout'] = $pageBlocks;
            }
        });

    }
}
