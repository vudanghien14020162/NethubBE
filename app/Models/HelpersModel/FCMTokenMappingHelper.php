<?php


namespace App\Models\HelpersModel;


use App\Helpers\ConstResponse;
use App\Models\Base\BaseDataResponse;
use App\Models\EpgData;
use App\Models\Genre;
use App\Models\HomeSlider;
use App\Models\MappingUidFcmToken;
use Carbon\Carbon;

class FCMTokenMappingHelper extends BaseHelper
{
    public static function getFCMTokenMapping($userId, $deviceId){
        $fcm = MappingUidFcmToken::query()
            ->where('user_id', $userId)
            ->where('device_id', $deviceId)
            ->first();
        return $fcm;
    }

}
