<?php namespace Qcsoft\Shop\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\Shop\Models\Customergroup;
use Qcsoft\Shop\Models\Product;

/**
 * Class CustomergroupProductBase
 * @package Qcsoft\Shop\Modelsbase
 * @property int $id
 * @property int $customergroup_id
 * @property int $product_id
 * @property int $price
 * @property Customergroup $customergroup;
 * @property Product $product;
 */
class CustomergroupProductBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_shop_customergroup_product';

    public $hasMany = [
    ];

    public $belongsTo = [
        'customergroup' => [Customergroup::class],
        'product' => [Product::class],
    ];

}
