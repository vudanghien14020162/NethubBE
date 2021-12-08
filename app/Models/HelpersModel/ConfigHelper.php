<?php


namespace App\Models\HelpersModel;


use App\Helpers\ConstResponse;
use App\Models\Config;

class ConfigHelper extends BaseHelper
{
    public static function getConfig(){
        $singleton = self::getCache();
        $key = ConstResponse::KEY_GET_CONFIG;
        $data = $singleton->getData($key);
        if(empty($data) || is_null($data)){
            $data = Config::query()->first();
            $singleton->createData($key, $data);
        }
        return $data;
    }
}
