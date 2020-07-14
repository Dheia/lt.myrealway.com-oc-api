<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;
use October\Rain\Database\Traits\Sluggable;
use Qcsoft\App\Models\Bundle;
use Qcsoft\App\Models\Genericblock;
use Qcsoft\App\Models\Genericpage;
use Qcsoft\App\Models\Product;

/**
 * Class PageBase
 * @package Qcsoft\App\Modelsbase
 * @property string $custom_h1_title
 * @property string $custom_seo_title
 * @property Genericblock $genericblocks
 * @property int $id
 * @property mixed $owner
 * @property int $owner_id
 * @property string $owner_type
 * @property string $path
 * @property string $seo_desc
 */
class PageBase extends Model
{
    use Sluggable;

    public $slugs = ['path' => 'owner.name'];

    public $timestamps = false;

    public $table = 'qcsoft_app_page';

    public $morphTo = [
        'owner' => [],
    ];

    public $morphMany = [
        'genericblocks' => [Genericblock::class, 'name' => 'owner', 'delete' => true],
    ];

}
