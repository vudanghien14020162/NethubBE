<?php


namespace App\Models\HelpersModel;


use App\Models\HomeSlider;

class HomeSliderHelper extends BaseHelper
{
    public static function getSliderByMenu($menu_id){
        $sliders = HomeSlider::query()
            ->where('menu_id', $menu_id)
            ->where('status', HomeSlider::STATUS_ACTIVE)
            ->where('deleted', HomeSlider::NOT_DELETED)
            ->get();
        return $sliders;
    }
}
