<?php namespace Qcsoft\Crudgen\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use System\Classes\PluginManager;

class PluginSelector extends Controller
{
    public $implement = [];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Qcsoft.Crudgen', 'main-menu-crudgen', 'side-menu-plugin-selector');
    }

    public function index()
    {
        $this->vars['authors'] = array_unique(array_map(function ($plugin) {
            return explode('.', $plugin)[0];
        }, array_keys(PluginManager::instance()->getPlugins())));

        if ($this->vars['activeAuthor'] = \Session::get('qcsoft.crudgen.activeAuthor'))
        {
            $this->vars['plugins'] = $this->getAuthorPlugins($this->vars['activeAuthor']);

            $this->vars['activePlugin'] = \Session::get('qcsoft.crudgen.activePlugin');
        }
    }

    public function onSelectAuthor()
    {
        \Session::put('qcsoft.crudgen.activeAuthor', $author = \Request::input('author'));

        \Session::put('qcsoft.crudgen.activePlugin', '');

        return [
            '#plugin_select' => $author ? $this->makePartial('plugin_select', [
                'activePlugin' => '',
                'plugins'      => $this->getAuthorPlugins($author)
            ]) : ''
        ];
    }

    public function onSelectPlugin()
    {
        \Session::put('qcsoft.crudgen.activePlugin', \Request::input('plugin'));
    }

    protected function getAuthorPlugins($author)
    {
        return array_filter(array_map(
            function ($item) use ($author) {
                list($_author, $plugin) = explode('.', $item);

                return $_author === $author ? $plugin : null;
            },
            array_keys(PluginManager::instance()->getPlugins())
        ));
    }

}
