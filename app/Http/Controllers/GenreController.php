<?php


namespace App\Http\Controllers;


use App\Helpers\BaseResponse;
use App\Models\Base\BaseDataResponse;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index(Request $request, $menu_id){
        $data = Genre::query()->get();
        $msg = 'Thành công!';
        return BaseResponse::responseRequestJsonSuccess($data, $msg);
    }

}