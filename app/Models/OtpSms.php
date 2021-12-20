<?php


namespace App\Models;
use App\Helpers\CacheCommon;
use App\Helpers\ConstResponse;
use App\Helpers\Response;
use App\Models\HelpersModel\WhiteListUserHelper;
use App\Traits\SingletonCache;

class OtpSms extends BaseModel
{
    use SingletonCache;
    protected $table = 'otp_sms';
    protected $fillable = [
        'id',
        'user_id',
        'phone_number',
        'otp_code',
        'expired_at',
    ];
    public static function createOtp($phone_number, $expire_at){
        $cache                      = SingletonCache::instance();
        $key                        = ConstResponse::KEY_GET_OTP_BY_USER . $phone_number;
        $check_otp_count            = $cache->getData($key);
        if($check_otp_count < ConstResponse::CCU_MAX_OTP){
            $check_otp_count += 1;
            if(WhiteListUserHelper::isDefaultOtp($phone_number)){
                $check_otp_count = 1;
            }
        }
//        return $data;
    }
}
