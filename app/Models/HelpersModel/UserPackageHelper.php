<?php


namespace App\Models\HelpersModel;
use App\Models\UserPackage;
class UserPackageHelper extends BaseHelper
{
    public static function getMaxDeviceCCU($user){
        $data = UserPackage::query()
            ->where('user_id', $user->id)
            ->where('status', UserPackage::STATUS_ACTIVE)
            ->where('deleted', UserPackage::NOT_DELETED)
            ->orderBy('max_device_login', 'desc')
            ->first();
        return $data;
    }
}
