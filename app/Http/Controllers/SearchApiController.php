<?php


namespace App\Http\Controllers;


use App\Helpers\BaseResponse;
use App\Helpers\ConstResponse;
use App\Models\Base\BaseDataResponse;
use App\Models\Genre;
use App\Models\HelpersModel\ConfigHelper;
use App\Models\HelpersModel\GenreHelper;
use App\Models\HelpersModel\MenuHelper;
use App\Models\HelpersModel\MovieHelper;
use App\Models\HelpersModel\NotificationHelper;
use App\Models\HelpersModel\RelationHelper;
use App\Models\Notification;
use App\Models\RelationMovieGenre;
use Illuminate\Http\Request;

class SearchApiController extends Controller
{
    public function searchTrendingSuggest(Request $request){
        $msg = 'Thành công.';
        return BaseResponse::responseRequestJsonSuccess(null, $msg);

        $msg = 'Thành công.';
        $movies = MovieHelper::searchTrendingSuggest();
        return BaseResponse::responseRequestJsonSuccess($movies, $msg);

    }

    public function searchTab(Request $request){
        $msg = 'Thành công.';
        return BaseResponse::responseRequestJsonSuccess(null, $msg);
    }

    public function searchSuggestion(Request $request){
        $msg = 'Thành công.';
        return BaseResponse::responseRequestJsonSuccess(new \stdClass(), $msg);

        $keyword = $request->search_string;
        $msg = 'Thành công.';
        $movies = MovieHelper::searchSuggestion($keyword);
    }
}
