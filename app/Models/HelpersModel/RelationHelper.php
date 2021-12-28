<?php


namespace App\Models\HelpersModel;
use App\Helpers\ConstResponse;
use App\Models\Actor;
use App\Models\Director;
use App\Models\Movie;
use App\Models\RelationActorMovie;
use App\Models\RelationMovieGenre;

class RelationHelper extends BaseHelper
{
    public static function getActorByMovie($movie_id){
        $data = Actor::query()
            ->select('actors.id', 'actors.name')
            ->join('avg_relations_movie_actor', 'actors.id', '=', 'avg_relations_movie_actor.actor_id')
            ->where('avg_relations_movie_actor.movie_id', $movie_id)
            ->where('actors.deleted', Actor::NOT_DELETED)
            ->get();
        return $data;
    }

    public static function getDirectorByMovie($movie_id){
        $data = Director::query()
            ->select('director.id', 'director.name')
            ->join('avg_relations_movie_director','director.id', '=', 'avg_relations_movie_director.director_id')
            ->where('avg_relations_movie_director.movie_id', $movie_id)
            ->where('avg_relations_movie_director.deleted', Director::NOT_DELETED)
            ->get();
        return $data;
    }

    public static function getFirstGenre($movie_id){
        $cache = self::getCache();
        $key = ConstResponse::KEY_GET_FIRST_GENRE . $movie_id;
        $data = $cache->getData($key);
        if(empty($data) || is_null($data)){
            $data = RelationMovieGenre::query()->where('movie_id', $movie_id)->first();
            $cache->createData($key, $data);
        }
        return $data;
    }
}
