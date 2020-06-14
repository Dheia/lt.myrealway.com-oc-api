<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\App\Models\Customergroup;
use Qcsoft\App\Models\Product;

/**
 * Class CustomergroupProductBase
 * @package Qcsoft\App\Modelsbase
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

    public $table = 'qcsoft_app_customergroup_product';

    public $hasMany = [
    ];

    public $belongsTo = [
        'customergroup' => [Customergroup::class],
        'product' => [Product::class],
    ];

}
