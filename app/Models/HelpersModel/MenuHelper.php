<?php


namespace App\Models\HelpersModel;


use App\Helpers\ConstResponse;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuHelper extends BaseHelper
{
    public static function getMenu(Request $request){
        $type = isset($request->type) ? $request->type : null;
        if(is_null($type)){
            $data = self::getMenuNotType($request);
        }else{
            $data = self::getMenuByType($request);
        }
        return $data;
    }

    public static function getMenuNotType(Request $request){
        $cache = self::getCache();
        $os = $request->header('os');
        $key = ConstResponse::KEY_GET_MENU . '_os_' . $os;
        $data = $cache->getData($key);
        if(empty($data) || is_null($data)){
            $data = Menu::query()
                ->where('status', Menu::STATUS_ACTIVE)
                ->where('deleted', Menu::NOT_DELETED)
                ->get();
            $cache->createData($key, $data);
        }
        return $data;
    }

    public static function getMenuByType(Request $request){
        $cache = self::getCache();
        $os = $request->header('os');
        $type = $request->type;
        $key = ConstResponse::KEY_GET_MENU . '_os_' . $os . '_type_' . $type;
        $data = $cache->getData($key);
        if(empty($data) || is_null($data)){
            $data = Menu::query()
                ->where('status', Menu::STATUS_ACTIVE)
                ->where('deleted', Menu::NOT_DELETED)
                ->whereRaw('FIND_IN_SET(' . $type . ',type)')
                ->get();
            $cache->createData($key, $data);
        }
        return $data;
    }

}
