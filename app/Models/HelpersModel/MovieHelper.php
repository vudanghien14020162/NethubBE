<?php


namespace App\Models\HelpersModel;


use App\Helpers\ConstResponse;
use App\Models\Base\BaseDataResponse;
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

    public static function getMovieId($id){
        $movie = Movie::find($id);
        return $movie;
    }

    public static function getMovieByGenre($genre, $offset, $limit){
        $cache = self::getCache();
        $key_cache = ConstResponse::KEY_GET_MOVIE_BY_GENRE_ID . $genre->id. '_offset_' . $offset . '_limit_' . $limit;
        $datas = [];
        $datas = $cache->getData($key_cache);
        if(empty($data) || is_null($data)){
            $movies = Movie::query()
                ->join('avg_relations_movie_genre', 'movies.id', '=', 'avg_relations_movie_genre.movie_id')
                ->where('avg_relations_movie_genre.genre_id', $genre->id)
                ->where('movies.deleted', Movie::NOT_DELETED)
                ->where('movies.status', Movie::STATUS_ACTIVE)
                ->offset($offset)
                ->limit($limit)
                ->get();
            if(count($movies) > 0){
                foreach ($movies as $movie){
                    $movie = BaseDataResponse::baseMovie($movie);
                    $datas[] = $movie;
                }
                $cache->createData($key_cache, $datas);
            }
        }
        return $datas;
    }

    public static function movieLinkById($movie_id){
        $movie = self::getMovieById($movie_id);
        return $movie;
    }



}
