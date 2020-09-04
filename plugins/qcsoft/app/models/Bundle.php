<?php namespace Qcsoft\App\Models;

use Qcsoft\App\Modelsbase\BundleBase;
use System\Models\File;

class Bundle extends BundleBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

    public function getH1TitleAttribute()
    {
        return $this->page->custom_h1_title ?: $this->catalogitem->name;
    }

    public function getNameAttribute()
    {
        return $this->catalogitem_name;
    }

    public function setNameAttribute($value)
    {
        return $this->catalogitem_name = $value;
    }

    public function getCatalogitemPriceAttribute($value)
    {
        return $value / 100;
    }

    public function setCatalogitemPriceAttribute($value)
    {
        return $this->attributes['catalogitem_price'] = $value * 100;
    }

    public function getSumPriceAttribute()
    {
        return $this->bundle_products->reduce(function ($prev, BundleProduct $bundleProduct)
        {
            return $prev + ($bundleProduct->product->default_price * $bundleProduct->quantity);
        }, 0);
    }

}
