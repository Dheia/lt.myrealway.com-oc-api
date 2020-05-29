<?php namespace Ydnnov\Crudgen\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use DbmlParser\Parser;
use RainLab\Builder\Classes\ModelModel;
use RainLab\Builder\Classes\PluginCode;
use System\Classes\PluginBase;
use System\Classes\PluginManager;
use Ydnnov\Crudgen\Classes\DbmlBelongsTo;
use Ydnnov\Crudgen\Classes\DbmlColumn;
use Ydnnov\Crudgen\Classes\DbmlHasMany;
use Ydnnov\Crudgen\Classes\DbmlModel;
use Ydnnov\Crudgen\Classes\Generator;

class Home extends Controller
{
    public $implement = [];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Ydnnov.Crudgen', 'main-menu-crudgen', 'side-menu-home');
    }

    public function index()
    {
        return '<a href="' . \Backend::url('ydnnov/crudgen/home/updatemodelsbase') . '">updateModelsbase</a>';

        $generator = new Generator('Ydnnov.Catalog', plugins_path('ydnnov/catalog/schema.dbml'));

//        return $this->createTables($generator);
//        return $generator->prettyPrint();
//        $generator->generateModels();
//        return $generator->generateMenus();
//        return $generator->generateControllers();

        $this->cleanStart($generator);
    }

    public function updateModelsbase()
    {
        $generator = new Generator('Ydnnov.Catalog', plugins_path('ydnnov/catalog/schema.dbml'));

        $generator->generateModels();
    }

    /**
     * @param Generator $generator
     * @return string
     */
    protected function cleanStart($generator)
    {
        $this->createTables($generator, false);
        $generator->generateModels();
        $generator->generateMenus();
        $generator->generateControllers();
    }

    /**
     * @param Generator $generator
     * @return string
     */
    protected function createTables($generator, $dry = true)
    {
        $migrationsCode = implode("\n", $generator->generateMigrations());

        if (!$dry)
        {
            file_put_contents(plugins_path('ydnnov/catalog/_schema.php'), <<<EOT
<?php return function() {
$migrationsCode
};
EOT
            );

            $migrationFn = require(plugins_path('ydnnov/catalog/_schema.php'));

            $migrationFn();
        }

        return '<pre>' . $migrationsCode . '</pre>';
    }

}
