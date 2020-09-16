<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\App\Models\Page;
use Qcsoft\App\Traits\CompositeModel;

/**
 * Class CustompageBase
 * @package Qcsoft\App\Modelsbase
 * @property string $code
 * @property string $content
 * @property int $id
 * @property string $name
 * @property Page $page
 */
class CustompageBase extends Model
{
    public static $type_id;

    use CompositeModel;

    public $compositeModel = [
        'page' => [],
    ];

    public $timestamps = false;

    public $table = 'qcsoft_app_custompage';

    public $morphOne = [
        'page' => [Page::class, 'name' => 'owner', 'delete' => true],
    ];

}
