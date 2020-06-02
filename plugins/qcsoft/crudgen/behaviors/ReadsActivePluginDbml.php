<?php namespace Qcsoft\Crudgen\Behaviors;

use Backend\Classes\ControllerBehavior;

class ReadsActivePluginDbml extends ControllerBehavior
{
    public $activeAuthor;

    public $activePlugin;

    public $activePluginCode;

    public $activePluginDbmlFilePath;

    public $activePluginDbmlStatus;

    public $activePluginDbmlIsError;

    public function __construct($controller)
    {
        parent::__construct($controller);

        $this->activeAuthor = \Session::get('qcsoft.crudgen.activeAuthor');
        $this->activePlugin = \Session::get('qcsoft.crudgen.activePlugin');

        $this->activePluginCode = $this->activeAuthor . '.' . $this->activePlugin;

        $this->activePluginDbmlFilePath = plugins_path(
            strtolower($this->activeAuthor . '/' . $this->activePlugin) . '/schema.dbml'
        );

        if (empty($this->activeAuthor) || empty($this->activePlugin))
        {
            $this->activePluginDbmlStatus = 'Active plugin not selected';

            $this->activePluginDbmlIsError = true;
        }
        elseif (!file_exists($this->activePluginDbmlFilePath))
        {
            $this->activePluginDbmlStatus = "Plugin $this->activePluginCode does not have schema.dbml file";

            $this->activePluginDbmlIsError = true;
        }
        else
        {
            $this->activePluginDbmlStatus = "Plugin: $this->activePluginCode";

            $this->activePluginDbmlIsError = false;
        }
    }

}
