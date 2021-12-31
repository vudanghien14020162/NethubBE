<?php


namespace App\Models\HelpersModel;


use App\Helpers\ConstResponse;
use App\Models\Base\BaseDataResponse;
use App\Models\HistoryLogin;
use App\Models\Movie;

class HistoryLoginHelper extends BaseHelper
{
    public static function getHistoryLoginCount($user_id){
        $data = HistoryLogin::query()
            ->where('user_id', $user_id)
            ->where('status', HistoryLogin::STATUS_ACTIVE)
            ->where('deleted', HistoryLogin::NOT_DELETED)
            ->count();
        return $data;
    }
    public static function getHistoryDeviceFirst($user_id, $device_id){
        $data = HistoryLogin::query()
            ->where('user_id', $user_id)
            ->where('device_id', $device_id)
            ->where('status', HistoryLogin::STATUS_ACTIVE)
            ->where('deleted', HistoryLogin::NOT_DELETED)
            ->first();
        return $data;
    }
}
