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
    const KEY_GET_GENRE_BY_ID                           = 'get_genre_by_id:';
    const KEY_GET_GENRE_BY_MENU_ID                      = 'get_genre_by_menu_id:';
    const KEY_GET_DATA_BY_GENRE_ID                      = 'get_data_by_genre_id:';
    const KEY_EPG_BY_GENRE_ID                           = 'get_epg_by_genre_id:';
    const KEY_LIVE_TO_VOD_BY_GENRE_ID                   = 'get_live_to_vod_by_genre_id:';
    const KEY_ADS_BY_GENRE_ID                           = 'get_ads_by_genre_id:';
    const KEY_GET_MOVIE_BY_GENRE_ID                     = 'get_movie_by_genre_id:';
    const KEY_GET_USER_BY_ID                            = 'get_user_by_id:';
    const KEY_GET_OTP_BY_USER                           = 'get_otp_by_user:';
    const KEY_GET_FIRST_GENRE                           = 'get_first_genre:';
    const EXPIRE_TIME_OTP                               = 5;
    const CCU_MAX_OTP                                   = 3;

    public static $message_sms_otp_nethub               = " la ma OTP xac thuc tai khoan NETHub cua Quy Khach";
    public static $message_sms_password                 = " la mat khau mac dinh dang nhap NETHub cua Quy khach";
    public static $error_send_otp_number_max            = 9;
}
