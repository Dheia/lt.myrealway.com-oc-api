<?php namespace Qcsoft\App\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Illuminate\Database\Events\StatementPrepared;
use October\Rain\Database\Relations\Relation;
use Qcsoft\App\Dev\ImportOldSite;
use Qcsoft\App\Dev\SeedHelper;
use Qcsoft\App\Classes\WriteApiCache;
use Qcsoft\App\Models\Bundle;
use Qcsoft\App\Models\Catalogitem;
use Qcsoft\App\Models\Custompage;
use Qcsoft\App\Models\Layout;
use Qcsoft\App\Models\Page;
use Qcsoft\App\Models\Product;
use Qcsoft\Ocext\Behaviors\MaintenanceController;
use Qcsoft\App\Dev\RandomBundles;

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
        (new SeedHelper())->step1_cleanup();
        return $this->asExtension(MaintenanceController::class)->index(__FUNCTION__);
    }

    public function step_2_seed_layouts()
    {
        (new SeedHelper())->step2_seedLayouts();
        return $this->asExtension(MaintenanceController::class)->index(__FUNCTION__);
    }

    public function step_3_seed_custompages()
    {
        (new SeedHelper())->step3_seedCustompages();
        return $this->asExtension(MaintenanceController::class)->index(__FUNCTION__);
    }

    public function step_4_import_old_site()
    {
        (new SeedHelper())->step4_importOldSite();
        return $this->asExtension(MaintenanceController::class)->index(__FUNCTION__);
    }

    public function step_5_seed_filters()
    {
        (new SeedHelper())->step5_seedFilters();
        return $this->asExtension(MaintenanceController::class)->index(__FUNCTION__);
    }

    public function step_6_generate_random_bundles()
    {
        (new RandomBundles())->generate(2000);
        return $this->asExtension(MaintenanceController::class)->index(__FUNCTION__);
    }

    public function step_7_make_random_filter_bindings($offset = 0)
    {
        \Debugbar::disable();

        $seedHelper = new SeedHelper();

        $nextOffset = $seedHelper->makeRandomFilterBindings($offset);

        if ($nextOffset === null)
        {
            return $this->asExtension(MaintenanceController::class)->index(__FUNCTION__);
        }
        else
        {
            return '<script>location.href="' . $this->actionUrl(__FUNCTION__ . "/$nextOffset") . '"</script>';
        }
    }

    public function step_8_make_random_catalogitem_relevant_items($offset = 0)
    {
        \Debugbar::disable();

        $seedHelper = new SeedHelper();

        $nextOffset = $seedHelper->makeRandomCatalogitemRelevantItems($offset);

        if ($nextOffset === null)
        {
            return $this->asExtension(MaintenanceController::class)->index(__FUNCTION__);
        }
        else
        {
            return '<script>location.href="' . $this->actionUrl(__FUNCTION__ . "/$nextOffset") . '"</script>';
        }
    }

    public function step_9_rm_rf_api_cache($type = null, $offset = null)
    {
        system('rm -rf ' . storage_path('apicache'));

        return $this->asExtension(MaintenanceController::class)->index(__FUNCTION__);
    }

    public function step_10_write_api_cache($type = null, $contextStr = '')
    {
        $api = new WriteApiCache();

        if ($next = $api->write(2, $type, $contextStr))
        {
//            dd($next);
            return '<script>location.href="' . $this->actionUrl(__FUNCTION__ . "/$next") . '"</script>';
        }

        return $this->asExtension(MaintenanceController::class)->index(__FUNCTION__);
    }

}
