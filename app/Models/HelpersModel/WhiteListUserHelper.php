<?php


namespace App\Models\HelpersModel;

use App\Models\WhiteListUser;
use Illuminate\Support\Facades\Log;

class WhiteListUserHelper extends BaseHelper
{
    public static function isDefaultOtp($mobile){
        $check = WhiteListUser::query()
            ->where('mobile', $mobile)
            ->where('is_otp_default', 1)
            ->where('status', WhiteListUser::STATUS_ACTIVE)
            ->first();
        if($check){
            Log::info('Thue bao nay: ' . $mobile . ' default otp');
            return true;
        }
        return false;
    }

    public static function isVipMobile($mobile){
        $check = WhiteListUser::query()
            ->where('mobile', $mobile)
            ->where('is_free_vip', 1)
            ->where('status', WhiteListUser::STATUS_ACTIVE)
            ->first();
        if($check){
            Log::info('Thue bao: ' . $mobile . ' la thue bao vip');
            return true;
        }
        return false;
    }
}
