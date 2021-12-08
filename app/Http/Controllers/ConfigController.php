<?php


namespace App\Http\Controllers;


use App\Helpers\BaseResponse;
use App\Helpers\Response;
use App\Models\HelpersModel\ConfigHelper;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function setting(Request $request){
        $msg  = 'Thành công!';
        $data = ConfigHelper::getConfig();
        return BaseResponse::responseRequestJsonSuccess($data, $msg);
    }
}
