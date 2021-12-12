<?php


namespace App\Models\HelpersModel;


use App\Helpers\ConstResponse;
use App\Models\Movie;

class MovieHelper extends BaseHelper
{
    public static function getMovieById($id){
        $cache = self::getCache();
        $key_cache = ConstResponse::KEY_GET_MOVIE_BY_ID;
        $data = $cache->getData($key_cache);
        if(empty($data) || is_null($data)){
            $data = Movie::find($id);
            $cache->createData($data, $key_cache);
        }
        return $data;
    }




}