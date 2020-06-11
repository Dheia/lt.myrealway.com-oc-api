<?php namespace Qcsoft\Myrealway\Formwidgets;

use Backend\Classes\FormWidgetBase;
use Backend\Widgets\Lists;
use October\Rain\Database\Builder;
use Qcsoft\Shop\Models\Product;

class BundleProductList extends FormWidgetBase
{
    protected $defaultAlias = 'bundleProductList';

    public function __construct($controller, $formField, $configuration = [])
    {
        parent::__construct($controller, $formField, $configuration);

        $this->addCss(['style.scss']);
        $this->addJs('script.js');

        $listConfig = \Yaml::parseFile(plugins_path(
            'qcsoft/myrealway/formwidgets/bundleproductlist/available-items-list.yaml'
        ));

        $listConfig['recordOnClick'] = 'vbus.app["' . $this->getId() . '"].onSelectProduct(:id)';
        $listConfig['model'] = new Product();

        $listWidget = new Lists($this->controller, $listConfig);

        $listWidget->bindToController();

        $listWidget->bindEvent('list.extendQueryBefore', function (Builder $query) {
            $query->with('main_image');
            $query->whereNotIn('id', \Request::input('existingItems', []));
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

}
