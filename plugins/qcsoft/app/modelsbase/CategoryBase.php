<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Collection;
use October\Rain\Database\Model;
use October\Rain\Database\Traits\NestedTree;
use Qcsoft\App\Models\Catalogitem;
use Qcsoft\App\Models\CatalogitemCategory;

/**
 * Class CategoryBase
 * @package Qcsoft\App\Modelsbase
 * @property Collection $catalogitems
 * @property Collection $category_catalogitems
 * @property string $description
 * @property int $id
 * @property string $name
 * @property int $nest_depth
 * @property int $nest_left
 * @property int $nest_right
 * @property int $parent_id
 */
class CategoryBase extends Model
{
    public static $type_id;

    use NestedTree;

    public $timestamps = false;

    public $table = 'qcsoft_app_category';

    public $hasMany = [
        'category_catalogitems' => [CatalogitemCategory::class, 'delete' => false],
        'catalogitems' => [Catalogitem::class, 'delete' => false],
    ];

}
