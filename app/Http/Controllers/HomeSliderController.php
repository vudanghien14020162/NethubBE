<?php


namespace App\Http\Controllers;


use App\Helpers\BaseResponse;
use App\Models\HelpersModel\HomeSliderHelper;
use Illuminate\Http\Request;

class HomeSliderController extends Controller
{
    public function index(Request $request, $menu_id){
        $msg  = 'Thành công!';
        $limit = $request->has('per_page') ? $request->get('per_page', 10) : 0;
        $page = $request->has('page') ? $request->get('page', 10) : 0;
        $offset = ($page > 0) ? ($page-1) * $limit : 0;
        $datas = HomeSliderHelper::getSliderByMenu($menu_id, $offset, $limit);
        return BaseResponse::responseRequestJsonSuccess($datas, $msg);
    }
}
