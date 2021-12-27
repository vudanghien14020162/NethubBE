<?php


namespace App\Models\HelpersModel;


use App\Helpers\ConstResponse;
use App\Models\OtpSms;
use App\Traits\SingletonCache;
use Carbon\Carbon;
class OtpHelper extends BaseHelper
{
    public static function createOtp($phone_number, $expire_at){
        $cache                      = SingletonCache::instance();
        $key                        = ConstResponse::KEY_GET_OTP_BY_USER . $phone_number;
        $check_otp_count            = $cache->getData($key);
        if($check_otp_count < ConstResponse::CCU_MAX_OTP){
            $check_otp_count += 1;
            if(WhiteListUserHelper::isDefaultOtp($phone_number)){
                $check_otp_count = 1;
            }
            $expired_time = Carbon::now('Asia/Ho_Chi_Minh')->addMinutes($expire_at)->format('Y-m-d H:i:s');
            $cache->createData($key, $check_otp_count, $expired_time);
            $otp = rand(1000, 9999);
            $checkOtp = OtpSms::where('phone_number', $phone_number)->where('otp_code', $otp)->first();
            if($checkOtp){
                $otp = rand(1000, 9999);
            }
            if(WhiteListUserHelper::isDefaultOtp($phone_number)){
                $otp = 1234;
            }
            $user_otp = OtpSms::firstOrNew([
                'phone_number' => $phone_number
            ]);
            $user_otp->otp_code = $otp;
            $user_otp->expired_at = $expired_time;
            $user_otp->save();
            return $otp;
        }
        return null;
    }
}
