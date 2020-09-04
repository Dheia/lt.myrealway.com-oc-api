<?php namespace Qcsoft\App\Api;

use Qcsoft\App\Models\Bundle;
use Qcsoft\App\Models\BundleProduct;
use Qcsoft\App\Models\Catalogitem;
use Qcsoft\App\Models\CatalogitemFilteroption;
use Qcsoft\App\Models\CatalogitemRelevantitem;
use Qcsoft\App\Models\Category;
use Qcsoft\App\Models\Filter;
use Qcsoft\App\Models\Filteroption;
use Qcsoft\App\Models\Page;
use Qcsoft\App\Models\Product;

class Count
{
    public function all()
    {
        $result = [
            'bundle'                   => Bundle::count(),
            'bundle_product'           => BundleProduct::count(),
            'catalogitem'              => Catalogitem::count(),
            'catalogitem_relevantitem' => CatalogitemRelevantitem::count(),
            'filter'                   => Filter::count(),
            'filteroption'             => Filteroption::count(),
            'catalogitem_filteroption' => CatalogitemFilteroption::count(),
            'page'                     => Page::count(),
            'product'                  => Product::count(),
        ];

        return $result;
    }

}
