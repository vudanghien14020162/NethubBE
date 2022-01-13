<?php


namespace App\Http\Controllers;


use App\Events\WatchLogsEvent;
use App\Helpers\BaseResponse;
use App\Helpers\ConstResponse;
use App\Helpers\DeviceManager;
use App\Helpers\TokenValidator;
use App\Models\Base\BaseDataResponse;
use App\Models\HelpersModel\FingerprintHelper;
use App\Models\HelpersModel\GenreHelper;
use App\Models\HelpersModel\MovieHelper;
use App\Models\HelpersModel\UserHelper;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Psy\CodeCleaner\FinalClassPass;

class PingDataController extends Controller
{
    public function ping(Request $request, $menu_id){
        $stdClass = new \stdClass();
        $token = $request->header('Authorization');
        $deviceId = $request->header('deviceId');
        $movie_id = isset($request->movieId) ? $request->movieId : 0;
        $time = isset($request->duration) ? $request->duration : 0;
        if(empty($token) || empty($deviceId) || empty($movie_id)){
            $msg = ConstResponse::$message_login;
            return BaseResponse::responseRequestJsonError(ConstResponse::ERROR_LOGIN, $stdClass, $msg);
        }
        $checkToken = TokenValidator::checkAccessToken($request);
        if(!$checkToken){
            $msg = ConstResponse::$message_login;
            return BaseResponse::responseRequestJsonError(ConstResponse::ERROR_LOGIN, $stdClass, $msg);
        }
        $user_id = isset($checkToken['user_id']) ? $checkToken['user_id'] : 0;
        $user = User::find($user_id);
        if(!$user){
            $msg = ConstResponse::$message_login;
            return BaseResponse::responseRequestJsonError(ConstResponse::ERROR_LOGIN, $stdClass, $msg);
        }
        if($user->is_block == 1){
            //Block user do vi phạm bản quyền
            $msg = ConstResponse::$message_blocked_user;
            return BaseResponse::responseRequestJsonError(ConstResponse::ERROR_LOGIN, $stdClass, $msg);
        }
        $movie = MovieHelper::getMovieById($movie_id);
        $max_devices = null;
        $datas = [];
        if($movie){
            $finger = FingerprintHelper::getFirstFingerprint($movie_id);
            $finger ? $datas['fingerprint'] = $finger : $datas['fingerprint'] = null;
            if(!empty($finger) || !is_null($finger)){
                $datas["fingerprint"] = [
                    "duration"          => $finger->duration,
                    "position"          => $finger->position,
                    "position_detail_x" => $finger->position_detail_x,
                    "position_detail_y" => $finger->position_detail_y,
                    "round_time"        => $finger->round_time,
                    "user_type_display" => $finger->user_type_display,
                    "font_type_display" => $finger->font_type_display,
                    "font_color"        => $finger->font_color
                ];
            }else{
                $datas["fingerprint"] = null;
            }
        }
        //end check
        $check_device = UserHelper::checkMaxCCU($user, $movie, $deviceId);
        if(!$check_device){
            $msg = "Đã quá giới hạn thiết bị truy cập nội dung.";
            return BaseResponse
                ::responseRequestJsonError(ConstResponse::$error_group_max_device_user, $stdClass, $msg);
        }
        if($time != 0){
            try {
                event(new WatchLogsEvent($movie, $user, $deviceId, '', $time, ['updateWatchHistory' => true] ));
            }catch (\Exception $ex){
                Log::info("Watch logs event ex: " . $ex->getMessage());
            }
        }
        //update watch logs
        try{
            event(new WatchLogsEvent($movie, $user, $deviceId, '', '', ['updateEndtime' => true]));
        }catch (\Exception $ex){
            Log::info("Watch logs event ex: " . $ex->getMessage());
        }
        $msg = 'Thành công!';
        return BaseResponse::responseRequestJsonSuccess($datas, $msg);
    }

    public function deleteDeviceAvg(Request $request){
        $msg = "Thành công";
        $empty_class = new \stdClass();
        $movie_id = isset($request->movieId) ? (int) $request->movieId : 0;
        $time = isset($request->duration) ? $request->duration : 0;
        $user_id = 0;
        $token = $request->header('Authorization');
        if(empty($token) || isset($token)){
            $checkToken = TokenValidator::checkAccessToken($token);
            if(!$checkToken){
                return BaseResponse::responseRequestJsonSuccess($empty_class, $msg);
            }
            $user_id = isset($checkToken['user_id']) ? (int) $checkToken['user_id'] : 0;
        }
        $deviceId = $request->header('deviceId');
        if(empty($deviceId) || !isset($deviceId)){
            return BaseResponse::responseRequestJsonSuccess($empty_class, $msg);
        }
        $user = User::find($user_id);
        if(!$user){
            return BaseResponse::responseRequestJsonSuccess($empty_class, $msg);
        }
        $movie = Movie::find($movie_id);
        if($movie){
            $checkUserPackage = UserHelper::getMaxDeviceCCU($user);
            if($checkUserPackage){
                $group_name = $checkUserPackage->parent_package_code;
                $max_device = $checkUserPackage->max_device_ccu;
                if($max_device > 0){
                    $helper_device_manager = new DeviceManager();
                    $helper_device_manager->setUser($user_id)
                        ->setGroupName($group_name)
                        ->setMaxDevice($max_device)
                        ->setDeviceLogon($deviceId)
                        ->removeDevice();
                }
            }
            if($time != 0){
                try {
                    event(new WatchLogsEvent($movie, $user, $deviceId, '', $time, ['updateWatchHistory' => true]));
                }catch (\Exception $ex){
                    Log::info("Watch logs event ex: " . $ex->getMessage());
                }
            }
            //update watch logs
            try {
                event(new WatchLogsEvent($movie, $user, $deviceId, '', '', ['updateEndTime' => true]));
            }catch (\Exception $ex){
                Log::info("Watch logs event ex: " . $ex->getMessage());
            }
        }
        return BaseResponse::responseRequestJsonSuccess($empty_class, $msg);

    }

}
