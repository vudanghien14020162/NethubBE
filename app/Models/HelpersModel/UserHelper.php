<?php


namespace App\Models\HelpersModel;


use App\Helpers\ConstResponse;
use App\Helpers\DeviceManager;
use App\Helpers\JsonResponseCode;
use App\Models\Menu;
use App\Models\User;
use App\Traits\SingletonCache;
use Illuminate\Http\Request;

class UserHelper extends BaseHelper
{
    use SingletonCache;
    const STATUS_ACTIVE = 1;
    const NOT_DELETED   = 0;
    public static function getUserById($user_id){
        $cache = self::getCache();
        $key = ConstResponse::KEY_GET_USER_BY_ID . $user_id;
        $data = $cache->getData($key);
        if(empty($data) || is_null($data)){
            $data = User::query()
               ->where('id', $user_id)
                ->where('deleted', self::NOT_DELETED)
                ->where('status', self::STATUS_ACTIVE)
                ->get();
           $cache->createData($key, $data);
        }
        return $data;
    }

    public static function createUser($request){
        try {
            $user = new User([
                'name' => !empty($request->mobile) ? $request->mobile : null,
                'mobile' => !empty($request->mobile) ? $request->mobile: null,
                'password' => !empty($request->password) ? bcrypt($request->password) : null,
                'status' => 0
            ]);
        } catch (\Illuminate\Database\QueryException $exception) {
            return null;
        }
        return $user;
    }

    public static function checkMaxCCU($user, $movie, $deviceId){
        $check_device = true;
        if(WhiteListUserHelper::isVipMobile($user->mobile)){
            $check_device = true;
            return $check_device;
        }
        $checkUserPackage = UserPackageHelper::getMaxDeviceCCU($user);
        if($checkUserPackage){
            $group_name = $checkUserPackage->parent_pack_code;
            $max_device = $checkUserPackage->max_device_ccu;
            if(!is_null($max_device) && $max_device > 0){
                $helper_device_manager = new DeviceManager();
                $check_device = $helper_device_manager
                    ->setUser($user->id)
                    ->setMovieId($movie->id)
                    ->setGroupName($group_name)
                    ->setMaxDevice($max_device)
                    ->setDeviceLogon($deviceId)
                    ->store();
            }
        }
        return $check_device;
    }
    public static function getMaxDeviceCCU($user){
        $cache = SingletonCache::instance();
        $key = ConstResponse::KEY_MAX_DEVICE_CCU . $user->id;
        $data = $cache->getData($key);
        if(empty($data) || is_null($data)){
            $data = UserPackageHelper::getMaxDeviceCCU($user);
            $cache->createData($key, $data);
        }
        return $data;
    }
    public static function getMaxDeviceLogin($user){
        $cache = SingletonCache::instance();
        $key = ConstResponse::KEY_MAX_DEVICE_LOGIN . $user->id;
        $data = $cache->getData($key);
        if(empty($data) || is_null($data)){
            $data = UserPackageHelper::getMaxDeviceLogin($user);
            $cache->createData($key, $data);
        }
        return $data;
    }
}
