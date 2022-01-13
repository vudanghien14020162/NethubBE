<?php

namespace App\Models;

class UserWatchedLogs
{
    protected $table = 'user_watched_logs';
    public static function insertLogs($user_id = 0, $content_id = 0, $content_title = '', $device_id = '', $platform = 'web', $type = 0){
        if($user_id > 0 && $content_id > 0 && $device_id != ""){
            $timestamp = date("Y-m-d H:i:s");
            $insert_data = array(
                'user_id' => $user_id,
                'content_id' => $content_id,
                'content_title' => $content_title,
                'device_id' => $device_id,
                'start_time' => $timestamp,
                'end_time' => $timestamp,
                'platform' => $platform
            );
            $rs = UserWatchedLogs::create($insert_data);
            if ($rs) {
                return true;
            } else {
                return false;
            }
        }else{
            return false;
        }
    }
    public static function updateEndtime($user_id = 0, $content_id=0, $device_id=''){
        if($user_id > 0 && $content_id > 0 && $device_id != ""){
            $exitst = UserWatchedLogs::where([
                'user_id' => $user_id,
                'content_id' => $content_id,
                'device_id' => $device_id
            ])->orderBy('id', 'DESC')->first();
            if($exitst){
                $timestamp = date('Y-m-d H:i:s');
                $exitst->end_time = $timestamp;
                $rs = $exitst->save();
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
