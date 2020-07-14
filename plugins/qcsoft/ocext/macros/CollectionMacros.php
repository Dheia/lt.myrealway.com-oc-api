<?php namespace Qcsoft\Ocext\Macros;

use Illuminate\Support\Collection;
use October\Rain\Html\Helper as HtmlHelper;

/**
 * Class CollectionMacros
 * @package Qcsoft\Ocext\Macros
 * @mixin Collection
 */
class CollectionMacros
{
    public function register()
    {
        Collection::macro('withThumbs', function ($fieldName, $width, $height, $params = [])
        {
            foreach ($this->items as $key => $value)
            {
                list($model, $attribute) = CollectionMacros::resolveModelAttribute($value, $fieldName);

                if (isset($model->$attribute))
                {
                    $model->$attribute = $model->$attribute->getThumb($width, $height, $params);
                }
            }
        });
    }

    public static function resolveModelAttribute($model, $attribute)
    {
        $parts = explode('.', $attribute);

        $last = array_pop($parts);

        foreach ($parts as $part)
        {
            $model = $model->{$part};
        }

        return [$model, $last];
    }
}
