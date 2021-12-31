<?php

namespace App\Helpers;

use App\Models\HelpersModel\HistoryLoginHelper;
use http\Env\Request;

class UUIDForPC
{
    public static function UniqueMachineID($salt=""){
        if(strtoupper(substr(PHP_OS, 0, 3) === 'WIN')){
            $temp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . "diskpartscipt.txt";
            if(!file_exists($temp) && !is_file($temp))
                file_put_contents($temp, "select disk 0\ndetail disk");
            $output = shell_exec("diskpart /s" . $temp);
            $lines = explode("\n", $output);
            $result = array_filter($lines, function ($line){
                return stripos($line, "ID:") !== false;
            });
            if(count($result) > 0){
                $result = array_shift(array_values($result));
                $result = explode(":", $result);
                $result = trim(end($result));
            }else $result = $output;
        }else{
            $result = shell_exec("blkid -o value -s UUID");
            if(stripos($result, 'blkid') !== false){
                $result = $_SERVER['HTTP_HOST'];
            }
        }
        return md5($salt . md5($result));
    }

    public static function getMacAddressExec(){
        $shellexec = shell_exec('getmax');
        dd($shellexec);
    }
    public static function getMacAddressShellExec(){
        $shellexec = exec('getmac');
        dd($shellexec);
    }

    public static function device_login($user_id, $device_id, $device_model = null){
        $os = \request()->header('os', '');
        $platform = \request()->header('platform', 'web');
        if($device_id == null) return false;
        $agent = new Agent();
        $user_device = HistoryLoginHelper::getHistoryDeviceFirst($user_id, $device_id);
        if($user_device){
            $user_device->device = $device_model ? $device_model : 'WebKit';
            $user_device->ip = self::getIP();
        }

    }

    public static function getIP(){
        $ipaddress = '';
        if(getenv('HTTP_CLIENT_IP')){
            $ipaddress = getenv('HTTP_CLIENT_IP');
        }else if(getenv('HTTP_X_FORWARDED_FOR')){
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        }else if(getenv('HTTP_X_FORWARDED')){
            $ipaddress = getenv('HTTP_X_FORWARDED');
        }else if(getenv('HTTP_FORWARDED_FOR')){
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        }else if(getenv('HTTP_FORWARDED')){
            $ipaddress = getenv('HTTP_FORWARDED');
        }else if(getenv('REMOTE_ADDR')){
            $ipaddress = getenv('REMOTE_ADDR');
        }else{
            $ipaddress = "UNKNOWN";
        }
        return $ipaddress;
    }
    public static function getIpServer(){
        $ipaddress = '';
        if(isset($_SERVER['SERVER_ADDR'])){
            $ipaddress = $_SERVER['SERVER_ADDR'];
        }elseif (isset($_SERVER['LOCAL_ADDR'])){
            $ipaddress = $_SERVER['LOCAL_ADDR'];
        }else{
            $ipaddress = 'Unknown';
        }
        return $ipaddress;
    }
    public static function getBrowser(){
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $browser = "Unknown Browser";
        $browser_array = array(
            '/msie/i' => 'Internet Explorer',
            '/firefox/i' => 'Firefox',
            '/safari/i' => 'Safari',
            '/chrome/i' => 'Chrome',
            '/edge/i' => 'Edge',
            '/opera/i' => 'Opera',
            '/netscape/i' => 'Netscape',
            '/maxthon/i' => 'Maxthon',
            '/kongueror/i' => 'Kongqueror',
            '/mobile/i' => 'Handheld Browser'
        );
        foreach ($browser_array as $regex => $value){
            if(preg_match($regex, $user_agent)){
                $browser = $value;
            }
        }
        return $browser;
    }
}
