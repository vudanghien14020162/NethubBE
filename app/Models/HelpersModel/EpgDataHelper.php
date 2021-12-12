<?php


namespace App\Models\HelpersModel;


use App\Helpers\ConstResponse;
use App\Models\Base\BaseDataResponse;
use App\Models\EpgData;
use App\Models\Genre;
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

    public static function getEpgByGenre($genre, $offset, $limit){
        $cache = self::getCache();
        $key_cache = ConstResponse::KEY_EPG_BY_GENRE_ID . $genre->id . '_offset_' . $offset . '_limit_' . $limit;
        $datas = $cache->getData($key_cache);
        $date = date("Y-m-d H:i:s");
        if(is_null($datas) || empty($datas)){
            $epg_datas = EpgData::query()
                ->where('genre_id', $genre->id)
                ->where('featured', Genre::STATUS_ACTIVE)
                ->where('program_end', ">=", $date)
                ->orderBy('program_start', 'asc')
                ->offset($offset)
                ->limit($limit)
                ->get();
            if(count($epg_datas) > 0){
                foreach ($epg_datas as $epg_data){
                    $epg = BaseDataResponse::baseEpg($epg_data);
                    $datas[] = $epg;
                }
            }
            $cache->createData($key_cache, $datas);
        }
        return $datas;
    }

    public static function getLiveToVod(){

    }

}