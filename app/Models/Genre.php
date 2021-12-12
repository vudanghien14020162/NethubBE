<?php


namespace App\Models;


class Genre extends BaseModel
{
    protected $table = 'genres';
    const GENRE_TYPE_CHANNEL = 1;
    const GENRE_TYPE_MOVIE = 2;
    const GENRE_TYPE_TV = 3;
    const GENRE_TYPE_ACTOR = 4;
    const GENRE_TYPE_DIRECTOR = 5;
    const GENRE_TYPE_EVENT = 6;
    const GENRE_TYPE_BANNER = 7;
    const GENRE_TYPE_WATCHING = 11;
    const GENRE_TYPE_TOP = 12;
    const GENRE_LIVE_TO_VOD = 14;

}
