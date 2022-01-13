<?php


namespace App\Models\HelpersModel;
use App\Events\ZaloAddTicketEvent;
use App\Helpers\CacheCommon;
use App\Helpers\ConstResponse;
use App\Models\Ads;
use App\Models\Base\BaseDataResponse;
use App\Models\BaseModel;
use App\Models\Event;
use App\Models\EventTicket;
use App\Traits\SingletonCache;
use mysql_xdevapi\CollectionModify;

class EventHelper extends BaseHelper
{
    public static function getFirstEvents(){
        $cache = self::getCache();
        $key = ConstResponse::$api_popup_event;
        $event = $cache->getData($key);
        if(empty($event)){
            $event = Event::query()
                ->with('eventTicket')
                ->where('status', BaseModel::STATUS_ACTIVE)
                ->where('deleted', BaseModel::NOT_DELETED)
                ->where('default', 1)
                ->first();
            $cache->createData($key, $event);
        }
        $data = null;
        if($event){
            $data = BaseDataResponse::baseEvent($event, ['image_mobile' => true, 'image' => true, 'list_button' => true, 'movie_id' => true, 'event_content' => true]);
        }
        return $data;
    }
    public static function checkMovieInEvent($id){
        $cache = self::getCache();
        $key = ConstResponse::$api_check_movie_in_event . $id;
        $data = $cache->getData($key);
        if(empty($data)){
            $data = Event::query()
                ->where('movie_id', $id)
                ->where('status', BaseModel::STATUS_ACTIVE)
                ->where('deleted', BaseModel::NOT_DELETED)
                ->where('default', 1)
                ->first();
            $cache->createData($key, $data);
        }
        return $data;
    }
//    public static function findEventByCode($code){
//        $cache = self::getCache();
//        $key = ConstResponse::
//    }
    public static function addPromotion($user){
        //add gói sự kiện cho khách hàng trong thời gian khuyen mai
        if(time() >= strtotime(Event::PROMOTION_START_TIME) && time() <= strtotime(Event::PROMOTION_END_TIME)){
            $event_data = self::findEventByCode(Event::EVENT_CODE_SHOW_1);
            $ticket = EventTicketHelper::findTicketByCode(EventTicket::ZPAVG_SHOW_01_TICKET_01);
            if($ticket && $event_data){
                \event(new ZaloAddTicketEvent($user, $ticket, $event_data, 1));
            }
        }
    }

    public static function findEventByCode($code){
        $cache = self::getCache();
        $key   = ConstResponse::api_get_event_code . $code;
        $datas = $cache->getData($key);
        if(empty($datas) || is_null($datas)){
            $datas = Event::where('code', $code)->first();
            $cache->createData($key, $datas);
        }
        return $datas;
    }


}
