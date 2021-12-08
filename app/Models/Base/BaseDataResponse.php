<?php


namespace App\Models\Base;


class BaseDataResponse
{
    public static function baseMenu($menu, $options = []){
        if(empty($menu) || is_null($menu)){
            return null;
        }
        $platform = request()->header('platform', 0);
        $version = request()->header('version', 0);
        if(is_array($menu)) $menu = (object)$menu;
        if(($platform == "smart_tv") && ((float)$version > 0)){
            $image_device_90 =  isset($menu->image_device_90)?$menu->image_device_90.'?width=510&height=310&type=resize&ver=6':'';
        }else{
            $image_device_90 =  isset($menu->image_device_90)?$menu->image_device_90:'';
        }
        $data = [
            'id' => isset($menu->id) ? $menu->id : 0,
            'parent_id' => isset($menu->parent_id) ? $menu->parent_id : 0,
            'name' => isset($menu->name) ? $menu->name : 0,
            'slug' => isset($menu->slug)?$menu->slug:'',
            'image_device_30' => isset($menu->image_device_30) ? $menu->image_device_30 : 0,
            'image_device_60' => isset($menu->image_device_60) ? $menu->image_device_60 : 0,
            'image_device_90' => $image_device_90
        ];
        return $data;
    }

}
