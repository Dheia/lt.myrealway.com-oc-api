<?php namespace Ydnnov\Catalog\Modelsbase;

use October\Rain\Database\Model;
use Ydnnov\Catalog\Models\BundleProduct;

/**
 * Class BundleBase
 * @package Ydnnov\Catalog\Modelsbase
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $price_override
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
