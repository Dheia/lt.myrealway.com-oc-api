<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\App\Models\Coupontype;
use Qcsoft\App\Models\Person;

/**
 * Class CouponBase
 * @package Qcsoft\App\Modelsbase
 * @property int $id
 * @property mixed $issuer
 * @property int $issuer_id
 * @property string $issuer_type
 * @property string $title
 * @property Coupontype $type
 * @property int $type_id
 */
class CouponBase extends Model
{
    public static $type_id;

    public $timestamps = false;

    public $table = 'qcsoft_app_coupon';

    public $belongsTo = [
        'type' => [Coupontype::class],
    ];

    public $morphTo = [
        'issuer' => [],
    ];

}
