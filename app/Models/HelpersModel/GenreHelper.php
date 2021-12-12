<?php


namespace App\Models\HelpersModel;


use App\Helpers\ConstResponse;
use App\Models\Genre;
use App\Models\RelationMenuGenre;

class GenreHelper extends BaseHelper
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
    public static function getGenreByMenuId($id, $offset, $limit){
        $cache = self::getCache();
        $key_cache = ConstResponse::KEY_GET_GENRE_BY_MENU_ID . $id;
        $data = $cache->getData($key_cache);
        if(empty($data) || is_null($data)){
            $data = Genre::query()
                ->select('genres.id', 'genres.name', 'genres.genre_type', 'genres.relation_menu_id', 'genres.relation_genre_id', 'genres.position')
                ->join('avg_relations_menu_genre', 'avg_relations_menu_genre.genre_id', '=', 'genres.id')
                ->where('avg_relations_menu_genre.menu_id', $id)
                ->where('genres.deleted', Genre::NOT_DELETED)
                ->orderBy('avg_relations_menu_genre.position')
                ->get();
            $cache->createData($data, $key_cache);
        }
        return $data;
    }

    public static function getDataByGenreId($genre, $offset, $limit){
        $movies = null;
        $events = null;
        $banners = null;
        $genre_type = $genre->genre_type;
        switch ($genre_type){
            case Genre::GENRE_TYPE_EVENT:
                $events = EpgDataHelper::getEpgByGenre($genre, $offset, $limit);
                break;
            case Genre::GENRE_TYPE_BANNER:
                $banners = AdsHelper::getBannerByGenre($genre);
                break;
            case Genre::GENRE_LIVE_TO_VOD:
                $events = EpgDataHelper::getLiveToVod($offset, $limit);
                break;
        }
        $object = new \stdClass();
        $object->events = $events;
        $object->movies = $movies;
        $object->banners = $banners;
        return $object;
    }

}