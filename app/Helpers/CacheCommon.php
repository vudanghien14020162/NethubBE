<?php


namespace App\Helpers;


use Illuminate\Support\Facades\Redis;
use Cache;

class CacheCommon
{
    private $redis;
    public function __construct()
    {
        $this->redis = Redis::connection('default');
    }
    public function getData($key){
        $key = ConstResponse::KEY_CACHE_PATH . $key;
        $data = Cache::get($key);
        return $data;
    }
    public function createData($key, $data){
        $key = ConstResponse::KEY_CACHE_PATH . $key;
        Cache::set($key, $data, ConstResponse::KEY_CACHE_TIME);
    }
    public function remove($key){
        $key = ConstResponse::KEY_CACHE_PATH . $key;
        return $this->redis->del($key);
    }
}
