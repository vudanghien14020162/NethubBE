<?php


namespace App\Models\HelpersModel;
use App\Helpers\DeviceManager;
use App\Models\EventTicket;
use App\Models\GenreSetting;
use App\Models\UserPackage;
class UserPackageHelper extends BaseHelper
{

    public static function userCanWatch($user, $package_id = 0){
        $canWatch = false;
        if($package_id == 1){
            return true;
        }
        if(WhiteListUserHelper::isVipMobile($user->mobile)){
            $canWatch = true;
        }
        $checkUserPackage = self::getUserPackage($user->id, $package_id);
        if($checkUserPackage) $canWatch = true;
        return $canWatch;
    }

    private static function getUserPackage($user_id, $package_id){
        $data = UserPackage::query()
            ->where('user_id', $user_id)
            ->where('parent_package_id', $package_id)
            ->where('end_time', '>=', now())
            ->first();
        return $data;
    }

    public static function checkMaxCCU($user, $movie, $device_id){
        $check_device = true;
        if(WhiteListUserHelper::isVipMobile($user->mobile)){
            $check_device = true;
            return $check_device;
        }
        $checkUserPackage = self::getMaxDeviceCCU($user);
        if($checkUserPackage){
            $group_name = $checkUserPackage->parent_package_code;
            $max_device = $checkUserPackage->max_device_ccu;
            if(!is_null($max_device) && $max_device > 0){
                $helper_device_manager = new DeviceManager();
                $check_device = $helper_device_manager
                    ->setUser($user->id)
                    ->setMovieId($movie->id)
                    ->setGroupName($group_name)
                    ->setMaxDevice($max_device)
                    ->setDeviceLogon($device_id)
                    ->store();
            }
        }
        return $check_device;
    }
    public static function getMaxDeviceCCU($user){
        $data = UserPackage::query()
            ->where('user_id', $user->id)
            ->where('status', UserPackage::STATUS_ACTIVE)
            ->where('deleted', UserPackage::NOT_DELETED)
            ->orderBy('max_device_ccu', 'desc')
            ->first();
        return $data;
    }

    public static function getMaxDeviceLogin($user){
        $data = UserPackage::query()
            ->where('user_id', $user->id)
            ->where('status', UserPackage::STATUS_ACTIVE)
            ->where('deleted', UserPackage::NOT_DELETED)
            ->orderBy('max_device_login', 'desc')
            ->first();
        return $data;
    }

    public static function userCanWatchLiveEvent($user, $event){
        /*
        0 => Vé thường
        1 => Vé vip || Thue Bao Vip || Ngay xem free
        -1 => Không có vé
        */
        $check = -1;
        if(WhiteListUserHelper::isVipMobile($user->mobile) || self::checkUserVipWatchLiveStream($user, $event) || GenreSettingHelper::getDataByField(GenreSetting::FREE_TO_VIEW_FLAG)){
            $check = 1;
        }elseif(self::checkUserNormalVipWatchLiveStream($user, $event)){
            $check = 0;
        }
        return $check;
    }

    public static function checkUserVipWatchLiveStream($user, $event){
        $check = false;
        $arr_vip_ticket = EventTicketHelper::getArrayEventCodeVip($event);
        if(count($arr_vip_ticket) > 0){
            $user_event_ticket = EventTicketHelper::firstEventUserTicket($event, $arr_vip_ticket);
            if($user_event_ticket){
                $check = true;
            }
        }
        return $check;
    }

    public static function checkUserNormalVipWatchLiveStream($user, $event){
        $check = false;
        $arr_normal_ticket = EventTicketHelper::getArrayEventCodeNormal($event);
        if(count($arr_normal_ticket) > 0){
            $user_event_ticket = EventTicketHelper::firstEventUserTicket($user, $arr_normal_ticket);
            if($user_event_ticket){
                $check = true;
            }
        }
        return $check;
    }
}
