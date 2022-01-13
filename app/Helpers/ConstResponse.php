<?php


namespace App\Helpers;


class ConstResponse
{
    const SUCCESS                                       = 200;
    const ERROR                                         = 400;
    const ERROR_LOGIN                                   = 403;
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
    const KEY_MAX_DEVICE_CCU                            = 'key_max_device_ccu:';
    const KEY_MAX_DEVICE_LOGIN                          = 'key_max_device_login:';
    const KEY_ARRAY_EVENT_TICKET_VIP                    = 'key_array_event_ticket_vip:';
    const KEY_ARRAY_EVENT_TICKET_NORMAL                 = 'key_array_event_ticket_normal:';
    const KEY_GET_EVENT_USER_TICKET                     = 'key_api_event_user_ticket:';
    const KEY_GET_FIELD_GENRE_SETTING                   = 'key_get_field_genre_setting:';
    const EXPIRE_TIME_OTP                               = 5;
    const CCU_MAX_OTP                                   = 3;
    const KEY_CACHE_GET_MOVIE_BY_TITLE                  = 'key_cache_get_movie_by_title: ';
    const api_get_event_code                            = 'api_cached:api_get_event_code:';
    const api_get_ticket_code                           = 'api_cached:api_get_ticket_code:';

    public static $message_sms_otp_nethub               = " la ma OTP xac thuc tai khoan NETHub cua Quy Khach";
    public static $message_sms_password                 = " la mat khau mac dinh dang nhap NETHub cua Quy khach";
    public static $error_send_otp_number_max            = 9;
    public static $watched_percent                      = 95;
    public static $message_login                        = "Bạn cần đăng nhập để sử dụng chức năng này.";
    public static $message_blocked_user                 = "Tài khoản này bị khóa do vi phạm chính sách bản quyền NETHub";
    public static $error_group_max_device_user          = 15;
    public static $error_code_no_fail                   = 1;
    public static $error_response_code_no_login         = 7;
    public static $api_popup_event                      = "api_cached:api_popup_event";
    public static $api_get_event_id                     = "api_get_event_id:";
    public static $api_check_movie_in_event             = "api_check_movie_in_event:";

}
