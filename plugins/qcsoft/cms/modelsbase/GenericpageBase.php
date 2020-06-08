<?php namespace Qcsoft\Cms\Modelsbase;

use October\Rain\Database\Model;

/**
 * Class GenericpageBase
 * @package Qcsoft\Cms\Modelsbase
 * @property int $id
 * @property string $name
 * @property string $path
 * @property string $custom_h1_title
 * @property string $custom_seo_title
 * @property string $seo_desc
 * @property string $content
 */
class GenericpageBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_cms_genericpage';

    public $hasMany = [
    ];

    public $belongsTo = [
    ];

}
