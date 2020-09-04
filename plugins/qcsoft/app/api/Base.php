<?php namespace Qcsoft\App\Api;

use Qcsoft\App\Models\Bundle;
use Qcsoft\App\Models\BundleProduct;
use Qcsoft\App\Models\Catalogitem;
use Qcsoft\App\Models\Catalogitem as CatalogitemModel;
use Qcsoft\App\Models\CatalogitemFilteroption;
use Qcsoft\App\Models\CatalogitemRelevantitem;
use Qcsoft\App\Models\Category;
use Qcsoft\App\Models\Filter;
use Qcsoft\App\Models\Filteroption;
use Qcsoft\App\Models\Page;
use Qcsoft\App\Models\Product;

class Base
{
    public function map()
    {
        $filteroptions = Filteroption::all();

        $cacheRecords = [];

        $table = (new CatalogitemFilteroption)->getTable();

        \DB::setFetchMode(\PDO::FETCH_COLUMN);

        foreach ($filteroptions as $filteroption)
        {
            $catalogitems = \DB::table($table)
                ->where('filteroption_id', $filteroption->id)
                ->select('catalogitem_id as id')
                ->get();

            dump($catalogitems);
            die;
        }

        dump($filteroptions->toArray());
        die;
        return 'qwqwwq';
    }

    public function all()
    {
        $result = [
            'qwer' => 'asdf'
            //            'count/all'                     => (new Count())->all(),
            //            'bundle/base'                   => $this->bundles(),
            //            'bundle_product/base'           => $this->bundles_products(),
            //            'catalogitem/base'              => (new \Qcsoft\App\Api\Catalogitem())->base(),
            //            'catalogitem_relevantitem/base' => $this->catalogitems_relevantitems(),
            //            'category/base'                 => $this->categories(),
            //            'filter/base'                   => $this->filters(),
            //            'filteroption/base'             => $this->filteroptions(),
            //            'catalogitem_filteroption/base' => $this->catalogitems_filteroptions(),
            //            'page/base'                     => $this->pages(),
            //            'product/base'                  => $this->products(),
        ];

        return $result;
    }

    public function bundles()
    {
        $result = Bundle::select([
            'id',
            'default_price',
        ])->get();

        return $result;
    }

    public function bundles_products()
    {
        $result = BundleProduct::select([
            'id',
            'bundle_id',
            'product_id',
            'quantity',
            'sort_order',
            'price_override',
        ])->get();

        return $result;
    }

    public function base()
    {
        $result = CatalogitemModel::select([
            'id',
            'name',
            'owner_type',
            'owner_id',
            'default_price',
        ])->get();

        return $result;
    }

    public function catalogitems()
    {
        $result = Catalogitem::select([
            'id',
            'item_type',
            'item_id',
            'main_category_id',
            'name',
        ])->get();

        return $result;
    }

    public function catalogitems_relevantitems()
    {
        $result = CatalogitemRelevantitem::get();

        return $result;
    }

    public function categories()
    {
        $result = Category::select([
            'id',
            'parent_id',
            'name',
        ])->get();

        return $result;
    }

    public function filters()
    {
        $result = Filter::select([
            'id',
            'name',
        ])->get();

        return $result;
    }

    public function filteroptions()
    {
        $result = Filteroption::select([
            'id',
            'filter_id',
            'name',
        ])->get();

        return $result;
    }

    public function catalogitems_filteroptions()
    {
        $result = CatalogitemFilteroption::select([
            'id',
            'filteroption_id',
            'catalogitem_id',
        ])->get();

        return $result;
    }

    public function pages()
    {
        $result = Page::select([
            'id',
            'owner_type',
            'owner_id',
            'path',
        ])->get();

        return $result;
    }

    public function products()
    {
        $result = Product::select([
            'id',
            'default_price',
        ])->get();

        return $result;
    }

    public function relevant_counts()
    {
        $sql = <<<EOT
select main_catalogitem_id as id,
       count(relevant_catalogitem_id) as count
from qcsoft_app_catalogitem_relevantitem
group by main_catalogitem_id
EOT;

        $result = \DB::select($sql);

        return $result;
    }

    public function bundle_products_count()
    {
        $sql = <<<EOT
select bundle_id as id,
       count(product_id) as count
from qcsoft_app_bundle_product
group by bundle_id
EOT;

        $result = \DB::select($sql);

        return $result;
    }

}
