<?php namespace ApiHandler\Page;

use ApiResponse;
use ApiStorage;

class Product
{
    public function handle($page, $owner, ApiStorage $storage, ApiResponse $response)
    {
        $catalogitem = $response->addObject('catalogitem', $owner->catalogitem_id);
        $response->addImage('md', $catalogitem->main_image_id);

        foreach ($catalogitem->relevant_ids as $id)
        {
            $relevantItem = $response->addObject('catalogitem', $id);
            $relevantItemOwner = $response->addObject($relevantItem->item_type, $relevantItem->item_id);
            $relevantItemPage = $response->addObject('page', $relevantItemOwner->page_path);

            if ($relevantItem->item_type === 'bundle')
            {
                foreach ($relevantItemOwner->products as $bundleProductId => $value)
                {
                    $bundleProduct = $response->addObject('product', $bundleProductId);
                    $bundleProductCatalogitem = $response->addObject('catalogitem', $bundleProduct->catalogitem_id);
                    $bundleProductPage = $response->addObject('page', $bundleProduct->page_path);
                }
            }
        }
    }

}
