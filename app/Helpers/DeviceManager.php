<?php


namespace App\Helpers;

use App\Models\HelpersModel\WhiteListUserHelper;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class DeviceManager
{
    protected $redis;
    protected $user_id;
    protected $mobile;
    protected $group_name;
    protected $device_id;
    protected $max_device;
    protected $movie_id;
    protected $movie_name;
    protected $key_cache;
    const EXPIRE = 2 * 60; // 2 minutes
    const KEY_CACHE_PREFIX = 'device_manager';

    public function __construct()
    {
        $this->redis = Redis::connection('default');
    }
    public function setUser($user_id){
        $this->user_id = trim($user_id);
        //mobile
        $user = User::find($this->user_id);
        $this->mobile = isset($user) ? trim($user->mobile) : '';
        return $this;
    }

    public function setGroupName($group_name){
        $this->group_name = trim($group_name);
        return $this;
    }
    public function setMaxDevice($max_device){
        $this->max_device = (int)$max_device;
        return $this;
    }
    public function setMovieId($movie_id){
        $this->movie_id = $movie_id;
        //movie
        $movie = Movie::find($this->movie_id);
        $this->movie_name = isset($movie) ? $movie->title: '';
        return $this;
    }
    public function setDeviceLogon($device_id){
        $this->device_id = trim($device_id);
        return $this;
    }
    public function store(){
        if(WhiteListUserHelper::isVipMobile($this->mobile)){
            return true;
        }
        $this->key_cache = self::KEY_CACHE_PREFIX . ':mobile_' . $this->mobile . '_group_' . $this->group_name . ':device_id_' . $this->device_id;
        $devices_logon = count($this->getAllDeviceLogOn());
        $is_device_exit = $this->isDeviceExits();
        if($is_device_exit || $devices_logon < $this->max_device){
            $data = [
                'user_id' => $this->user_id,
                'mobile' => $this->mobile,
                'movie_id' => $this->movie_id,
                'movie_name' => $this->movie_name,
                'group_name' => $this->group_name,
                'device_id' => $this->device_id,
                'max_device' => (int)$this->max_device,
                'expired_time' => date('Y-m-d H:i:s', time() + self::EXPIRE)
            ];
            $this->redis->set($this->key_cache, json_encode($data));
            $this->redis->expire($this->key_cache, self::EXPIRE);
            return true;
        }else{
            $this->removeDevice();
        }
        return false;
    }

    public function getAllDeviceLogOn(){
        $key_search = self::KEY_CACHE_PREFIX . ':mobile_' . $this->mobile . '_group_' . $this->group_name . ':*';
        return $this->redis->keys($key_search);
    }
    public function isDeviceExits(){
        return $this->redis->get($this->key_cache);
    }
    public function removeDevice(){
        $this->key_cache = self::KEY_CACHE_PREFIX . ':mobile_' . $this->mobile . '_group_' . $this->group_name . ':device_id' . $this->device_id;
        $res = $this->redis->del($this->key_cache);
        return $res;
    }
    public function __destruct(){
        if(isset($this->redis) && $this->redis instanceof Redis){
            $this->redis->close();
        }
    }

}
