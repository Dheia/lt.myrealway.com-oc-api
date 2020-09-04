<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Collection;
use October\Rain\Database\Model;
use Qcsoft\App\Models\Bundle;
use Qcsoft\App\Models\CatalogitemCategory;
use Qcsoft\App\Models\CatalogitemCustomergroup;
use Qcsoft\App\Models\CatalogitemFilteroption;
use Qcsoft\App\Models\CatalogitemRelevantitem;
use Qcsoft\App\Models\Category;
use Qcsoft\App\Models\Product;
use System\Models\File;

/**
 * Class CatalogitemBase
 * @package Qcsoft\App\Modelsbase
 * @property Collection $catalogitem_categories
 * @property Collection $catalogitem_customergroups
 * @property Collection $catalogitem_filteroptions
 * @property Collection $catalogitem_relevantitems
 * @property int $id
 * @property mixed $item
 * @property int $item_id
 * @property string $item_type
 * @property Category $main_category
 * @property int $main_category_id
 * @property File $main_image
 * @property string $name
 * @property int $price
 */
class CatalogitemBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_app_catalogitem';

    public $morphTo = [
        'item' => [],
    ];

    public $belongsTo = [
        'main_category' => [Category::class],
    ];

    public $hasMany = [
        'catalogitem_filteroptions' => [CatalogitemFilteroption::class, 'delete' => false],
        'catalogitem_customergroups' => [CatalogitemCustomergroup::class, 'delete' => false],
        'catalogitem_categories' => [CatalogitemCategory::class, 'delete' => false],
//        'catalogitem_relevantitems' => [CatalogitemRelevantitem::class, 'delete' => true],
    ];

    public $attachOne = [
        'main_image' => [File::class],
    ];

}
