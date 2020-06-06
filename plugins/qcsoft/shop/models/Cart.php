<?php namespace Qcsoft\Shop\Models;

use Qcsoft\Shop\Modelsbase\CartBase;

class Cart extends CartBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

    protected $fillable = ['session_key'];

    /**
     * @return static
     * @throws \Exception
     */
    public static function fromSessionGetOrCreate()
    {
        if (!$cart_session_key = \Session::get('cart_session_key'))
        {
            for ($i = 0; $i < 5; $i++)
            {
                $cart_session_key = str_random(100);

                if ($cart = static::where('session_key', $cart_session_key)->first())
                {
                    $cart_session_key = null;
                }
                else
                {
                    break;
                }
            }

            if (!$cart_session_key)
            {
                throw new \Exception('Could not generate unique random cart session key. This should have never happened');
            }

            \Session::put('cart_session_key', $cart_session_key);
        }

        return static::firstOrCreate(['session_key' => $cart_session_key]);
    }

}
