<?php namespace Qcsoft\App\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Illuminate\Database\Events\StatementPrepared;
use Qcsoft\App\Classes\ImportOldSite;
use Qcsoft\App\Classes\SeedDb;
use Qcsoft\App\Classes\WriteApiCache;
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
        return $this->asExtension(MaintenanceController::class)->index();
    }

    public function step_2_import_old_site()
    {
        (new ImportOldSite())->import();
        return $this->asExtension(MaintenanceController::class)->index();
    }

    public function step_3_seed_filters()
    {
        (new SeedDb())->seedFilters();
        return $this->asExtension(MaintenanceController::class)->index();
    }

    public function step_4_generate_random_bundles()
    {
        (new RandomBundles())->generate();
        return $this->asExtension(MaintenanceController::class)->index();
    }

    public function step_5_make_random_filter_bindings($offset = 0)
    {
        \Debugbar::disable();

        $seedDb = new SeedDb();

        $nextOffset = $seedDb->makeRandomFilterBindings($offset);

        if ($nextOffset === null)
        {
            return $this->asExtension(MaintenanceController::class)->index();
        }
        else
        {
            return '<script>location.href="' . $this->actionUrl("step_5_make_random_filter_bindings/$nextOffset") . '"</script>';
        }
    }

    public function step_6_write_api_cache($type = null, $offset = null)
    {
        $api = new WriteApiCache();

        if ($nextChunk = $api->write($type, $offset,1000))
        {
            return '<script>location.href="' . $this->actionUrl("step_6_write_api_cache/$nextChunk") . '"</script>';
        }

        return $this->asExtension(MaintenanceController::class)->index();
    }


//    public function page()
//    {
//        $time = microtime(true);
//
//        \DB::connection()->getPdo()->exec('set group_concat_max_len = 10485760');
//
//        $map = \DB::select(<<<EOT
//select item_type, group_concat(concat(id, ':', item_id) separator ',') as data
//from qcsoft_app_catalogitem
//group by item_type
//EOT
//        );
//
//        foreach ($map as $entry)
//        {
////            dump(strlen($entry->data));
//            file_put_contents(storage_path("apicache/catalogitem-map-{$entry->item_type}.json"), "{{$entry->data}}");
//        }
//
//        dump(microtime(true) - $time);
//
////        $bundles=Bundle::count();
////        dump($bundles);
//
//        die;
//    }
//
//    public function qwer()
//    {
//        $filteroptions = Filteroption::all();
//
//        $table = (new CatalogitemFilteroption)->getTable();
//
//        \Event::listen(StatementPrepared::class, function ($event)
//        {
//            $event->statement->setFetchMode(\PDO::FETCH_COLUMN, 0);
//        });
//
//        $result = [];
//
//        foreach ($filteroptions as $filteroption)
//        {
//            $catalogitems = \DB::table($table)
//                ->where('filteroption_id', $filteroption->id)
//                ->select('catalogitem_id')
//                ->get()
//                ->implode(',');
//
//            $result[] = "{$filteroption->id}:[$catalogitems]}";
//        }
//
//        dump(strlen(implode(',', $result)));
//
//        die;
//    }

}
