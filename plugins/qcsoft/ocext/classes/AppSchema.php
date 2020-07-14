<?php namespace Qcsoft\Ocext\Classes;

use October\Rain\Support\Traits\Singleton;
use Qcsoft\Ocext\Models\ISColumn;

class AppSchema
{
    use Singleton;

    public $list;

    protected function init()
    {
        $dbName = \DB::connection()->getDatabaseName();

        $this->list = collect(\DB::connection()->select(<<<EOT
select *
from information_schema.columns
where columns.TABLE_SCHEMA = '$dbName'
EOT
        ))
            ->groupBy('TABLE_NAME');
    }
}
