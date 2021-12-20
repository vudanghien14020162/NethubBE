<?php


namespace App\Http\Controllers;


use App\Helpers\BaseResponse;
use App\Models\Base\BaseDataResponse;
use App\Models\HelpersModel\ConfigHelper;
use App\Models\HelpersModel\MenuHelper;
use App\Models\HelpersModel\NotificationHelper;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function listNotification(Request $request){
        $msg  = 'Thành công!';
        $limit = $request->has('per_page') ? $request->get('per_page') : 10;
        $page = $request->has('page') ? $request->get('page') : 0;
        $offset = ($page - 1) > 0 ? ($page - 1) * $limit : 0;
        $datas = NotificationHelper::getNotification($offset, $limit);
        return BaseResponse::responseRequestJsonSuccess($datas, $msg);
    }

    public function countNotification(Request $request){
        $msg = 'Thành công!';
        $data = NotificationHelper::countNotification();
        return BaseResponse::responseRequestJsonSuccess($data, $msg);
    }
}
