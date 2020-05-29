<?php namespace Ydnnov\Catalog\Modelsbase;

use October\Rain\Database\Model;
use Ydnnov\Catalog\Models\BundleProduct;
use October\Rain\Database\Collection;

/**
 * Class BundleBase
 * @package Ydnnov\Catalog\Modelsbase
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $price_override
 * @property Collection $bundle_products;
 */
class BundleBase extends Model
{
    public $timestamps = false;

    public $table = 'ydnnov_catalog_bundle';

    public $hasMany = [
        'bundle_products' => [BundleProduct::class],
    ];

    public $belongsTo = [
    ];

}
