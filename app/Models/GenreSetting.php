<?php


namespace App\Models;
use App\Models\HelpersModel\RelationHelper;

class GenreSetting extends BaseModel
{
    protected $table = 'general_setting';

    const FREE_TO_VIEW_FLAG = 'free_to_view_flag';
    const MAINTENANCE_PAYMENT = 'maintenance_payment';
    const CRM5_INTERFACE_FLAG = 'crm5_interface_flag';
    const CRM5_INTERFACE_DOMAIN = 'crm5_interface_domain';
    const SYNC_QNET_FLAG = 'sync_qnet_flag';
    const MAINTENANCE_SERVER = 'maintenance_server';
    const EVENT_FLAG = 'event_flag';
    const EVENT_MAX_RANDOM_COUNTDOWN = 'event_max_random_countdown';
    const WEBSOCKET_DOMAIN = 'websocket_domain';
    const CHANNEL_DEFAULT = 'channel_default';
    const SYSTEM_COMMENT_FLAG = 'system_comment_flag';

}
