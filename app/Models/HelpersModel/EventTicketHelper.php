<?php


namespace App\Models\HelpersModel;


use App\Helpers\ConstResponse;
use App\Models\Base\BaseDataResponse;
use App\Models\EventTicket;
use App\Models\EventUserTicket;
use App\Models\Movie;
use App\Traits\SingletonCache;

class EventTicketHelper extends BaseHelper
{
    public static function getArrayEventCodeVip($event){
        $cache = self::getCache();
        $key = ConstResponse::KEY_ARRAY_EVENT_TICKET_VIP . $event->code;
        $data = $cache->getData($key);
        if(empty($data) || is_null($data)){
            $data = EventTicket::query()
                ->where('event_code', $event->code)
                ->where('is_vip', 1)
                ->pluck('code')
                ->toArray();
            $cache->createData($key, $data);
        }
        return $data;

    }

    public static function firstEventUserTicket($user, $arr_ticket = []){
        $cache = self::getCache();
        $key = ConstResponse::KEY_GET_EVENT_USER_TICKET . $user->id;
        $data = $cache->getData($key);
        if(empty($data) || is_null($data)){
            $data = EventUserTicket::query()
                ->where('user_id', $user->id)
                ->where('status', EventUserTicket::STATUS_ACTIVE)
                ->where('deleted', EventUserTicket::NOT_DELETED)
                ->whereIn('package_code', $arr_ticket)
                ->first();
            $cache->createData($key, $data);
        }
        return $data;
    }

    public static function getArrayEventCodeNormal($event){
        $cache = self::getCache();
        $key = ConstResponse::KEY_ARRAY_EVENT_TICKET_NORMAL . $event->code;
        $data = $cache->getData($key);
        if(empty($data) || is_null($data)){
            $data = EventTicket::query()
                ->where('event_code', $event->code)
                ->where('is_vip', 0)
                ->where('parent_id', '!=', 0)
                ->pluck('code')
                ->toArray();
            $cache->createData($key, $data);
        }
        return $data;
    }

    public static function findTicketByCode($code){
        $cache = self::getCache();
        $key   = ConstResponse::api_get_ticket_code . $code;
        $data = $cache->getData($key);
        if(empty($data)){
            $data = EventTicket::where('code', $code)->first();
            $cache->createData($key, $data);
        }
        return $data;
    }

}
