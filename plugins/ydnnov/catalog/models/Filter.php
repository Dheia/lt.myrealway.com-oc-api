<?php namespace Ydnnov\Catalog\Models;

use Ydnnov\Catalog\Modelsbase\FilterBase;

class Filter extends FilterBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

    protected static function boot()
    {
        parent::boot();

        Filter::extend(function ($model) {

            $optionsRepeaterValue = null;

            $model->bindEvent('model.saveInternal', function () use ($model, &$optionsRepeaterValue) {

                $optionsRepeaterValue = $model->attributes['options_repeater'];

                unset($model->attributes['options_repeater']);
            });

            $model->bindEvent('model.afterSave', function () use ($model, &$optionsRepeaterValue) {
                foreach($optionsRepeaterValue as $option){
                    \Debugbar::info($option);
                }
                return false;
            });
        });
    }

    public function saveOptionsRepeaterToRelation()
    {

    }

    public function getOptionsRepeaterAttribute()
    {
        return [
            [
                'id'   => '123',
                'name' => 'qwer',
            ],
            [
                'id'   => '234',
                'name' => 'asdf',
            ],
        ];
    }

}
