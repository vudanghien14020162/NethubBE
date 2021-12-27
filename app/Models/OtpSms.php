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
}
