<?php


namespace App\Models\HelpersModel;
use App\Models\Actor;
use App\Models\Director;
use App\Models\Movie;
use App\Models\RelationActorMovie;

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

}
