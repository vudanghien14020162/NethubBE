<?php


namespace App\Helpers;


class Crm
{
    public static function crulPost($url, $data=array(), $json_encode=true, $timeout=10){
        return self::_curlCall("POST", $url, $data, $json_encode, $timeout);
    }
    public static function curlGet($url, $timeout=10){
        return self::_curlCall("GET", $url, $timeout);
    }
    public static function curlPut($url, $data = array(), $json_encode=true, $timeout=10){
        return self::_curlCall("PUT", $url, $data, $json_encode, $timeout);
    }
    private static function _curlCall($method="GET", $url, $data=array(), $json_encode=true, $timeout=10){
        if(!is_array($method, array('GET', 'POST', 'PUT'))){
            $method = 'GET';
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_PROXY_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if(is_array($data) && count($data) > 0){
            $payload = $data;
            if($json_encode){
                $payload = $json_encode($data);
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'api_key: ' . '4f347cb0-1aed-4f70-a120-b8b3aee191c6',
            'Content-Type:application/json'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);
        return [
            'header' => $header,
            'data' => $result
        ];
    }
}
