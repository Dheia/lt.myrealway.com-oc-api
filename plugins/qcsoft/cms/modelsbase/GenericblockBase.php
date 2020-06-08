<?php namespace Qcsoft\Cms\Modelsbase;

use October\Rain\Database\Model;

/**
 * Class GenericblockBase
 * @package Qcsoft\Cms\Modelsbase
 * @property int $id
 * @property int $parent_id
 * @property string $owner_type
 * @property int $owner_id
 * @property int $sort_order
 * @property string $partial
 * @property string $type
 * @property string $subtype
 * @property string $code
 * @property string $tag
 * @property string $link
 * @property string $link_type
 * @property string $link_obj_id
 * @property string $name
 * @property string $sdesc
 * @property string $content
 * @property string $extra_fields
 */
class GenericblockBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_cms_genericblock';

    public $hasMany = [
    ];

    public $belongsTo = [
    ];

}
