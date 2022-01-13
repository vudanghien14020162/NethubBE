<?php


namespace App\Http\Controllers;


use App\Helpers\BaseResponse;
use App\Helpers\ConstResponse;
use App\Helpers\JsonResponseCode;
use App\Helpers\KickoffDeviceHelper;
use App\Helpers\Response;
use App\Models\HelpersModel\FCMTokenMappingHelper;
use App\Models\HelpersModel\GenreHelper;
use App\Models\HelpersModel\HistoryLoginHelper;
use App\Models\UserSubscription;
use Illuminate\Http\Request;

class DeviceDataApiController extends Controller
{
    public function getDeviceByUser(Request $request){
        $userId = JsonResponseCode::getUserToken($request);
        if($userId == null){
            $msg = ConstResponse::$message_login;
            return
                BaseResponse::responseRequestJsonError(ConstResponse::$error_response_code_no_login, null, $msg);
        }
        $logs = HistoryLoginHelper::getDeviceByUser($userId);
        if(count($logs) > 0){
            foreach ($logs as $log){
                $log->device = ($log->browser != '0') ? $log->device . ' - '. $log->browser : $log->device;
            }
        }
        $msg = 'Thành công.';
        return BaseResponse::responseRequestJsonSuccess($logs, $msg);
    }

    public function deleteDevice(Request $request, $id){
        $userId = JsonResponseCode::getUserToken($request);
        if($userId == null){
            $msg = 'Không tìm thấy thông tin User';
            return BaseResponse::responseRequestJsonError(null, $msg);
        }else{
            $deviceById = HistoryLoginHelper::getHistoryDeviceFirst($id, $userId);
            if(!$deviceById){
                $msg = 'Lỗi nhập liệu. Vui lòng thử lại.';
                return BaseResponse::responseRequestJsonError(null, $msg);
            }
            if($deviceById->deleted){
                $msg = 'Dữ liệu không còn đăng nhập hệ thống.';
                return BaseResponse::responseRequestJsonError(null, $msg);
            }

            $fcm = FCMTokenMappingHelper::getFCMTokenMapping($userId, $deviceById->device_id);
            if($fcm){
                $fcm->status = 0;
                $fcm->save();
            }
            $deviceById->deleted = true;
            $deviceById->save();
            $msg = 'Xóa thành công.';
            return BaseResponse::responseRequestJsonSuccess(null, $msg);
        }

    }


}
