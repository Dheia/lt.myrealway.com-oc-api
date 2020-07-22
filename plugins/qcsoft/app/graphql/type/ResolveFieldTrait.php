<?php namespace Qcsoft\App\GraphQL\Type;

use GraphQL\Type\Definition\ResolveInfo;
use October\Rain\Database\Collection;

trait ResolveFieldTrait
{
    public function resolveField($model, $args, $context, ResolveInfo $info)
    {
        $method = 'resolve' . ucfirst($info->fieldName);

        if (method_exists($this, $method))
        {
            return $this->{$method}($model, $args, $context, $info);
        }
        else
        {
            return $model->{$info->fieldName};
        }
    }
}
