<?php


namespace App\Models\HelpersModel;


use App\Helpers\ConstResponse;
use App\Models\Base\BaseDataResponse;
use App\Models\EpgData;
use App\Models\HomeSlider;
use Carbon\Carbon;

class EpgDataHelper extends BaseHelper
{
    public static function getDataEpgByOption($id, $option = false){
       if($option){
           $epg = EpgData::where('id', $id)
               ->where('program_start', '>=', Carbon::now('Asia/Ho_Chi_Minh'))
               ->first();
       }else{
           $epg = EpgData::where('id', $id)->get();
       }
       return $epg;
    }

}