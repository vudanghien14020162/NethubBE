<?php


namespace App\Models\Base;


use App\Models\EpgData;
use App\Models\HelpersModel\EpgDataHelper;
use App\Models\HelpersModel\MovieHelper;
use App\Models\HomeSlider;
use Carbon\Carbon;

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

    public static function baseSlider($slider, $option=[]){
        if(empty($slider) || is_null($slider)) return null;
        if(is_array($slider)) $slider = (object)$slider;
        $title = '';
        $movie_id = 0;
        $data_info = null;
        if(in_array($slider->content_type, [HomeSlider::TYPE_LIVE, HomeSlider::TYPE_VOD])) {
            $movie = isset($slider->movie) ? $slider->movie : null;
            $title = isset($movie->title) ? $movie->title : null;
            $movie_id = isset($movie->movie_id) ? $movie->movie_id : 0;
        }elseif ($slider->content_type == HomeSlider::TYPE_PAY){
            $title = 'Thanh toán';
        }elseif ($slider->content_type == HomeSlider::TYPE_EVENT){
            $event = isset($slider->event) ? $slider->event : null;
            $title = isset($event->name) ? $event->name : null;
            $movie_id = isset($event->movie_id) ? $event->movie_id : 0;
            $data_info = self::baseEvent($event, ['image_mobile' => true, 'image' => true, 'list_button' => true, 'movie_id' => true, 'event_content'=> true]);
        }elseif ($slider->content_type == HomeSlider::TYPE_EPG){
            $epg = EpgDataHelper::getDataEpgByOption($slider->content_id);
            if(!$epg){
                return false;
            }
            $movie = MovieHelper::getMovieById($epg->channels_id);
            if(isset($movie)){
                $slider->channels_id = (int)$movie->id;
                $slider->channel_name = $movie->title;
                $slider->program_start = $epg->program_start;
                $slider->program_end = $epg->program_end;
            }else{
                $slider->channels_id = null;
                $slider->channel_name = null;
                $slider->program_start = null;
                $slider->program_end = null;
            }
            $title = isset($epg->title) ? $epg->title : '';
            $movie_id = isset($slider->content_id) ? $slider->content_id : 0;
        }
        $data = [
            'id'    => $slider->id,
            'title' => $title,
            'movie_id' => $movie_id,
            'slide_image' => $slider->image,
            'type' => isset($slider->content_type)?$slider->content_type:2,
            'channels_id' => isset($slider->channels_id)?$slider->channels_id:null,
            'channel_name' => isset($slider->channel_name)?$slider->channel_name:null,
            'program_start' => isset($slider->program_start)?$slider->program_start:null,
            'program_end' => isset($slider->program_end)?$slider->program_end:null,
            'data_info' => $data_info
        ];
        return $data;
    }

    public static function baseEvent($event, $options = []){
        if (empty($event) || empty($event)) {
            return false;
        }
        if (is_array($event)) $event = (object)$event;
        $data = [
            "event_code"        => isset($event->code)?$event->code:0,
            "event_name"        => isset($event->name)?$event->name:'',
            "start_time"        => isset($event->start_time)?date('Y-m-d H:i:s', strtotime($event->start_time)):date('Y-m-d H:i:s'),
            "end_time"          => isset($event->end_time)?date('Y-m-d H:i:s', strtotime($event->end_time)):date('Y-m-d H:i:s'),
        ];
        if(isset($options['movie_id']) && $options['movie_id']){
            $data["movie_id"] = isset($event->movie_id)?$event->movie_id:'';
        }
        if(isset($options['image']) && $options['image']){
            $data["image"] = isset($event->image)?$event->image:'';
        }
        if(isset($options['image_mobile']) && $options['image_mobile']){
            $data["image_mobile"] = isset($event->image_mobile)?$event->image_mobile:'';
        }
        if(isset($options['list_button']) && $options['list_button']){
            $data["list_button_name"] = ['Xem Sự Kiện'];
        }
        if(isset($options['event_content']) && $options['event_content']){
            $data["event_content"] = isset($event->content)?$event->content:'';
        }
        return $data;
    }

    public static function baseEpg($epg, $options = []){
        if(is_null($epg) || empty($epg)){
            return false;
        }
        if(is_array($epg)) $epg = (object)$epg;
        $data = [
            'id'                => $epg->id,
            'channels_id'       => $epg->channels_id,
            'channel_name'      => $epg->channel_name,
            'title'             => $epg->title,
            'program_start'     => $epg->program_start,
            'program_end'       => $epg->program_end,
            'thumbnail'         => $epg->image_event,
            'poster'            => $epg->image_event
        ];
        return $data;
    }

    public static function baseAds($ad, $option = []){
        if(is_null($ad) || empty($ad)){
            return false;
        }
        if(is_array($ad)) $ad = (object) $ad;
        $data = [
            'id' => $ad->id,
            'ad_image_tv' => $ad->image,
            'ad_image_mobile' => $ad->image,
            'ad_target' => null
        ];
        return !empty($data) ? $data : null;
    }

}
