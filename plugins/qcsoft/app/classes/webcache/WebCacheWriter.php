<?php namespace Qcsoft\App\Classes\Webcache;

use Qcsoft\App\Classes\Webcache\Handler\Bundle;
use Qcsoft\App\Classes\Webcache\Handler\Genericpage;
use Qcsoft\App\Classes\Webcache\Handler\Product;
use Qcsoft\App\Models\Page;

class WebCacheWriter
{
    public function write($offset, $limit)
    {
        $pages = Page::orderBy('id')->skip($offset)->take($limit)->get();

        $ownerMap = [
            'genericpage' => Genericpage::class,
            'product'     => Product::class,
            'bundle'      => Bundle::class,
        ];

        foreach ($pages as $page)
        {
            $ownerClass=$ownerMap[$page->owner_type];

//            $handler=new $ownerClass()

            dump($page->toArray());
            dump($page->owner->toArray());
            die;
        }

        return count($pages);
    }

}
