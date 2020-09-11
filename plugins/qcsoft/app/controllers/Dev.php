<?php namespace Qcsoft\App\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Illuminate\Database\Events\StatementPrepared;
use Qcsoft\App\Classes\ImportOldSite;
use Qcsoft\App\Classes\SeedDb;
use Qcsoft\App\Classes\Webcache\WebCacheWriter;
use Qcsoft\App\Classes\WriteApiCache;
use Qcsoft\App\Models\Genericpage;
use Qcsoft\App\Models\Page;
use Qcsoft\App\Models\Product;
use Qcsoft\Ocext\Behaviors\MaintenanceController;
use Qcsoft\App\Classes\RandomBundles;

class Dev extends Controller
{
    public $implement = [MaintenanceController::class];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Qcsoft.App', 'main-menu-app', 'side-menu-dev');
    }

    public function index()
    {
        return $this->asExtension(MaintenanceController::class)->index();
    }

    public function step_1_cleanup()
    {
        (new SeedDb())->cleanup();
        return $this->asExtension(MaintenanceController::class)->index(__FUNCTION__);
    }

    public function step_2_import_old_site_and_seed_base()
    {
        (new ImportOldSite())->import();
        (new SeedDb())->seedFilters();
        (new SeedDb())->seedViews();
        return $this->asExtension(MaintenanceController::class)->index(__FUNCTION__);
    }

    public function step_3_generate_random_bundles()
    {
        (new RandomBundles())->generate();
        return $this->asExtension(MaintenanceController::class)->index(__FUNCTION__);
    }

    public function step_4_make_random_filter_bindings($offset = 0)
    {
        \Debugbar::disable();

        $seedDb = new SeedDb();

        $nextOffset = $seedDb->makeRandomFilterBindings($offset);

        if ($nextOffset === null)
        {
            return $this->asExtension(MaintenanceController::class)->index(__FUNCTION__);
        }
        else
        {
            return '<script>location.href="' . $this->actionUrl(__FUNCTION__ . "/$nextOffset") . '"</script>';
        }
    }

    public function step_5_make_random_catalogitem_relevant_items($offset = 0)
    {
        \Debugbar::disable();

        $seedDb = new SeedDb();

        $nextOffset = $seedDb->makeRandomCatalogitemRelevantItems($offset);

        if ($nextOffset === null)
        {
            return $this->asExtension(MaintenanceController::class)->index(__FUNCTION__);
        }
        else
        {
            return '<script>location.href="' . $this->actionUrl(__FUNCTION__ . "/$nextOffset") . '"</script>';
        }
    }

    public function step_6_write_api_base()
    {
        $api = new WriteApiCache();

        $api->writeBase();

        return $this->asExtension(MaintenanceController::class)->index(__FUNCTION__);
    }

    public function step_7_rm_rf_api_cache($type = null, $offset = null)
    {
        system('rm -rf ' . storage_path('apicache'));

        return $this->asExtension(MaintenanceController::class)->index(__FUNCTION__);
    }

    public function step_8_write_api_cache($type = null, $offset = null)
    {
        $api = new WriteApiCache();

        if ($next = $api->write($type, $offset))
        {
//            dd($next);
            return '<script>location.href="' . $this->actionUrl(__FUNCTION__ . "/$next") . '"</script>';
        }

        return $this->asExtension(MaintenanceController::class)->index(__FUNCTION__);
    }

    public function step_9_write_page_requires($type = null, $offset = 0)
    {
        $types = [
            'genericpage' => Genericpage::class,
            'bundle'      => Product::class,
            'product'     => Product::class,
        ];

        $requires = (new Product())->getPageRequireEntities(['limit' => 10, 'offset' => $offset]);
        dd($requires);
    }

}
