<?php


namespace App\Http\Controllers;


use App\Helpers\BaseResponse;
use App\Models\HelpersModel\GenreHelper;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index(Request $request, $genre_id){
        $limit = $request->has('per_page') ? $request->get('per_page', 0) : 10;
        $page = $request->has('page') ? $request->get('page', 0) : 0;
        $offset = ($page - 1) >= 0 ? ($page - 1) * $limit : 0;
        $genre = GenreHelper::getGenreById($genre_id);
        $data = GenreHelper::getDataByGenreId($genre, $offset, $limit);
        $msg = 'Thành công!';
        return BaseResponse::responseRequestJsonSuccess($data, $msg);
    }

}