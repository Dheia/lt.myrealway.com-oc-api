<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;

/**
 * Class OrderBase
 * @package Qcsoft\App\Modelsbase
 * @property string $accept_language
 * @property int $affiliate_id
 * @property string $comment
 * @property decimal $commission
 * @property timestamp $created_at
 * @property string $currency_code
 * @property int $currency_id
 * @property decimal $currency_value
 * @property string $custom_field
 * @property int $customer_group_id
 * @property int $customer_id
 * @property string $email
 * @property string $fax
 * @property string $firstname
 * @property string $forwarded_ip
 * @property int $id
 * @property int $invoice_no
 * @property string $invoice_prefix
 * @property string $ip
 * @property int $language_id
 * @property string $lastname
 * @property int $marketing_id
 * @property int $orderstatus_id
 * @property string $payment_address_1
 * @property string $payment_address_2
 * @property string $payment_address_format
 * @property string $payment_city
 * @property string $payment_code
 * @property string $payment_company
 * @property string $payment_country
 * @property int $payment_country_id
 * @property string $payment_custom_field
 * @property string $payment_firstname
 * @property string $payment_lastname
 * @property string $payment_method
 * @property string $payment_postcode
 * @property string $payment_zone
 * @property int $payment_zone_id
 * @property string $shipping_address_1
 * @property string $shipping_address_2
 * @property string $shipping_address_format
 * @property string $shipping_city
 * @property string $shipping_code
 * @property string $shipping_company
 * @property string $shipping_country
 * @property int $shipping_country_id
 * @property string $shipping_custom_field
 * @property string $shipping_firstname
 * @property string $shipping_lastname
 * @property string $shipping_method
 * @property string $shipping_postcode
 * @property string $shipping_zone
 * @property int $shipping_zone_id
 * @property int $store_id
 * @property string $store_name
 * @property string $store_url
 * @property string $telephone
 * @property decimal $total
 * @property float $total_points
 * @property string $tracking
 * @property timestamp $updated_at
 * @property string $user_agent
 * @property int $warehouse_id
 */
class OrderBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_app_order';

}
