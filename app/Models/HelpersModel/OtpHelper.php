<?php


namespace App\Models\HelpersModel;


use App\Helpers\ConstResponse;
use App\Models\Genre;
use App\Models\RelationMenuGenre;

class OtpHelper extends BaseHelper
{

    public static function getGenreById($genre_id){
        $cache = self::getCache();
        $key_cache = ConstResponse::KEY_GET_GENRE_BY_ID . $genre_id;
        $data = $cache->getData($key_cache);
        if(empty($data) || is_null($data)){
            $data = Genre::find($genre_id);
            $cache->createData($key_cache, $data);
        }
        return $data;
    }
}
