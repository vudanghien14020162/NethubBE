<?php


namespace App\Traits;


use App\Helpers\CacheCommon;

trait SingletonCache
{
    protected static ?CacheCommon $instance = null;

    /** call this method to get instance */
    public static function instance(){
        if (static::$instance === null){
            static::$instance = new CacheCommon();
        }
        return static::$instance;
    }

    /** protected to prevent cloning */
    protected function __clone(){
    }

    /** protected to prevent instantiation from outside of the class */
    protected function __construct(){
    }

}
