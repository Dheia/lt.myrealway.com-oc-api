<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Collection;
use October\Rain\Database\Model;
use Qcsoft\App\Models\Coupon;

/**
 * Class CoupontypeBase
 * @package Qcsoft\App\Modelsbase
 * @property Collection $coupons
 * @property int $id
 * @property string $name
 */
class CoupontypeBase extends Model
{
    public static $type_id;

    public $timestamps = false;

    public $table = 'qcsoft_app_coupontype';

    public $hasMany = [
        'coupons' => [Coupon::class, 'delete' => false],
    ];

}
