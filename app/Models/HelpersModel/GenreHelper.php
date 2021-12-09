<?php


namespace App\Models\HelpersModel;


use App\Helpers\ConstResponse;
use App\Models\Genre;
use App\Models\RelationMenuGenre;

class GenreHelper extends BaseHelper
{
    public static function getMovieById($id, $offset, $limit){
        $cache = self::getCache();
        $key_cache = ConstResponse::KEY_GET_GENRE_BY_MENU_ID . $id;
        $data = $cache->getData($key_cache);
        if(empty($data) || is_null($data)){
            $data = Genre::query()
                ->join(RelationMenuGenre::class, 'avg_relations_menu_genre.genre_id', '=', 'genres.id')
                ->where('avg_relations_menu_genre.menu_id', $id)
                ->where('genres.deleted', Genre::NOT_DELETED)
                ->orderBy('avg_relations_menu_genre.position')
                ->get();
            $cache->createData($data, $key_cache);
        }
        return $data;
    }

}