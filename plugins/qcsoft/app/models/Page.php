<?php namespace Qcsoft\App\Models;

use Qcsoft\App\Modelsbase\PageBase;

class Page extends PageBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

    public function newQuery()
    {
        return parent::newQuery()->withOwnerTypeId();
    }

    public function scopeWithOwnerTypeId($query)
    {
        $tlayout = (new Layout())->getTable();

        return $query->addSelect(['*', \DB::raw(<<<EOT
(select l.owner_type_id from $tlayout l where l.id = layout_id) as owner_type_id
EOT
        )]);
    }
}
