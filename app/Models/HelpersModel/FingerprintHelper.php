<?php


namespace App\Models\HelpersModel;
use App\Helpers\ConstResponse;
use App\Models\Ads;
use App\Models\Base\BaseDataResponse;
use App\Models\Fingerprint;
use App\Models\Notification;

class FingerprintHelper extends BaseHelper
{
    public static function getFirstFingerprint($movie_id){
        $dataNow = date('Y-m-d H:i:s', strtotime(now('Asia/Ho_Chi_Minh')));
        $finger = Fingerprint::query()
            ->where('movie_id', $$movie_id)
            ->where("start_time", '>=', $dataNow)
            ->where('end_time', '<=', $dataNow)
            ->where('status', Fingerprint::STATUS_ACTIVE)
            ->where('deleted', Fingerprint::NOT_DELETED)
            ->first();
        if(!empty($finger) || !is_null($finger)){
            $datas = [
                'duration' => $finger->duration,
                'position' => $finger->position,
                'position_detail_x' => $finger->position_detail_x,
                'position_detail_y' => $finger->position_detail_y,
                'round_time' => $finger->round_time,
                'user_type_display' => $finger->user_type_display,
                'font_type_display' => $finger->font_type_display,
                'font_color' => $finger->font_clor
            ];
        }else{
            $datas = null;
        }
        return $datas;
    }
}
