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
 * @property int $default_price
 * @property int $id
 * @property Category $main_category
 * @property int $main_category_id
 * @property File $main_image
 * @property string $name
 * @property mixed $owner
 * @property int $owner_id
 * @property int $owner_type_id
 */
class CatalogitemBase extends Model
{
    public static $type_id;

    public $timestamps = false;

    public $table = 'qcsoft_app_catalogitem';

    public $morphTo = [
        'owner' => ['type' => 'owner_type_id'],
    ];

    public $belongsTo = [
        'main_category' => [Category::class],
    ];

    public $hasMany = [
        'catalogitem_filteroptions'  => [CatalogitemFilteroption::class, 'delete' => false],
        'catalogitem_customergroups' => [CatalogitemCustomergroup::class, 'delete' => false],
        'catalogitem_categories'     => [CatalogitemCategory::class, 'delete' => false],
        //        'catalogitem_relevantitems' => [CatalogitemRelevantitem::class, 'delete' => true],
    ];

    public $attachOne = [
        'main_image' => [File::class],
    ];

}
