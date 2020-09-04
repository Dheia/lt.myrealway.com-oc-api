<?php namespace Qcsoft\App\Components;

use Cms\Classes\ComponentBase;
use Qcsoft\App\Models\Cart as CartModel;
use Qcsoft\App\Models\Cartitem;
use Qcsoft\App\Models\Catalogitem;

class Cart extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Cart',
        ];
    }

    public function onAddItem()
    {
        $cart = CartModel::fromSessionGetOrCreate();
\Debugbar::info(\Request::input('catalogitemId'));
        $catalogitem = Catalogitem::findOrFail(\Request::input('catalogitemId'));

        $cartItem = Cartitem::firstOrNew([
            'cart_id'       => $cart->id,
            'sellable_type' => 'catalogitem',
            'sellable_id'   => \Request::input('catalogitemId'),
        ]);

        $cartItem->quantity += \Request::input('quantity');

        $cartItem->save();

        return $this->onGetCurrent();
    }

    public function onGetCurrent()
    {
        $cart = CartModel::fromSessionGetOrCreate();

        $cartitems = $cart->cartitems()
            ->with(['sellable', 'sellable.main_image'])
            ->get()
            ->map(function ($item)
            {
                $mainImageUrl = $item->sellable->main_image ?
                    $item->sellable->main_image->getThumb(100, 100, ['mode' => 'crop']) : null;

                $result = $item->toArray();

                $result['sellable']['main_image'] = $mainImageUrl;

                // @todo When they bring laravel 6 to october, we'll be able to select only some
                // @todo columns in with(...) call, for now, we just unset unnecessary columns to
                // @todo at least avoid sending them over the web
                unset(
                    $result['sellable']['main_category_id'],
                    $result['sellable']['description'],
                    $result['sellable']['h1_title'],
                    $result['sellable']['seo_title'],
                    $result['sellable']['seo_desc'],
                );

                return $result;
            })
            ->all();

        $cart = $cart->toArray();

        $cart['total_count'] = array_reduce($cartitems, function ($count, $item)
        {
            return $count + $item['quantity'];
        }, 0);

        unset($cart['session_key'], $cart['customer_id']);

        $cart['cartitems'] = $cartitems;

        return $cart;
    }

}
