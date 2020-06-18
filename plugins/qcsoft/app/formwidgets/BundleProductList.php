<?php namespace Qcsoft\App\Formwidgets;

use Backend\Classes\FormWidgetBase;
use Backend\Widgets\Lists;
use October\Rain\Database\Builder;
use Qcsoft\App\Models\BundleProduct;
use Qcsoft\App\Models\BundleProductCustomergroup;
use Qcsoft\App\Models\Customergroup;
use Qcsoft\App\Models\Product;

class BundleProductList extends FormWidgetBase
{
    protected $defaultAlias = 'bundleProductList';

    public $allCustomergroups;

    public function __construct($controller, $formField, $configuration = [])
    {
        parent::__construct($controller, $formField, $configuration);

//        $this->addJs('script.js');

        ////////////////////////////////////////////////////////////////////////////////
        /// Init product list for modal "product picker"
        ////////////////////////////////////////////////////////////////////////////////
        $listConfig = \Yaml::parseFile(plugins_path(
            'qcsoft/app/formwidgets/bundleproductlist/available-items-list.yaml'
        ));

        $listConfig['recordOnClick'] = 'vbus.app["' . $this->getId() . '"].$refs.bundleProductList.onSelectProduct(:id)';
        $listConfig['model'] = new Product();

        $listWidget = new Lists($this->controller, $listConfig);

        $listWidget->bindToController();

        $listWidget->bindEvent('list.extendQueryBefore', function (Builder $query)
        {
            $query->with('main_image');
            $query->whereNotIn('id', \Request::input('existingItems', []));
        });

        ////////////////////////////////////////////////////////////////////////////////
        /// Needed in more than one place
        ////////////////////////////////////////////////////////////////////////////////
        $this->allCustomergroups = Customergroup::all();

        ////////////////////////////////////////////////////////////////////////////////
        /// Bind save events to model to save json object as a set of related records
        ////////////////////////////////////////////////////////////////////////////////
        $this->model->bindEvent('model.saveInternal', function ()
        {
            unset($this->model->attributes[$this->fieldName]);
        });

        $this->model->bindEvent('model.afterSave', function ()
        {
            if ($bundleRequest = \Request::input($this->parentForm->arrayName))
            {
                $widgetSaveData = json_decode(array_get($bundleRequest, $this->fieldName, '{}'));

                if (object_get($widgetSaveData, 'status') === 'ok')
                {
                    $this->saveBundleProductListToRelation($widgetSaveData->items);
                }
            }
        });
    }

    public function render()
    {
        return $this->makePartial('default');
    }

    public function onGetProductData()
    {
        $product = Product::where('id', \Request::input('productId'))->first();

        $main_image = $product->main_image->getThumb(120, 120, ['mode' => 'crop']);

        $result = $product->toArray();

        $result['main_image'] = $main_image;

        return [
            'product' => $result,
        ];
    }

    public function onGetAvailableItems()
    {
        return $this->controller->widget->list->onRefresh();
    }

    public function getLoadValue()
    {
        $result = $this->model->bundle_products()
            ->with(['product', 'product.main_image', 'bundle_product_customergroups'])
            ->get()
            ->sortBy('sort_order')
            ->values()
            ->map(function ($item)
            {
                /** @var BundleProduct $item */

                $main_image = $item->product->main_image->getThumb(120, 120, ['mode' => 'crop']);

                $product = $item->product->only(['id', 'name', 'default_price']);

                $product['main_image'] = $main_image;

                $bpCustomergroups = $this->allCustomergroups
                    ->map(function ($customergroup) use ($item)
                    {
                        /** @var Customergroup $customergroup */

                        $result = [
                            'customergroup_id' => $customergroup->id,
                        ];

                        if ($itemBpcg = $item->bundle_product_customergroups
                            ->firstWhere('customergroup_id', $customergroup->id))
                        {
                            $result ['overrideDiscount'] = [
                                'value'     => $itemBpcg->discount_value,
                                'valueType' => $itemBpcg->discount_value_type,
                            ];
                        }

                        return $result;
                    });

                $result = $item->only(['id', 'quantity', 'price_override']);

                $result['product'] = $product;
                $result['bpCustomergroups'] = $bpCustomergroups;

                return $result;
            });

        return $result->toJson();
    }

    protected function saveBundleProductListToRelation($requestedItems)
    {
        $existingItems = $this->model->bundle_products()->with('bundle_product_customergroups')->get();

        $requestedItems = collect($requestedItems);

        // Delete items which aren't present in requested value
        foreach ($existingItems as $item)
        {
            if (!$requestedItems->firstWhere('product_id', $item->product_id))
            {
                $item->delete();
            }
        }

        // Insert new and update existing items
        $sort_order = 1;

        foreach ($requestedItems as $item)
        {
            if (!$saveItem = $existingItems->firstWhere('product_id', $item->product_id))
            {
                $saveItem = new BundleProduct();

                $saveItem->bundle_id = $this->model->id;
                $saveItem->product_id = $item->product_id;
            }

            $saveItem->quantity = $item->quantity;
            $saveItem->sort_order = $sort_order;
            $saveItem->price_override = $item->price_override;

            $saveItem->save();

            $sort_order++;

            $this->saveCustomergroupsOverrideDiscountToRelation($saveItem, $item);
        }
    }

    protected function saveCustomergroupsOverrideDiscountToRelation($existingBundleProduct, $requestedBundleProduct)
    {
        $requestedItems = collect($requestedBundleProduct->bpCustomergroups)
            ->filter(function ($item)
            {
                return strlen(object_get($item, 'overrideDiscount.value')) > 0
                    && strlen(object_get($item, 'overrideDiscount.valueType')) > 0;
            });

        foreach ($existingBundleProduct->bundle_product_customergroups as $existingItem)
        {
            if (!$requestedItems->firstWhere('customergroup_id', $existingItem->customergroup_id))
            {
                $existingItem->delete();
            }
        }

        foreach ($requestedItems as $requestedItem)
        {
            $existingItem = $existingBundleProduct->bundle_product_customergroups
                ->firstWhere('customergroup_id', $requestedItem->customergroup_id);

            if ($existingItem)
            {
                $saveItem = $existingItem;
            }
            else
            {
                $saveItem = new BundleProductCustomergroup();

                $saveItem->bundle_product_id = $existingBundleProduct->id;
                $saveItem->customergroup_id = $requestedItem->customergroup_id;
            }

            $saveItem->discount_value = $requestedItem->overrideDiscount->value;
            $saveItem->discount_value_type = $requestedItem->overrideDiscount->valueType;

            $saveItem->save();
        }
    }

}
