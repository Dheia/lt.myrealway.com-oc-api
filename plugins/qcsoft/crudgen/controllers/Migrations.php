<?php namespace Qcsoft\Crudgen\Controllers;

use Backend\Classes\Controller;
use Backend\Widgets\Form;
use BackendMenu;
use October\Rain\Database\Model;
use Qcsoft\Crudgen\Behaviors\ReadsActivePluginDbml;
use Qcsoft\Crudgen\Classes\Generator;

/**
 * Class Migrations
 * @package Qcsoft\Crudgen\Controllers
 * @mixin ReadsActivePluginDbml
 */
class Migrations extends Controller
{
    public $implement = [ReadsActivePluginDbml::class];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Qcsoft.Crudgen', 'main-menu-crudgen', 'side-menu-migrations');
    }

    public function index()
    {
        if ($this->activePluginDbmlIsError)
        {
            return;
        }

        $this->vars['activePluginCode'] = $this->activePluginCode;

        $codeEditorFormModel = new Model();

        $codeEditorFormModel->code = '<?php ' . $this->makePendingMigrations();

        $codeEditorForm = new Form($this, [
            'fields' => [
                'code' => [
                    'label'    => 'Migrations code',
                    'type'     => 'codeeditor',
                    'size'     => 'giant',
                    'readOnly' => true,
                ]
            ],
            'model'  => $codeEditorFormModel
        ]);

        $codeEditorForm->bindToController();
    }

    public function onApplyPendingMigrations()
    {
        $migrations = eval($this->makePendingMigrations());

        foreach ($migrations as $migration)
        {
            $migration->up();
        }

        \Flash::info("Applied migrations from $this->activePluginCode schema.dbml");

        return \Redirect::refresh();
    }

    protected function makePendingMigrations()
    {
        $generator = new Generator($this->activePluginCode, $this->activePluginDbmlFilePath);

        $migrationModels = $generator->generateMigrations();

        $code = "return [\n"
            . collect($migrationModels)
                ->map(function ($item) {
                    return $item ? "new class {\n$item->code\n}" : null;
                })
                ->filter()
                ->implode(",\n")
            . '];';

        return $code;
    }

}
