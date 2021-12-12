<?php


namespace App\Models\HelpersModel;
use App\Helpers\ConstResponse;
use App\Models\Ads;
use App\Models\Base\BaseDataResponse;

class AdsHelper extends BaseHelper
{
    public static function getBannerByGenre($genre){
        $cache = self::getCache();
        $key_cache = ConstResponse::KEY_ADS_BY_GENRE_ID . $genre->id;
        $datas=[];
        $datas = $cache->getData($key_cache);
        if(is_null($datas) || empty($datas)){
            $ads = Ads::query()
                ->where('genre_id', $genre->id)
                ->where('type', Ads::ADS_GENRE)
                ->get();
            if(count($ads) > 0){
                foreach ($ads as $ad){
                    $datas[] = BaseDataResponse::baseAds($ad);
                }
            }
            $cache->createData($key_cache, $datas);
        }
        return $datas;
    }
}
