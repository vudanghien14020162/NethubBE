<?php


namespace App\Helpers;


class ConstResponse
{
    const SUCCESS                                       = 200;
    const ERROR                                         = 400;
    const KEY_CACHE_TIME                                = 5 * 60 * 60;
    const KEY_CACHE_PATH                                = 'COMMON_CACHE:';
    const KEY_GET_CONFIG                                = 'get_config';
    const KEY_GET_MENU                                  = 'get_menu';
    const KEY_SLIDER_BY_MENU                            = 'get_slider_by_menu_id: ';
    const KEY_GET_MOVIE_BY_ID                           = 'get_movie_by_id:';
    const KEY_GET_GENRE_BY_MENU_ID                      = 'get_movie_by_id:';
}
