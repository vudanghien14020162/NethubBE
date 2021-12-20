<?php


namespace App\Models;
use App\Models\HelpersModel\RelationHelper;

class Movie extends BaseModel
{
    protected $table = 'movies';

    public $attributes = [
        'user_rating',
        'actor',
        'director',
        'is_vip',
        'genre'
    ];

    public function getActorAttribute(){
        $movie_id = !empty($this->getAttribute('id')) ? $this->getAttribute('id') : 0;
        $datas = RelationHelper::getActorByMovie($movie_id);
        return $datas;
    }

    public function getDirectorAttribute(){
        $movie_id = !empty($this->getAttribute('id')) ? $this->getAttribute('id') : 0;
        $datas = RelationHelper::getDirectorByMovie($movie_id);
        return $datas;
    }

    public function getUserRatingAttribute(){
        $rating = !empty($this->getAttribute('rating')) ? $this->getAttribute('rating') : 0;
        return $rating;
    }

    public function getIsVipAttribute(){
        $is_vip = !empty($this->getAttribute('is_vip')) ? $this->getAttribute('is_vip') : 0;
        return $is_vip;
    }

}
