<?php


namespace App\Models\HelpersModel;


use App\Helpers\BaseResponse;
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

    public static function getMovieWatching($user_id, $offset, $limit){
        $list = WatchHistoryHelper::userWatchingMovies($user_id, $offset, $limit);
        return $list;
    }

    public static function searchTrendingSuggest(){
        $movies = Movie::query()
            ->where('status', Movie::STATUS_ACTIVE)
            ->where('deleted', Movie::NOT_DELETED)
            ->where('live', Movie::IS_LIVE)
            ->where('parent_id', null)
            ->where(function ($query){
                $query->where('movies.drm_type', '<>', '1')
                    ->orWhere('movies.drm_type', null);
            })
            ->orderBy('id', 'desc')
            ->limit(20)
            ->get();
        $datas = null;
        foreach ($movies as $movie){
            $datas[] = [
                'id' => isset($movie->id) ? (int)$movie->id : 0,
                'title' => isset($movie->title) ? $movie->title:'',
                'live' => isset($movie->live) ? (int)$movie->live:'',
                'thumbnail' => isset($movie->thumbnail) ? $movie->thumbnail : '',
                'poster' => isset($movie->poster) ? $movie->poster : '',
                'description' => isset($movie->description) ? $movie->description : ''
            ];
        }
        return $datas;
    }

    public static function searchSuggestion($keyword){
        $movies = Movie::query()
            ->where('status', Movie::STATUS_ACTIVE)
            ->where('deleted', Movie::NOT_DELETED)
            ->where('parent_id', null)
            ->where(function ($query){
                $query->where('movies.drm_type', '<>', '1')
                    ->orWhere('movies.drm_type', null);
            })
            ->where('title', 'like', '%' . $keyword . '%')
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
        $datas = null;
        foreach ($movies as $movie){
            $datas[] = [
                'id' => isset($movie->id) ? (int) $movie->id : 0,
                'title' => isset($movie->title) ? $movie->title : '',
                'live' => isset($movie->live) ? $movie->live: '',
                'thumbnail' => isset($movie->thumbnail) ? $movie->thumbnail : '',
                'poster' => isset($movie->poster) ? $movie->poster : '',
                'description' => isset($movie->description) ? $movie->description : ''
            ];
        }
        return $datas;
    }

    public static function getMovieByTitle($title){
        $cache = self::getCache();
        $key = ConstResponse::KEY_CACHE_GET_MOVIE_BY_TITLE . $title;
        $data = $cache->getData($key);
        if(empty($data) || is_null($data)){
            $data = Movie::query()
                ->where('title', 'like', '%' , $title , '%')
                ->where('status', Movie::STATUS_ACTIVE)
                ->where('deleted', Movie::NOT_DELETED)
                ->first();
            ;
            $data = $cache->createData($key, $data);
        }
        return $data;
    }
}
