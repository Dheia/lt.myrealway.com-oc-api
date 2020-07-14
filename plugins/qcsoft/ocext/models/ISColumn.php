<?php namespace Qcsoft\Ocext\Models;

use October\Rain\Database\Builder;
use October\Rain\Database\Model;

class ISColumn extends Model
{
    public $timestamps = false;

    public $table = 'information_schema.columns';

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('table_schema', function (Builder $query)
        {
            $query->where('TABLE_SCHEMA', \DB::connection()->getDatabaseName());
        });
    }

}
