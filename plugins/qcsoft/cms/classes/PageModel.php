<?php namespace Qcsoft\Cms\Classes;

/**
 * Trait PageModel
 * @package Qcsoft\Cms\Classes
 * @property string $name
 * @property string $path
 * @property string $custom_h1_title
 * @property string $custom_seo_title
 * @property string $seo_desc
 */
trait PageModel
{
    public function getH1TitleAttribute()
    {
        return $this->custom_h1_title ?: $this->name;
    }

    public function getSeoTitleAttribute()
    {
        return $this->custom_seo_title ?: $this->name;
    }

}
