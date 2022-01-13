<?php

namespace App\Http\Middleware;
use App\Helpers\DeviceManager;
use App\Helpers\TokenValidator;
use App\Models\HelpersModel\UserHelper;
use App\Models\User;
use Closure;
class DeletePing
{
    public function handle($request, Closure $next){
        $token = $request->header('Authorization');
        $checkToken = TokenValidator::checkAccessToken($token);
        if(!$checkToken){
            return $next($request);
        }else{
            $user_id = isset($checkToken['user_id']) ? (int) $checkToken['user_id'] : 0;
            $user = User::find($user_id);
            if(empty($device_id)){
                return $next($request);
            }else{
                //Call del Ping
                $device_id = $request->header('deviceId') ? $request->header('deviceId') : '';
                if(empty($device_id)){
                    return $next($request);
                }else{
                    //Call del Ping
                    $checkUserPackage = UserHelper::getMaxDeviceCCU($user);
                    if($checkUserPackage){
                        $group_name = $checkUserPackage->parent_package_code;
                        $max_device = $checkUserPackage->max_device_ccu;
                        if($max_device > 0){
                            $helper_device_manager = new DeviceManager();
                            $helper_device_manager->setUser($user_id)
                                ->setGroupName($group_name)
                                ->setMaxDevice($max_device)
                                ->setDeviceLogon($device_id)
                                ->removeDevice();
                        }

                    }
                    //del ping
                    return $next($request);
                }

            }
        }
    }
}
