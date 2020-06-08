<?php namespace Qcsoft\Shop;

use October\Rain\Database\Relations\Relation;
use Qcsoft\Shop\Components\Cart;
use Qcsoft\Shop\Components\ProductList;
use Qcsoft\Shop\Models\Bundle;
use Qcsoft\Shop\Models\Category;
use Qcsoft\Shop\Models\Customer;
use Qcsoft\Shop\Models\Customergroup;
use Qcsoft\Shop\Models\Product;
use RainLab\User\Models\User;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            ProductList::class => 'productList',
            Cart::class        => 'cart',
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

        \Event::listen('qcsoft.cms.registerPageTypes', function () {
            return [
                Product::class,
                Category::class,
            ];
        });

        User::extend(function (User $user) {
            $user->bindEvent('model.afterCreate', function () use ($user) {

                /** @var Customergroup $defaultCustomergroup */
                $defaultCustomergroup = Customergroup::where('is_default', true)->first();

                $customer = new Customer();

                $customer->group_id = $defaultCustomergroup->id;
                $customer->user_id = $user->id;

                $customer->save();
            });
        });

    }

}
