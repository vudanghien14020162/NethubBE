<?php


namespace App\Http\Controllers;


use App\Helpers\BaseResponse;
use App\Models\HelpersModel\GenreHelper;
use App\Models\HelpersModel\MovieHelper;
use App\Models\HelpersModel\RelationHelper;
use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EpgDataApiController extends Controller
{
    public function getEpgOfDate(Request $request, $title, $date){
        $msg = "Thành công.";
        $page = $request->has('page') ? $request->get('page') : 1;
        $title =  strtoupper($title);
        $epg_channels = MovieHelper::getMovieByTitle($title);
        $listDates = array();
        if($date == 0){
            $today = Carbon::now('Asia/Ho_Chi_Minh');
            $epg_data = $epg_channels->data($today->toDateString())->get()->toArray();
            $date_timestamp = $today->subDays(7);
            for($i = 0; $i < 10; $i++){
                $date_timestamp = date('Y-m-d 00:00:00', strtotime($date_timestamp . ' + 1 day'));
                $listDates[] = strtotime($date_timestamp);
            }
            $json_data = array('epg_data', $epg_data, 'timestamp' => $listDates);

        }else{
            $dateRequest = Carbon::createFromTimestamp($date)->format('Y-m-d');
            $epg_data = $epg_channels->date($dateRequest)->get()->toArray();
            $json_data = array('epg_data' => $epg_data, 'timestamp' => $listDates);
        }
        return BaseResponse::responseRequestJsonSuccess($json_data, $msg);
    }

}
