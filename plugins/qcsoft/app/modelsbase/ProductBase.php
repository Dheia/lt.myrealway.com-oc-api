<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Collection;
use October\Rain\Database\Model;
use Qcsoft\App\Models\BundleProduct;
use Qcsoft\App\Models\Catalogitem;
use Qcsoft\App\Models\Page;
use Qcsoft\App\Traits\CompositeModel;

/**
 * Class ProductBase
 * @package Qcsoft\App\Modelsbase
 * @property Catalogitem $catalogitem
 * @property int $default_price
 * @property string $description
 * @property int $id
 * @property string $ingredients
 * @property string $mini_desc
 * @property Page $page
 * @property Collection $product_bundles
 * @property string $product_code
 */
class ProductBase extends Model
{
    use CompositeModel;

    public $compositeModel = [
        'catalogitem' => [],
        'page' => [],
    ];

    public $timestamps = false;

    public $table = 'qcsoft_app_product';

    public $hasMany = [
        'product_bundles' => [BundleProduct::class, 'delete' => false],
    ];

    public $morphOne = [
        'page' => [Page::class, 'name' => 'owner', 'delete' => true],
        'catalogitem' => [Catalogitem::class, 'name' => 'item', 'delete' => true],
    ];

}
