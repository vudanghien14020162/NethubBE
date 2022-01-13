<?php


namespace App\Models\HelpersModel;


use App\Helpers\ConstResponse;
use App\Helpers\DeviceManager;
use App\Helpers\JsonResponseCode;
use App\Models\Menu;
use App\Models\User;
use App\Models\UserSubscription;
use App\Traits\SingletonCache;
use Illuminate\Http\Request;

class UserSubscriptionHelper extends BaseHelper
{
    public static function countMaxDeviceCCU($user){
        $limit = 4;
        $select_plans = UserSubscription::query()
            ->leftJoin('plans', 'plans.id', '=', 'user_subscriptions.plan_id')
            ->select('plans.*')
            ->where('user_subscriptions.user_id', $user->id)
            ->where('user_subscriptions.deleted', false)
            ->orderBy('plans.max_device_ccu', 'desc')
            ->first();
        if($select_plans){
            $limit = isset($select_plans->max_device_ccu) ? $select_plans->max_device_ccu : $limit;
        }
        return (int)$limit;
    }

    public static function getPlanIdCCU($user){
        $group_name = 'DEFAULT';
        //check max thiết bị xem đồng thời
        $select_plans = UserSubscription::query()
            ->leftJoin('plans', 'plans.id', '=', 'user_subscription.plan_id')
            ->select('plans.*')
            ->where('user_subscriptions.user_id', $user->id)
            ->where('user_subscriptions.deleted', false)
            ->orderBy('plans.max_device_ccu', 'desc')
            ->first();
        if($select_plans){
            $group_name = isset($select_plans->plan_id) ? $select_plans->plan_id : $group_name;
        }
        //check max thiet bi xem dong thoi
        return strtoupper($group_name);
    }
}
