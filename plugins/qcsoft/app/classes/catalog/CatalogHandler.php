<?php namespace Qcsoft\App\Classes\Catalog;

use Qcsoft\App\Models\Filter;
use Qcsoft\App\Models\Filteroption;

class CatalogHandler
{
    public function handle()
    {
        $input = \Request::input();

        $cquery = new CatalogQuery();

        $this->applyFilters($input, $cquery);

        $this->applySort($input, $cquery);

        include base_path('api/classes/ApiStorage.php');
        include base_path('api/classes/ApiResponse.php');

        $storage = \ApiStorage::getDefault();

        $response = new \ApiResponse($storage);

        foreach ($cquery->get() as $id)
        {
            $catalogitem = $response->addObject('catalogitem', $id);
            $owner = $response->addObject($catalogitem->item_type, $catalogitem->item_id);
            $page = $response->addObject('page', $owner->page_path);

            if ($catalogitem->main_image_id)
            {
                $response->addImage('md', $catalogitem->main_image_id);
            }
        }

        header('Access-Control-Allow-Origin: *');
        echo $response->json();

        die;
    }

    protected function applyFilters($input, $cquery)
    {
        $allFilters = Filter::all();
        $allOptions = Filteroption::all();

        foreach ($input as $key => $valueStr)
        {
            if (!$filterConfig = $allFilters->firstWhere('slug', $key))
            {
                continue;
            }

            $filter = $cquery->addOptionsFilter($filterConfig, $key);

            $slugs = explode(',', $valueStr);

            foreach ($slugs as $slug)
            {
                if ($optionConfig = $allOptions
                    ->where('filter_id', $filterConfig->id)
                    ->firstWhere('slug', $slug))
                {
                    $filter->addOption($optionConfig, $slug);
                }
            }
        }
    }

    protected function applySort($input, CatalogQuery $cquery)
    {
        $parts = explode(',', array_get($input, 'sort', ''));

        if (!in_array($parts[0], ['name', 'popularity', 'default_price']))
        {
            $parts[0] = 'name';
            $parts[1] = 'asc';
        }

        if (!in_array($parts[1], ['asc', 'desc']))
        {
            $parts[1] = 'asc';
        }

        $cquery->setSorting($parts[0], $parts[1]);
    }

}
