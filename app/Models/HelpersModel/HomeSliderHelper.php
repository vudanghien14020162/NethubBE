<?php


namespace App\Models\HelpersModel;


use App\Helpers\ConstResponse;
use App\Models\Base\BaseDataResponse;
use App\Models\HomeSlider;

class HomeSliderHelper extends BaseHelper
{
    public static function getSliderByMenu($menu_id, $offset, $limit){
        $cache = self::getCache();
        $key = ConstResponse::KEY_SLIDER_BY_MENU . $menu_id .'_offset_' . $offset . '_limit_' . $limit;
        $datas = [];
        $datas = $cache->getData($key);
        if(count($datas) == 0){
            $sliders = HomeSlider::query()
                ->where('status', HomeSlider::STATUS_ACTIVE)
                ->where('deleted', HomeSlider::NOT_DELETED)
                ->where('menu_id', $menu_id)
                ->orderBy('position', 'desc')
                ->limit($limit)
                ->offset($offset)
                ->get();
            if(count($sliders) > 0){
                foreach ($sliders as $slider){
                    $class = BaseDataResponse::baseSlider($slider);
                    if($class){
                        $datas[] = $class;
                    }
                }
            }
            $cache->createData($key, $datas);
        }
        return $datas;
    }

}