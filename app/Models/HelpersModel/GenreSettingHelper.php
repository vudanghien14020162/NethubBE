<?php


namespace App\Models\HelpersModel;
use App\Helpers\ConstResponse;
use App\Models\Actor;
use App\Models\Director;
use App\Models\GenreSetting;
use App\Models\Movie;
use App\Models\RelationActorMovie;
use App\Models\RelationMovieGenre;

class GenreSettingHelper extends BaseHelper
{
    public static function getDataByField($field){
        $cache = self::getCache();
        $key = ConstResponse::KEY_GET_FIELD_GENRE_SETTING . $field;
        $data = $cache->getData($key);
        if(empty($data) || is_null($data)){
            $data = GenreSetting::query()
                ->where('field', $field)
                ->first();
            $cache->createData($key, $data);
        }
        return isset($data) && isset($data->content) ? $data->content : 0;
    }
}
