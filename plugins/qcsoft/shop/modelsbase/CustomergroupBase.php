<?php namespace Qcsoft\Shop\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\Shop\Models\Customer;
use October\Rain\Database\Collection;

/**
 * Class CustomergroupBase
 * @package Qcsoft\Shop\Modelsbase
 * @property int $id
 * @property string $name
 * @property Collection $customers;
 */
class CustomergroupBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_shop_customergroup';

    public $hasMany = [
        'customers' => [Customer::class],
    ];

    public $belongsTo = [
    ];

}
