<?php


namespace App\Http\Controllers;


use App\Helpers\BaseResponse;
use App\Models\HelpersModel\GenreHelper;
use App\Models\HelpersModel\MovieHelper;
use App\Models\Movie;
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

    public function movieInfo(Request $request, $movie_id){
        $msg = "Thành công.";
        $data = MovieHelper::getMovieId($movie_id);
        return BaseResponse::responseRequestJsonSuccess($data, $msg);
    }

    public function movieLink(Request $request, $movie_id){
        $msg = 'Thành công.';
        $data = MovieHelper::movieLinkById($movie_id);
        return BaseResponse::responseRequestJsonSuccess($data, $msg);
    }

    public function allMovie(Request $request, $movie_id){
        $msg = "Thành công.";
    }

}
