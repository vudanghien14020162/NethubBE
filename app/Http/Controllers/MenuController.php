<?php


namespace App\Http\Controllers;


use App\Helpers\BaseResponse;
use App\Models\Base\BaseDataResponse;
use App\Models\HelpersModel\ConfigHelper;
use App\Models\HelpersModel\MenuHelper;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request){
        $msg  = 'Thành công!';
        $menus = MenuHelper::getMenu($request);
        $datas = [];
        if(count($menus) > 0){
            foreach ($menus as $menu){
                $data = BaseDataResponse::baseMenu($menu);
                $datas[] = $data;
            }
        }
        return BaseResponse::responseRequestJsonSuccess($datas, $msg);
    }
}
