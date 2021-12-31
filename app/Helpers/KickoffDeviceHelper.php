<?php


namespace App\Helpers;

use App\Models\HelpersModel\HistoryLoginHelper;
use App\Models\HelpersModel\UserPackageHelper;
use App\Models\HelpersModel\WatchHistoryHelper;
use App\Models\HelpersModel\WhiteListUserHelper;
use App\Models\HistoryLogin;
use Illuminate\Http\Request;

class KickoffDeviceHelper
{
    public static function checkKickoffDevice($user){
        $device_id = \request()->header('deviceId', "");
        $kickoff_device = 0;
        $max_device = KickoffDeviceHelper::countMaxDevice($user);
        $kickoff_message = "Tài khoản $user->moble đã đăng nhập vượt quá $max_device thiết bị.
         Vui lòng nhập mã OTP để xóa 1 thiết bị cũ nhất đã đăng nhập.";
        if(WhiteListUserHelper::isVipMobile($user->mobile)){
            $kickoff_device = 0;
            return [
                'kickoff_device' => $kickoff_device,
                'kickoff_message' => $kickoff_message,
                'max_device' => $max_device
            ];
        }
        $count_list_device = HistoryLoginHelper::getHistoryLoginCount($user->id);
        $checkActive = false;
        if(!empty($device_id)){
            $checkActive = HistoryLoginHelper::getHistoryDeviceFirst($user->id, $device_id);
        }
        if($checkActive){
            if($count_list_device > $max_device){
                $kickoff_device = 1;
            }
        }
        else{
            if($count_list_device >= $max_device){
                $kickoff_device = 1;
            }
        }
        return [
            'kickoff_device' => $kickoff_device,
            'kickoff_message' => $kickoff_message,
            'max_device' => $max_device
        ];
    }

    public static function countMaxDevice($user){
        $max_device = 3;
        $checkUserPackage = UserPackageHelper::getMaxDeviceLogin($user);
        if($checkUserPackage){
            $max_device = $checkUserPackage->max_device_login;
        }
        return $max_device;
    }
}
