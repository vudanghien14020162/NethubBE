<?php


namespace App\Http\Controllers;


use App\Helpers\BaseResponse;
use App\Models\HelpersModel\GenreHelper;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index(Request $request, $menu_id){
        $limit = $request->has('per_page') ? $request->get('per_page', 0) : 10;
        $page = $request->has('page') ? $request->get('page', 0) : 0;
        $offset = ($page - 1) >= 0 ? ($page - 1) * $limit : 0;
        $data = GenreHelper::getGenreByMenuId($menu_id, $offset, $limit);
        $msg = 'Thành công!';
        return BaseResponse::responseRequestJsonSuccess($data, $msg);
    }

}
