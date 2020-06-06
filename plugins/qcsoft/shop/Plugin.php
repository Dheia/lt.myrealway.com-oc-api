<?php namespace Qcsoft\Shop;

use October\Rain\Database\Relations\Relation;
use Qcsoft\Shop\Components\ProductList;
use Qcsoft\Shop\Components\Cart;
use Qcsoft\Shop\Models\Bundle;
use Qcsoft\Shop\Models\Category;
use Qcsoft\Shop\Models\Product;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            ProductList::class    => 'productList',
            Cart::class           => 'cart',
        ];
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
        Relation::morphMap([
            'bundle'  => Bundle::class,
            'product' => Product::class,
        ]);

        \Event::listen('qcsoft.app.registerPageTypes', function () {
            return [
                Product::class,
                Category::class,
            ];
        });
    }

}
