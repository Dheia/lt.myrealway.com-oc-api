<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Collection;
use October\Rain\Database\Model;
use Qcsoft\App\Models\BundleProduct;
use Qcsoft\App\Models\Catalogitem;
use Qcsoft\App\Models\Page;
use Qcsoft\App\Traits\CompositeModel;

/**
 * Class BundleBase
 * @package Qcsoft\App\Modelsbase
 * @property Collection $bundle_products
 * @property Catalogitem $catalogitem
 * @property int $default_price
 * @property string $description
 * @property int $id
 * @property string $mini_desc
 * @property Page $page
 */
class BundleBase extends Model
{
    use CompositeModel;

    public $compositeModel = [
        'catalogitem' => [],
        'page' => [],
    ];

    public $timestamps = false;

    public $table = 'qcsoft_app_bundle';

    public $hasMany = [
        'bundle_products' => [BundleProduct::class, 'delete' => true],
    ];

    public $morphOne = [
        'page' => [Page::class, 'name' => 'owner', 'delete' => true],
        'catalogitem' => [Catalogitem::class, 'name' => 'item', 'delete' => true],
    ];

}
