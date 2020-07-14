<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;
use October\Rain\Database\Traits\NestedTree;
use Qcsoft\App\Models\Page;
use System\Models\File;

/**
 * Class GenericblockBase
 * @package Qcsoft\App\Modelsbase
 * @property string $content
 * @property string $content_alt
 * @property string $extra_fields
 * @property string $fields
 * @property int $id
 * @property File $image
 * @property File $image_alt
 * @property File $image_icon
 * @property File $image_icon_alt
 * @property int $index
 * @property boolean $is_published
 * @property string $key
 * @property string $link
 * @property string $link_alt
 * @property int $link_alt_obj_id
 * @property string $link_alt_type
 * @property int $link_obj_id
 * @property string $link_type
 * @property int $nest_depth
 * @property int $nest_left
 * @property int $nest_right
 * @property mixed $owner
 * @property int $owner_id
 * @property string $owner_type
 * @property int $parent_id
 * @property string $partial
 * @property string $sdesc
 * @property string $sdesc_alt
 * @property string $subtype
 * @property string $title
 * @property string $title_alt
 * @property string $type
 */
class GenericblockBase extends Model
{
    use NestedTree;

    public $timestamps = false;

    public $table = 'qcsoft_app_genericblock';

    public $morphTo = [
        'owner' => [],
    ];

    public $attachOne = [
        'image' => [File::class],
        'image_alt' => [File::class],
        'image_icon' => [File::class],
        'image_icon_alt' => [File::class],
    ];

}
