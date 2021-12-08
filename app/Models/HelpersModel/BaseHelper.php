<?php


namespace App\Models\HelpersModel;


use App\Traits\SingletonCache;

class BaseHelper
{
    use SingletonCache;
    /**
     * @return \App\Helpers\CacheCommon|null
     */
    public static function getCache()
    {
        $cache = SingletonCache::instance();
        return $cache;
    }

}
