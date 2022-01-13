<?php


namespace App\Http\Controllers;


use App\Helpers\BaseResponse;
use App\Helpers\ConstResponse;
use App\Models\Base\BaseDataResponse;
use App\Models\Genre;
use App\Models\HelpersModel\ConfigHelper;
use App\Models\HelpersModel\GenreHelper;
use App\Models\HelpersModel\MenuHelper;
use App\Models\HelpersModel\NotificationHelper;
use App\Models\HelpersModel\RelationHelper;
use App\Models\Notification;
use App\Models\RelationMovieGenre;
use Illuminate\Http\Request;

class RecommendApiController extends Controller
{
    public function getRecommend(Request $request, $id){
        $datas = new \stdClass();
        $firstGenre = RelationHelper::getFirstGenre($id);
        if($firstGenre){
            $genre_id = isset($firstGenre->genre_id) ? $firstGenre->genre_id : 0;
            $genre = GenreHelper::getGenreById($genre_id);
            if($genre){
                $datas = GenreHelper::getListMoviesGenre($firstGenre, 16, 0);
            }
        }
        $msg = "Thành công.";
        return BaseResponse
            ::responseRequestJsonSuccess($datas, $msg);
    }
}
