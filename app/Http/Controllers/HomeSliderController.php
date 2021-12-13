<?php


namespace App\Http\Controllers;


use App\Helpers\BaseResponse;
use App\Helpers\Response;
use App\Models\HelpersModel\ConfigHelper;
use App\Models\HelpersModel\HomeSliderHelper;
use Illuminate\Http\Request;

class HomeSliderController extends Controller
{
    public function index(Request $request, $menu_id){
        $msg  = 'Thành công!';
        $data = HomeSliderHelper::getSliderByMenu($menu_id);
        return BaseResponse::responseRequestJsonSuccess($data, $msg);
    }
}
