<?php

namespace App\Helpers;

class HelperChangeTime
{

    public static function helperChangeTime($timeChange){
        if($timeChange != null){
            $minutes = 0;
            $seconds  = 0;
            if(self::validTime($timeChange, 'i')){
                return $timeChange * 60 * 1000;
            }elseif (self::validTime($timeChange, 'i:s')){
                $str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $timeChange);
                sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
                $time_seconds = $hours * 3600 + $minutes * 60 + $seconds;
                return ($time_seconds * 1000);
            }elseif (self::validTime($timeChange, 'H:i:s')){
                $str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $timeChange);
                sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
                $time_seconds = $hours * 3600 + $minutes * 60 + $seconds;
                return ($time_seconds * 1000);
            }elseif (is_numeric($timeChange)){
                return $timeChange * 60 * 1000;
            }else{
                return $timeChange;
            }
        }else{
            return 0;
        }
    }

    public static function validTime($time, $format='H:i:s'){
        $d
            = \DateTime
            ::createFromFormat("Y-m-d $format", "2017-12-01 $time");
        return $d && $d->format($format) == $time;
    }
}
