<?php namespace Ydnnov\Crudgen\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
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
        return collect((new \ReflectionClass($this))->getMethods(\ReflectionMethod::IS_PUBLIC))
            ->filter(function ($item) {
                /** @var \ReflectionMethod $item */
                return $item->getDeclaringClass()->getName() === static::class &&
                    $item->getName() !== '__construct';
            })
            ->map(function ($item) {
                /** @var \ReflectionMethod $item */
                $lname = strtolower($name = $item->getName());
                return '<p><a href="' . \Backend::url("ydnnov/crudgen/home/$lname") . '">' . $name . '</a></p>';
            })
            ->implode('');
    }

    public function createTables()
    {
        $generator = new Generator('Ydnnov.Catalog', plugins_path('ydnnov/catalog/schema.dbml'));

        $migrationModels = $generator->generateMigrationModels();

        $code = "<?php return [\n"
            . collect($migrationModels)
                ->map(function ($item) {
                    return "new class {\n$item->code\n}";
                })
                ->implode(",\n")
            . '];';

        file_put_contents(plugins_path('ydnnov/catalog/_migrations.php'), $code);

        $migrations = require(plugins_path('ydnnov/catalog/_migrations.php'));

        foreach ($migrations as $migration)
        {
            $migration->up();
        }
    }

    public function generateOrUpdateModels()
    {
        $generator = new Generator('Ydnnov.Catalog', plugins_path('ydnnov/catalog/schema.dbml'));

        $generator->generateOrmModels();
    }

    public function generateMenus()
    {
        $generator = new Generator('Ydnnov.Catalog', plugins_path('ydnnov/catalog/schema.dbml'));

        $generator->generateMenus();
    }

    public function generateControllers()
    {
        $generator = new Generator('Ydnnov.Catalog', plugins_path('ydnnov/catalog/schema.dbml'));

        $generator->generateControllers();
    }

}
