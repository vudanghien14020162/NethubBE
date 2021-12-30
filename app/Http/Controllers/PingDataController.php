<?php


namespace App\Http\Controllers;


use App\Helpers\BaseResponse;
use App\Helpers\ConstResponse;
use App\Helpers\TokenValidator;
use App\Models\HelpersModel\FingerprintHelper;
use App\Models\HelpersModel\GenreHelper;
use App\Models\HelpersModel\MovieHelper;
use App\Models\HelpersModel\UserHelper;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\Request;
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
        }
        $check_device = UserHelper::checkMaxCCU($user, $movie, $deviceId);
        $msg = 'Thành công!';
        return BaseResponse::responseRequestJsonSuccess($data, $msg);
    }

}
