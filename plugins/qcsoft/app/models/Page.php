<?php namespace Qcsoft\App\Models;

use October\Rain\Database\Model;
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
        return $query->addSelect(['*', \DB::raw($this->ownerTypeIdSubselect())]);
    }

    public function ownerTypeIdSubselect()
    {
        $tlayout = (new Layout)->getTable();

        return "(select l.owner_type_id from $tlayout l where l.id = layout_id) as owner_type_id";
    }

    public static function boot()
    {
        parent::boot();

        static::extend(function (Model $model)
        {
            $model->bindEvent('model.saveInternal', function () use ($model, &$saveAttributes)
            {
                unset($model->attributes['owner_type_id']);
            });
        });
    }

//    public function setOwnerAttribute($value)
//    {
//        $this->owner_id = $value->id;
////        $this->layout->owner_type_id = Entity::idByClassname(get_class($value));
//    }

//    public function setOwnerTypeIdAttribute($value)
//    {
//        $this->layout->owner_type_id = $value;
//    }

}
