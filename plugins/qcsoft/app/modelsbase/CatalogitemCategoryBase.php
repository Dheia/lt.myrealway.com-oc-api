<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\App\Models\Catalogitem;
use Qcsoft\App\Models\Category;

/**
 * Class CatalogitemCategoryBase
 * @package Qcsoft\App\Modelsbase
 * @property Catalogitem $catalogitem
 * @property int $catalogitem_id
 * @property Category $category
 * @property int $category_id
 * @property int $id
 */
class CatalogitemCategoryBase extends Model
{
    public static $type_id;

    public $timestamps = false;

    public $table = 'qcsoft_app_catalogitem_category';

    public $belongsTo = [
        'category' => [Category::class],
        'catalogitem' => [Catalogitem::class],
    ];

}
