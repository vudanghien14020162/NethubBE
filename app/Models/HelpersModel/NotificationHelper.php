<?php


namespace App\Models\HelpersModel;
use App\Helpers\ConstResponse;
use App\Models\Ads;
use App\Models\Base\BaseDataResponse;
use App\Models\Notification;

class NotificationHelper extends BaseHelper
{
    public static function getNotification($offset, $limit){
        $data = Notification::query()
            ->where('status', Notification::STATUS_ACTIVE)
            ->orderBy('id', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();
        return $data;
    }

    public static function countNotification(){
        $startDate = date('Y-m-d 00:00:00', strtotime(now('Asia/Ho_Chi_Minh')));
        $endDate = date('Y-m-d 23:59:59', strtotime(now('Asia/Ho_Chi_Minh')));
        $total = Notification::query()
            ->where('status', Notification::STATUS_ACTIVE)
            ->count();
        $not_viewed = Notification::query()
            ->where('status', Notification::STATUS_ACTIVE)
            ->where('push_time', '>=', $startDate)
            ->where('push_time', '<=', $endDate)
            ->count();
        $viewed = (isset($total) && isset($not_viewed) && ($total - $not_viewed) > 0) ? ($total - $not_viewed) : 0;
        $data = [
            'total' => isset($total) && ($total > 0) ? $total : 0,
            'not_viewed' => isset($not_viewed) && ($not_viewed > 0) ? $not_viewed : 0,
            'viewed' => $viewed
        ];
        return $data;
    }
}
