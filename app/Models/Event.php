<?php


namespace App\Models;

use App\Helpers\ConstResponse;

class Event extends BaseModel
{
    protected $table = 'events';

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const NOT_DELETED_EVENT = 0;
    const EVENT_CODE_SHOW_1 = 'ZPAVG_SHOW_01';
    const EVENT_CODE_SHOW_2 = 'ZPAVG_SHOW_02';
    const EVENT_CODE_SHOW_3 = 'ZPAVG_SHOW_03';

    const PROMOTION_START_TIME = '2022-01-07 00:00:00';
    const PROMOTION_END_TIME = '2022-01-07 18:00:00';

    public function eventTicket(){
        return $this->hasMany(EventTicket::class, 'event_code', 'code')
            ->where('parent_id', '!=', 0);
    }


}
