<?php namespace Qcsoft\Shop\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\Shop\Models\BundleProduct;
use October\Rain\Database\Collection;

/**
 * Class BundleBase
 * @package Qcsoft\Shop\Modelsbase
 * @property int $id
 * @property string $name
 * @property string $path
 * @property string $custom_h1_title
 * @property string $custom_seo_title
 * @property string $seo_desc
 * @property string $description
 * @property int $default_price
 * @property Collection $bundle_products;
 */
class BundleBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_shop_bundle';

    public $hasMany = [
        'bundle_products' => [BundleProduct::class],
    ];

    public $belongsTo = [
    ];

}
