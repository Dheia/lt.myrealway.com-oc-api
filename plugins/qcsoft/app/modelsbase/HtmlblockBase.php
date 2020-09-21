<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\App\Models\Page;

/**
 * Class HtmlblockBase
 * @package Qcsoft\App\Modelsbase
 * @property string $fields
 * @property int $id
 * @property int $index
 * @property string $key
 * @property mixed $owner
 * @property int $owner_id
 * @property string $owner_type
 * @property string $partial
 * @property string $subtype
 * @property string $type
 */
class HtmlblockBase extends Model
{
    public static $type_id;

    public $timestamps = false;

    public $table = 'qcsoft_app_htmlblock';

    public $morphTo = [
        'owner' => [],
    ];

}
